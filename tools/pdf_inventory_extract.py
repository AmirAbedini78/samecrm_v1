#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Extract inventory movement data from any PDF (گردش کالا).
Detects product code/name from document and parses table rows.
Output: JSON to stdout.

Usage:
  python tools/pdf_inventory_extract.py --pdf path/to/file.pdf [--inventory-json path/to/list.json]

Optional --inventory-json: JSON array of {"code": "...", "name": "..."} for matching.
"""

from __future__ import print_function, unicode_literals

import argparse
import json
import os
import re
import sys

# Optional: try pdfplumber for table/text extraction
try:
    import pdfplumber
    HAS_PDFPLUMBER = True
except ImportError:
    HAS_PDFPLUMBER = False

# Optional: PyMuPDF as fallback for text
try:
    import fitz  # PyMuPDF
    HAS_PYMUPDF = True
except ImportError:
    HAS_PYMUPDF = False


def normalize_float(s):
    if s is None or s == '':
        return 0.0
    s = str(s).strip().replace(',', '')
    s = re.sub(r'[^\d.\-]', '', s)
    try:
        return float(s)
    except ValueError:
        return 0.0


def normalize_date(s):
    if s is None or s == '':
        return None
    s = str(s).strip()
    # Keep digits and / and -
    s = re.sub(r'[^\d/\-.]', '', s)
    return s if s else None


# Persian/English column name variants for movement table
COL_DATE = ['تاريخ', 'تاریخ', 'date', 'تاریخ سند', 'تاریخ تراکنش']
COL_DOC = ['سند', 'شماره سند', 'document', 'entry_code', 'سند ورود']
COL_TYPE = ['نوع', 'type', 'entry_type', 'نوع تراکنش', 'ورودی', 'خروجی']
COL_BASE_DOC = ['شماره سند مبنا', 'سند مبنا', 'document_number', 'base_document']
COL_QTY = ['مقدار', 'quantity', 'qty', 'amount', 'تعداد']
COL_UNIT_PRICE = ['في', 'فی', 'unit_price', 'price', 'قیمت واحد']
COL_TOTAL = ['مبلغ تمام شده', 'مبلغ', 'total_amount', 'total_cost', 'مبلغ کل']


def normalize_header(h):
    if h is None:
        return ''
    return str(h).strip().replace('\n', ' ').replace('\r', ' ')


def find_column_index(headers, candidates):
    headers_norm = [normalize_header(h) for h in headers]
    for c in candidates:
        for i, h in enumerate(headers_norm):
            if c in h or h in c:
                return i
    return -1


def extract_text_pdfplumber(pdf_path):
    text_parts = []
    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            t = page.extract_text()
            if t:
                text_parts.append(t)
    return '\n'.join(text_parts)


def extract_text_pymupdf(pdf_path):
    text_parts = []
    doc = fitz.open(pdf_path)
    for page in doc:
        text_parts.append(page.get_text())
    doc.close()
    return '\n'.join(text_parts)


def extract_tables_pdfplumber(pdf_path):
    all_tables = []
    with pdfplumber.open(pdf_path) as pdf:
        for i, page in enumerate(pdf.pages):
            tables = page.extract_tables()
            if tables:
                for t in tables:
                    if t and len(t) > 0:
                        all_tables.append({'page': i + 1, 'rows': t})
    return all_tables


def detect_product_from_text(text, inventory_list=None):
    """Detect product code or name from full text."""
    if not text:
        return None, None
    text_lower = text.replace('\n', ' ').replace('\r', ' ')
    found_code = None
    found_name = None

    # Pattern: کد کالا: XXX or کد: XXX or product code: XXX
    patterns_code = [
        r'کد\s*کالا\s*[:\-]\s*([^\s\n\r،,]+)',
        r'کد\s*[:\-]\s*([^\s\n\r،,]+)',
        r'product\s*code\s*[:\-]\s*([^\s\n\r,]+)',
        r'کد\s*محصول\s*[:\-]\s*([^\s\n\r،,]+)',
    ]
    for pat in patterns_code:
        m = re.search(pat, text_lower, re.IGNORECASE)
        if m:
            found_code = m.group(1).strip()
            break

    # Match from inventory list (fuzzy: code or name appears in text)
    if inventory_list and isinstance(inventory_list, list):
        for item in inventory_list:
            code = (item.get('code') or item.get('inventory_code') or '').strip()
            name = (item.get('name') or item.get('inventory_name') or '').strip()
            if code and code in text_lower:
                found_code = code
                found_name = name or None
                break
            if name and len(name) > 2 and name in text_lower:
                found_name = name
                if not found_code and code:
                    found_code = code
                break

    return found_code, found_name


def parse_table_to_rows(table_rows, headers_row_idx=0):
    """Parse a table (list of rows) into structured movement rows."""
    if not table_rows or len(table_rows) <= headers_row_idx:
        return []
    headers = table_rows[headers_row_idx]
    idx_date = find_column_index(headers, COL_DATE)
    idx_doc = find_column_index(headers, COL_DOC)
    idx_type = find_column_index(headers, COL_TYPE)
    idx_base = find_column_index(headers, COL_BASE_DOC)
    idx_qty = find_column_index(headers, COL_QTY)
    idx_price = find_column_index(headers, COL_UNIT_PRICE)
    idx_total = find_column_index(headers, COL_TOTAL)

    # Need at least date and quantity to be useful
    if idx_qty < 0:
        return []
    rows_out = []
    for r in table_rows[headers_row_idx + 1:]:
        if not r:
            continue
        # Pad row to have enough cells
        while len(r) <= max(idx_date, idx_doc, idx_type, idx_base, idx_qty, idx_price, idx_total):
            r = list(r) + [None]
        qty = normalize_float(r[idx_qty] if idx_qty >= 0 else 0)
        if qty <= 0:
            continue
        row = {
            'entry_date': normalize_date(r[idx_date]) if idx_date >= 0 else None,
            'entry_code': str(r[idx_doc]).strip() if idx_doc >= 0 and r[idx_doc] else '',
            'entry_type': str(r[idx_type]).strip() if idx_type >= 0 and r[idx_type] else 'ورودی',
            'document_number': str(r[idx_base]).strip() if idx_base >= 0 and r[idx_base] else '',
            'quantity': qty,
            'unit_price': normalize_float(r[idx_price]) if idx_price >= 0 else 0,
            'total_amount': normalize_float(r[idx_total]) if idx_total >= 0 else (qty * normalize_float(r[idx_price]) if idx_price >= 0 else 0),
        }
        rows_out.append(row)
    return rows_out


def find_best_table(tables):
    """Find the table with most rows that look like movement data (has numbers)."""
    best = []
    for tbl in tables:
        rows = tbl.get('rows', [])
        if len(rows) < 2:
            continue
        parsed = parse_table_to_rows(rows)
        if len(parsed) > len(best):
            best = parsed
    return best


def main():
    parser = argparse.ArgumentParser(description='Extract inventory movement from PDF')
    parser.add_argument('--pdf', required=True, help='Path to PDF file')
    parser.add_argument('--inventory-json', default=None, help='Path to JSON list of {code, name}')
    args = parser.parse_args()

    pdf_path = os.path.abspath(args.pdf)
    if not os.path.isfile(pdf_path):
        out = {'success': False, 'error': 'PDF file not found', 'inventory_code': None, 'rows': []}
        print(json.dumps(out, ensure_ascii=False))
        return 1

    inventory_list = None
    if args.inventory_json and os.path.isfile(args.inventory_json):
        try:
            with open(args.inventory_json, 'r', encoding='utf-8') as f:
                inventory_list = json.load(f)
        except Exception:
            pass

    full_text = ''
    tables = []

    if HAS_PDFPLUMBER:
        try:
            full_text = extract_text_pdfplumber(pdf_path)
            tables = extract_tables_pdfplumber(pdf_path)
        except Exception as e:
            full_text = str(e)
    elif HAS_PYMUPDF:
        try:
            full_text = extract_text_pymupdf(pdf_path)
        except Exception as e:
            full_text = str(e)
    else:
        out = {
            'success': False,
            'error': 'Install pdfplumber: pip install pdfplumber',
            'inventory_code': None,
            'inventory_name': None,
            'rows': [],
            'raw_text_preview': ''
        }
        print(json.dumps(out, ensure_ascii=False))
        return 2

    inventory_code, inventory_name = detect_product_from_text(full_text, inventory_list)
    movement_rows = find_best_table(tables)

    # If no table found but we have text, try to find lines that look like numbers (quantity)
    if not movement_rows and full_text:
        for line in full_text.split('\n'):
            parts = re.split(r'\s+', line.strip())
            nums = [normalize_float(p) for p in parts if normalize_float(p) > 0]
            if len(nums) >= 2 and len(parts) >= 4:
                row = {
                    'entry_date': None,
                    'entry_code': '',
                    'entry_type': 'ورودی',
                    'document_number': '',
                    'quantity': nums[0],
                    'unit_price': nums[1] if len(nums) > 1 else 0,
                    'total_amount': nums[2] if len(nums) > 2 else (nums[0] * nums[1]),
                }
                movement_rows.append(row)

    out = {
        'success': True,
        'inventory_code': inventory_code,
        'inventory_name': inventory_name,
        'rows': movement_rows,
        'raw_text_preview': (full_text[:2000] + '...') if len(full_text) > 2000 else full_text,
    }
    print(json.dumps(out, ensure_ascii=False))
    return 0


if __name__ == '__main__':
    sys.exit(main())

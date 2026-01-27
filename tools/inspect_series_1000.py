#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Inspect SERIES 1000.xlsx (multi-sheet) without external deps.
Python 2.7 compatible.

Usage:
  python tools/inspect_series_1000.py "C:\\laragon\\www\\samecrm_v1\\public\\documents\\xlsx\\SERIES 1000.xlsx"
"""

from __future__ import unicode_literals

import os
import re
import sys
import zipfile
import xml.etree.ElementTree as ET

NS_P = 'http://schemas.openxmlformats.org/package/2006/relationships'
NS_R = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships'
NS_M = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'


def _tag(ns, name):
    return '{%s}%s' % (ns, name)


def read_xml(zf, name):
    return ET.fromstring(zf.read(name))


def load_shared_strings(zf):
    try:
        root = read_xml(zf, 'xl/sharedStrings.xml')
    except KeyError:
        return []
    out = []
    for si in root.findall(_tag(NS_M, 'si')):
        parts = []
        t = si.find(_tag(NS_M, 't'))
        if t is not None and t.text:
            parts.append(t.text)
        for rnode in si.findall(_tag(NS_M, 'r')):
            t2 = rnode.find(_tag(NS_M, 't'))
            if t2 is not None and t2.text:
                parts.append(t2.text)
        out.append(b''.join(parts))
    return out


def cell_value(c, sst):
    t = c.get('t')
    v = c.find(_tag(NS_M, 'v'))
    if v is None or v.text is None:
        return b''
    raw = v.text
    if t == 's':
        try:
            return sst[int(raw)]
        except Exception:
            return b''
    return raw


def get_sheet_rows(zf, sheet_path, sst, max_rows=2):
    root = read_xml(zf, sheet_path)
    sheetData = root.find(_tag(NS_M, 'sheetData'))
    if sheetData is None:
        return []
    rows = []
    for row in list(sheetData.findall(_tag(NS_M, 'row')))[:max_rows]:
        cells = {}
        for c in row.findall(_tag(NS_M, 'c')):
            ref = c.get('r')
            cells[ref] = cell_value(c, sst)
        rows.append(cells)
    return rows


def safe_utf8(x):
    try:
        if isinstance(x, unicode):
            return x.encode('utf-8')
    except Exception:
        pass
    try:
        return x.decode('utf-8', 'ignore').encode('utf-8')
    except Exception:
        try:
            return str(x)
        except Exception:
            return b''


_SHEET_WEIGHT_RE = re.compile(r'\(([^)]+)\)')


def parse_sheet_name(name):
    """
    Examples:
      '1111 (1Kg)'
      '1141 (1.5 Kg)'
      '1391 (10 lit)'
      '1341 N (750 Gr)'
    """
    name_b = safe_utf8(name)
    # keep raw bytes, but also try a basic split
    m = _SHEET_WEIGHT_RE.search(name_b)
    weight_raw = m.group(1).strip() if m else b''
    product_part = name_b
    if m:
        product_part = name_b[:m.start()].strip()
    return product_part, weight_raw


def main():
    if len(sys.argv) < 2:
        sys.stderr.write("Missing xlsx path\n")
        return 2

    xlsx = sys.argv[1]
    if not os.path.exists(xlsx):
        sys.stderr.write("File not found: %s\n" % xlsx)
        return 2

    with zipfile.ZipFile(xlsx, 'r') as zf:
        sst = load_shared_strings(zf)
        wb = read_xml(zf, 'xl/workbook.xml')
        rels = read_xml(zf, 'xl/_rels/workbook.xml.rels')

        rid_to_target = {}
        for rel in rels.findall(_tag(NS_P, 'Relationship')):
            rid_to_target[rel.get('Id')] = rel.get('Target')

        sheets = []
        sheets_node = wb.find(_tag(NS_M, 'sheets'))
        for sh in sheets_node.findall(_tag(NS_M, 'sheet')):
            name = sh.get('name') or ''
            rid = sh.get('{%s}id' % NS_R) or ''
            target = rid_to_target.get(rid)
            if target and not target.startswith('xl/'):
                target = 'xl/' + target
            sheets.append((name, rid, target))

        sys.stdout.write(b"sheet_count=%d\n" % len(sheets))

        header_sets = {}
        max_cols = {}
        extra_cols = []

        for idx, (name, rid, target) in enumerate(sheets, 1):
            name_b = safe_utf8(name)
            product_part, weight_raw = parse_sheet_name(name)
            sys.stdout.write(b"[%d] sheet=%s | product_part=%s | weight_raw=%s | target=%s\n" % (
                idx, name_b, product_part, weight_raw, safe_utf8(target)
            ))

            rows = get_sheet_rows(zf, target, sst, max_rows=2)
            if not rows:
                continue

            # header row is first row
            header = rows[0]
            # determine max column letter present in header row
            cols = sorted([ref.rstrip('0123456789') for ref in header.keys()])
            last_col = cols[-1] if cols else ''
            max_cols[name_b] = last_col

            # map A1.. to header values
            header_map = {}
            for ref, val in header.items():
                header_map[ref] = safe_utf8(val)
            # keep normalized header signature
            sig = tuple(sorted([(k, header_map[k]) for k in header_map.keys()]))
            header_sets.setdefault(sig, []).append(name_b)

            if last_col and last_col not in ['H', 'I']:
                extra_cols.append((name_b, last_col))

        sys.stdout.write(b"\n--- header variants ---\n")
        sys.stdout.write(b"variants=%d\n" % len(header_sets))
        for i, (sig, sheet_names) in enumerate(header_sets.items(), 1):
            sys.stdout.write(b"\nvariant[%d] sheets=%d sample_sheet=%s\n" % (i, len(sheet_names), sheet_names[0]))
            for k, v in sig:
                sys.stdout.write(safe_utf8(k) + b"=" + safe_utf8(v) + b"\n")

        sys.stdout.write(b"\n--- max header column per sheet (spot extras) ---\n")
        for sheet, last in sorted(max_cols.items()):
            sys.stdout.write(sheet + b" => " + safe_utf8(last) + b"\n")

        if extra_cols:
            sys.stdout.write(b"\nextra_col_sheets:\n")
            for sheet, last in extra_cols:
                sys.stdout.write(sheet + b" => " + safe_utf8(last) + b"\n")

    return 0


if __name__ == '__main__':
    sys.exit(main())


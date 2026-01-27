#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Inspect .xlsx (Office Open XML) without external dependencies.
Compatible with Python 2.7.

Usage:
  python tools/inspect_xlsx.py "C:\\path\\to\\file.xlsx"
"""

from __future__ import unicode_literals

import os
import sys
import zipfile
import xml.etree.ElementTree as ET


NS_R = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships'
NS_P = 'http://schemas.openxmlformats.org/package/2006/relationships'
NS_M = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'


def _tag(ns, name):
    return '{%s}%s' % (ns, name)


def read_xml(zf, name):
    data = zf.read(name)
    return ET.fromstring(data)


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


def iter_rows(zf, sheet_path, sst, max_rows=8):
    root = read_xml(zf, sheet_path)
    sheetData = root.find(_tag(NS_M, 'sheetData'))
    if sheetData is None:
        return []

    rows = []
    for row in list(sheetData.findall(_tag(NS_M, 'row')))[:max_rows]:
        cells = {}
        for c in row.findall(_tag(NS_M, 'c')):
            ref = c.get('r')  # e.g. A1
            cells[ref] = cell_value(c, sst)
        rows.append(cells)
    return rows


def main():
    if len(sys.argv) < 2:
        sys.stderr.write("Missing xlsx path\n")
        return 2

    xlsx = sys.argv[1]
    if not os.path.exists(xlsx):
        sys.stderr.write("File not found: %s\n" % xlsx)
        return 2

    zf = zipfile.ZipFile(xlsx, 'r')
    try:
        sst = load_shared_strings(zf)
        wb = read_xml(zf, 'xl/workbook.xml')
        rels = read_xml(zf, 'xl/_rels/workbook.xml.rels')

        rid_to_target = {}
        # Note: .rels files use the PACKAGE relationships namespace (NS_P)
        for rel in rels.findall(_tag(NS_P, 'Relationship')):
            rid_to_target[rel.get('Id')] = rel.get('Target')

        print("rels_count: %d" % len(rid_to_target))
        # show a few rels for debugging
        shown = 0
        for k in sorted(rid_to_target.keys()):
            print("  rel: %s -> %s" % (k, rid_to_target[k]))
            shown += 1
            if shown >= 5:
                break

        sheets = []
        sheets_node = wb.find(_tag(NS_M, 'sheets'))
        if sheets_node is None:
            print("No sheets node")
            return 1

        for sh in sheets_node.findall(_tag(NS_M, 'sheet')):
            name = sh.get('name') or ''
            rid = sh.get(_tag(NS_R, 'id')) or sh.get('{%s}id' % NS_R)
            target = rid_to_target.get(rid)
            if target and not target.startswith('xl/'):
                target = 'xl/' + target
            sheets.append((name, rid, target))

        print("sheet_count: %d" % len(sheets))
        for i, (name, rid, target) in enumerate(sheets, 1):
            try:
                name_u = name.encode('utf-8')
            except Exception:
                name_u = str(name)
            print("[%d] name=%s rid=%s target=%s" % (i, name_u, rid, target))

        print("\n--- sample rows per sheet (first 8 rows, raw cell map) ---")
        for i, (name, rid, target) in enumerate(sheets, 1):
            try:
                name_u = name.encode('utf-8')
            except Exception:
                name_u = str(name)

            if not target:
                print("\n[%d] %s: missing target" % (i, name_u))
                continue

            try:
                rows = iter_rows(zf, target, sst, max_rows=8)
            except KeyError:
                print("\n[%d] %s: missing sheet xml %s" % (i, name_u, target))
                continue

            print("\n[%d] %s (%s)" % (i, name_u, target))
            for r in rows:
                # show sorted keys for stable output
                keys = sorted(r.keys())
                parts = []
                for k in keys:
                    v = r[k]
                    # keys are ascii like A1, B2...
                    try:
                        k_b = k.encode('utf-8')
                    except Exception:
                        k_b = str(k)

                    # values may be bytes; try decode then re-encode safely
                    try:
                        if isinstance(v, unicode):
                            v_u = v
                        else:
                            v_u = v.decode('utf-8', 'ignore')
                    except Exception:
                        try:
                            v_u = unicode(v)
                        except Exception:
                            v_u = unicode(str(v), errors='ignore')

                    try:
                        v_b = v_u.encode('utf-8')
                    except Exception:
                        v_b = str(v_u)

                    parts.append(k_b + b"=" + v_b)

                sys.stdout.write(b"  " + b" | ".join(parts) + b"\n")

    finally:
        zf.close()

    return 0


if __name__ == '__main__':
    sys.exit(main())


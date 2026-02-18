# 00-START-HERE-CURSOR — Entry Point for Cursor (Cloud Models)

Date: 2026-02-17

Purpose:
User only states intent:
- "می‌خوام انبار بلزونا رو تغییر بدم: ..."
- "می‌خوام صفحه X رو توسعه بدم: ..."

Cursor must:
1) Read AI-RULES.md
2) Resolve module from 02-domains/INDEX.yml
3) Recursively read depends_on
4) Validate code (touches_code + validation_queries)
5) Apply change
6) Update History / ADR / depends_on
7) Ask if work is complete → if yes, run Progressive Expansion (full mode)

Language policy:
- Persian explanations
- English technical identifiers

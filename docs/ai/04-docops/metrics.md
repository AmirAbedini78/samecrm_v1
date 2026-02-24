# Metrics — سنجش کیفیت مستندسازی و خود-بهبود (Doc Quality Metrics)

تاریخ شروع: 2026-02-17

---

## قالب Entry
- Date
- Module
- Change Type: bugfix | feature | refactor | doc-only
- Docs Updated: yes/no
- ADR Created/Updated: yes/no
- Mismatch Count: 0/1/2...
- Doc Patch Accepted: yes/no
- Open Questions Count
- Regression Found: yes/no
- Notes (1-2 خط)

---

## قواعد تفسیر
- Mismatch بالا → داکیومنت از کد عقب است.
- Doc Patch Accepted پایین → Validation Queries باید بهتر شود.
- Regression بالا → smoke_checks ناکافی است.

---

## Entries

- 2026-02-23 | inventory-belzona | feature | Docs: yes | ADR: no | Mismatch: 0 | COC برای همه رکوردها + مودال افزودن COC (دیتاتیبل + آپلود به ازای inbound_id). Gate: yes.
- 2026-02-23 | inventory-belzona | feature | Docs: yes | ADR: no | Mismatch: 0 | ستون COC + مودال اسلایدر (زوم، تمام‌صفحه، prev/next) + رفع مسیر و scandir
- 2026-02-23 | inventory-belzona | refactor | Docs: yes | ADR: no | Mismatch: 0 | Doc Patch: yes | بهینه‌سازی دیتاتیبل ورودها (حذف ساب‌کوئری‌های سنگین)، آپدیت metrics/COVERAGE/expansion
- 2026-02-18 | inventory-belzona | feature | Docs: yes | ADR: no | Mismatch: 0 | باکس آخرین مانده خروجی‌های پارت

---

## قانون خود-بهبود
هر 5 entry یک “Improvement Proposal” در `04-docops/improvements.md` ثبت کن.

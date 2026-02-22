# SmartDocs Guard Quickstart (v15)

This version fixes two UX issues:
1) No `make` needed on Windows (use `task` wrapper).
2) Module-id memorization not needed (aliases + fuzzy resolve via INDEX.yml).

## Windows usage (cmd/PowerShell/Laragon terminal)
Start a task:
```bash
task start "انبار بلزونا" "swap datepickers"
```
Or advanced:
```bash
task start --module="inventory-belzona" --name="swap datepickers"
```

Record Gate (after AI asks and you answer in chat):
```bash
task gate no
# or
task gate yes
```

Close task (mechanical check):
```bash
task close
```

Status:
```bash
task status
```

## macOS/Linux usage
```bash
./task start "انبار بلزونا" "swap datepickers"
./task gate no
./task close
```

If close fails, paste the error into Cursor and tell it to complete missing artifacts.

# SmartDocs Guard Quickstart (v14)

This package adds a mechanical guard to prevent AI shortcutting.

## Start a task (operator)
From repo root:
```bash
make task-start module="inventory-belzona" name="swap datepickers"
```

## Work in Cursor chat
Give your short intent. The AI implements changes and updates docs.

## Record Gate (after the AI asks and you answer in chat)
If you answered YES:
```bash
make task-gate-yes
```
If you answered NO:
```bash
make task-gate-no
```

## Close task (must pass checks)
```bash
make task-close
```

If close fails, update missing artifacts and retry.

## Inspect state
```bash
make task-status
```

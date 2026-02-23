@echo off
setlocal EnableExtensions EnableDelayedExpansion

REM SmartDocs task wrapper (Windows) - SAFE v15
REM Usage:
REM   task start "target phrase" "task name"
REM   task start --module="inventory-belzona" --name="swap datepickers"
REM   task gate yes|no
REM   task close
REM   task status

set "CMD=%~1"
if "%CMD%"=="" goto help
shift

if /I "%CMD%"=="start" goto start
if /I "%CMD%"=="gate" goto gate
if /I "%CMD%"=="close" goto close
if /I "%CMD%"=="status" goto status

:help
echo Usage:
echo   task start "target phrase" "task name"
echo   task start --module="inventory-belzona" --name="swap datepickers"
echo   task gate yes^|no
echo   task close
echo   task status
exit /b 1

:start
REM If user passed flags, forward directly
echo %* | findstr /C:"--name=" >nul
if not errorlevel 1 (
  python tools\smartdocs\smartdocs.py start %*
  exit /b %errorlevel%
)

REM Positional: target phrase + task name
set "TARGET=%~1"
set "NAME=%~2"
if "%NAME%"=="" (
  echo ERROR: Provide both target phrase and task name.
  goto help
)

python tools\smartdocs\smartdocs.py start --module "%TARGET%" --target "%TARGET%" --name "%NAME%"
exit /b %errorlevel%

:gate
set "ANS=%~1"
if "%ANS%"=="" goto help
python tools\smartdocs\smartdocs.py gate --answer %ANS%
exit /b %errorlevel%

:close
python tools\smartdocs\smartdocs.py close
exit /b %errorlevel%

:status
python tools\smartdocs\smartdocs.py status
exit /b %errorlevel%
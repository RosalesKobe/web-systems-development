@echo off
title OJT ServerSide (PHP - WAMP)

rem Check if WAMP (wampmanager.exe) is already running
tasklist /FI "IMAGENAME eq wampmanager.exe" | find /I "wampmanager.exe" >nul
if errorlevel 1 (
    echo WAMP is not running. Starting WAMP Server...
    start "" "C:\wamp64\wampmanager.exe"
) else (
    echo WAMP is already running.
)

echo Waiting for MySQL to be ready...

:CheckMySQL
powershell -Command ^
  "$tcp = Test-NetConnection -ComputerName 127.0.0.1 -Port 3306; exit ($tcp.TcpTestSucceeded -eq $false)"

if %errorlevel%==1 (
    echo MySQL not ready yet, waiting 2 seconds...
    timeout /t 2 >nul
    goto CheckMySQL
)

echo MySQL is ready! Opening ServerSide index...
start "" "http://localhost/web-systems-development/ServerSide/html/server_index.php"

pause

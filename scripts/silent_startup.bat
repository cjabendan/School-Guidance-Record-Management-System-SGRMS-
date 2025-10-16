@echo off
:: ------------------------------
:: Check and start Apache silently
:: ------------------------------
tasklist /FI "IMAGENAME eq httpd.exe" | find /I "httpd.exe" >nul
if errorlevel 1 (
    cd "C:\xampp\apache\bin"
    start "" /b httpd.exe
)

:: ------------------------------
:: Check and start MySQL silently
:: ------------------------------
tasklist /FI "IMAGENAME eq mysqld.exe" | find /I "mysqld.exe" >nul
if errorlevel 1 (
    cd "C:\xampp\mysql\bin"
    start "" /b mysqld.exe
)

:: ------------------------------
:: Wait until Apache (port 80) is ready
:: ------------------------------
echo Waiting for Apache to start...
:CheckApache
powershell -Command "try { $tcp = Test-NetConnection -ComputerName 127.0.0.1 -Port 80; if($tcp.TcpTestSucceeded){exit 0}else{exit 1} } catch {exit 1}"
if errorlevel 1 (
    timeout /t 1 >nul
    goto CheckApache
)

:: ------------------------------
:: Wait until MySQL (port 3306) is ready
:: ------------------------------
echo Waiting for MySQL to start...
:CheckMySQL
powershell -Command "try { $tcp = Test-NetConnection -ComputerName 127.0.0.1 -Port 3306; if($tcp.TcpTestSucceeded){exit 0}else{exit 1} } catch {exit 1}"
if errorlevel 1 (
    timeout /t 1 >nul
    goto CheckMySQL
)

:: ------------------------------
:: Start Laravel server silently
:: ------------------------------
cd "C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS"
start "" /b php artisan serve >nul 2>&1

@echo off
echo Stopping Laravel, Apache, and MySQL...

:: Stop Laravel
taskkill /IM php.exe /F

:: Stop Apache
taskkill /IM httpd.exe /F

:: Stop MySQL
taskkill /IM mysqld.exe /F

echo All processes stopped.
pause

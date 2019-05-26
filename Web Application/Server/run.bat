@echo off
set minimized=true
cd "D:\SE\Projects\RealHack\Registration\Server"

start /min cmd /C "node server.js"

"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" --start-fullscreen http://localhost:3000 http://localhost/realhack http://localhost/realhack/log.php

exit
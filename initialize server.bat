@echo off

start cmd.exe /k php artisan serve
start cmd.exe /k npm run watch-poll
start C:\xampp\htdocs\cleaning_service_provider
start "" "C:\Program Files\Sublime Text 3\sublime_text.exe"
start "" "C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" "http://localhost:8000"
exit
pwsh -Command "Invoke-WebRequest -Uri https://getcomposer.org/download/latest-stable/composer.phar -OutFile composer.phar"
if not exist composer.phar (
    powershell -Command "Invoke-WebRequest -Uri https://getcomposer.org/download/latest-stable/composer.phar -OutFile composer.phar"
)
C:\xampp\php\php.exe composer.phar install > composer_output.txt 2>&1
C:\xampp\php\php.exe artisan key:generate >> composer_output.txt 2>&1
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS lav_sms;" >> composer_output.txt 2>&1
C:\xampp\php\php.exe artisan migrate --seed >> composer_output.txt 2>&1
echo Done > setup_done.txt

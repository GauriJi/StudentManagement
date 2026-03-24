<?php
echo "Starting PHP setup script...\n";
$composerUrl = 'https://getcomposer.org/download/latest-stable/composer.phar';
if (!file_exists('composer.phar')) {
    echo "Downloading Composer...\n";
    file_put_contents('composer.phar', file_get_contents($composerUrl));
}
echo "Composer downloaded.\n";

echo "Running composer install...\n";
$composerOutput = shell_exec('c:\xampp\php\php.exe composer.phar install 2>&1');
file_put_contents('setup_log.txt', "Composer Install Output:\n" . $composerOutput . "\n");

echo "Generating App Key...\n";
$keyOutput = shell_exec('c:\xampp\php\php.exe artisan key:generate 2>&1');
file_put_contents('setup_log.txt', "App Key Output:\n" . $keyOutput . "\n", FILE_APPEND);

echo "Creating Database...\n";
$dbOutput = shell_exec('c:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS lav_sms;" 2>&1');
file_put_contents('setup_log.txt', "DB Status Output:\n" . $dbOutput . "\n", FILE_APPEND);

echo "Running Migrations...\n";
$migOutput = shell_exec('c:\xampp\php\php.exe artisan migrate --seed 2>&1');
file_put_contents('setup_log.txt', "Migrations Output:\n" . $migOutput . "\n", FILE_APPEND);

echo "Finished.";
file_put_contents('setup_done.txt', "Done");
?>

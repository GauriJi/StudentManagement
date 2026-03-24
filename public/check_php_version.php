<?php
echo "Current PHP Version (Web Server): " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    echo "CRITICAL: Your PHP version is below 7.4.0. Arrow functions ('fn') are not supported.\n";
    echo "Please upgrade your XAMPP/PHP version to at least 7.4 (preferably 8.1 or 8.2).\n";
} else {
    echo "PHP version is compliant (7.4+). If you still see the error, please check if multiple PHP versions are installed.\n";
}

<?php
$output = shell_exec("php artisan migrate --force 2>&1");
file_put_contents(public_path('migrate_output.txt'), $output);
return "DONE";

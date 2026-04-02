<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";
$users = \App\User::whereIn('email', ['pradeep@gmail.com', 'pooja@gmail.com'])->get();
foreach ($users as $u) {
    $output .= "USER: " . $u->email . " | TYPE: " . $u->user_type . " | ID: " . $u->id . "\n";
}
file_put_contents('user_check_result.txt', $output);
echo "DONE\n";

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\User::where('email', 'pradeep@gmail.com')->first();
if ($user) {
    echo "USER: pradeep@gmail.com | TYPE: " . $user->user_type . "\n";
} else {
    echo "USER: pradeep@gmail.com NOT FOUND\n";
}

$user = \App\User::where('email', 'pooja@gmail.com')->first();
if ($user) {
    echo "USER: pooja@gmail.com | TYPE: " . $user->user_type . "\n";
} else {
    echo "USER: pooja@gmail.com NOT FOUND\n";
}

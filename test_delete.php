<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\User::where('email', 'testdel@test.com')->first();
if(!$user) {
    $user = App\User::create([
        'name' => 'testdel',
        'email' => 'testdel@test.com',
        'user_type' => 'student',
        'password' => bcrypt('sec'),
        'code' => '123'
    ]);
    echo "Created user.\n";
}

try {
    $user->delete();
    echo "Successfully deleted user.\n";
} catch (\Exception $e) {
    echo "Error deleting: " . $e->getMessage() . "\n";
}

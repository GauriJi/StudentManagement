<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "ADDING teacher_id column to my_classes...\n";

try {
    Schema::table('my_classes', function (Blueprint $table) {
        if (!Schema::hasColumn('my_classes', 'teacher_id')) {
            $table->unsignedInteger('teacher_id')->nullable()->after('class_type_id');
            //$table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
            echo "COLUMN ADDED SUCCESSFULY\n";
        } else {
            echo "COLUMN ALREADY EXISTS\n";
        }
    });
} catch (\Exception $e) {
    echo "ERROR ADDING COLUMN: " . $e->getMessage() . "\n";
}

try {
    // Attempt to add FK separately
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE my_classes ADD CONSTRAINT my_classes_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL');
    echo "FOREIGN KEY ADDED SUCCESSFULY\n";
} catch (\Exception $e) {
    echo "ERROR ADDING FK: " . $e->getMessage() . "\n";
}

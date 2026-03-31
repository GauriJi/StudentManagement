<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixStudentNotificationsTypeEnum extends Migration
{
    public function up()
    {
        // MySQL: Change enum to a string so 'admin' and other types work freely
        DB::statement("ALTER TABLE student_notifications MODIFY COLUMN type VARCHAR(50) DEFAULT 'general'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE student_notifications MODIFY COLUMN type ENUM('homework','exam','message','assignment','event','general') DEFAULT 'general'");
    }
}

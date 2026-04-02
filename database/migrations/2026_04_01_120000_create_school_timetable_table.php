<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolTimetableTable extends Migration
{
    public function up()
    {
        Schema::create('school_timetable', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable(); // users.id
            $table->string('day', 20);        // Monday … Friday
            $table->tinyInteger('period_no'); // 1-8
            $table->string('time_from', 10)->nullable(); // e.g. 09:00
            $table->string('time_to', 10)->nullable();   // e.g. 09:45
            $table->string('session', 20);   // e.g. 2025-2026
            $table->timestamps();

            $table->unique(['my_class_id','section_id','day','period_no','session'], 'tt_slot_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_timetable');
    }
}

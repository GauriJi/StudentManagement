<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoubtsTable extends Migration
{
    public function up()
    {
        Schema::create('doubts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('student_id'); // Who asked
            $table->unsignedInteger('teacher_id'); // Who is being asked
            $table->unsignedInteger('subject_id')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doubts');
    }
}

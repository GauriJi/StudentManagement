<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('general'); // chat, doubt, assignment, general
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('sender_id')->nullable(); // Admin or Student
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_notifications');
    }
}

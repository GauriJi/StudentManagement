<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicCalendarsTable extends Migration
{
    public function up()
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['holiday', 'event', 'exam', 'notice'])->default('event');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // custom badge color
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_calendars');
    }
}

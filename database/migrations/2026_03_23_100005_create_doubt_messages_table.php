<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoubtMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('doubt_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doubt_id');
            $table->unsignedInteger('user_id'); // Can be teacher, student, or parent
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doubt_messages');
    }
}

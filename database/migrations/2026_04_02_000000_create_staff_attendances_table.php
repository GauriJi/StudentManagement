<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->date('date');
            $table->string('status', 20)->default('present'); // present, absent, late, half_day
            $table->text('notes')->nullable();
            $table->timestamps();

            // Setup foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['user_id', 'date']); // Prevent duplicate records per user per day
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_attendances');
    }
}

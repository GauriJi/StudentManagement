<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleColumnsToUsersAndStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('yearly_income')->nullable();
            $table->string('relationship_to_student')->nullable();
        });
        
        Schema::table('staff_records', function (Blueprint $table) {
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['father_name', 'mother_name', 'father_occupation', 'yearly_income', 'relationship_to_student']);
        });
        
        Schema::table('staff_records', function (Blueprint $table) {
            $table->dropColumn(['qualification', 'specialization']);
        });
    }
}

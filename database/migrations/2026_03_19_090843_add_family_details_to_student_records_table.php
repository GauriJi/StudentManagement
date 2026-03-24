<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFamilyDetailsToStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->string('father_name', 100)->nullable()->after('birth_certificate');
            $table->string('mother_name', 100)->nullable()->after('father_name');
            $table->string('father_occupation', 100)->nullable()->after('mother_name');
            $table->decimal('yearly_income', 12, 2)->nullable()->after('father_occupation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->dropColumn(['father_name', 'mother_name', 'father_occupation', 'yearly_income']);
        });
    }
}

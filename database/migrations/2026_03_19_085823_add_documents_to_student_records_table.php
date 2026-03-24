<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentsToStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->string('aadhar_card')->nullable()->after('age');
            $table->string('prev_marksheet')->nullable()->after('aadhar_card');
            $table->string('birth_certificate')->nullable()->after('prev_marksheet');
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
            $table->dropColumn(['aadhar_card', 'prev_marksheet', 'birth_certificate']);
        });
    }
}

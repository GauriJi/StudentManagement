<?php
namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('my_classes')->delete();
        $ct = ClassType::pluck('id')->all();

        $data = [
            ['name' => 'Nursery', 'class_type_id' => $ct[0]],
            ['name' => 'LKG',     'class_type_id' => $ct[0]],
            ['name' => 'UKG',     'class_type_id' => $ct[0]],
            ['name' => 'Class 1', 'class_type_id' => $ct[1]],
            ['name' => 'Class 2', 'class_type_id' => $ct[1]],
            ['name' => 'Class 3', 'class_type_id' => $ct[1]],
            ['name' => 'Class 4', 'class_type_id' => $ct[1]],
            ['name' => 'Class 5', 'class_type_id' => $ct[1]],
            ['name' => 'Class 6', 'class_type_id' => $ct[2]],
            ['name' => 'Class 7', 'class_type_id' => $ct[2]],
            ['name' => 'Class 8', 'class_type_id' => $ct[2]],
            ['name' => 'Class 9', 'class_type_id' => $ct[3]],
            ['name' => 'Class 10', 'class_type_id' => $ct[3]],
            ['name' => 'Class 11', 'class_type_id' => $ct[3]],
            ['name' => 'Class 12', 'class_type_id' => $ct[3]],
            ];

        DB::table('my_classes')->insert($data);

    }
}

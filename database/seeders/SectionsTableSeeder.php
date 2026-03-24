<?php
namespace Database\Seeders;

use App\Models\MyClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->delete();
        $c = MyClass::pluck('id')->all();

        $data = [];
        foreach ($c as $class_id) {
            $data[] = ['name' => 'A', 'my_class_id' => $class_id, 'active' => 1];
        }

        DB::table('sections')->insert($data);
    }
}

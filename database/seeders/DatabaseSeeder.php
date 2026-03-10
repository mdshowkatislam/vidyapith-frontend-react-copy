<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use File;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $institutes_json = File::get("database/seeders/seed-data/institutes.json");
        $institutes = json_decode($institutes_json, true);
        // DB::table('institutes')->delete();
        // DB::table('institutes')->insert($institutes);
        $chunks = array_chunk($institutes, 1000);
        DB::table('institutes')->delete();
        foreach ($chunks as $chunk) {
            DB::table('institutes')->insert($chunk);
        }

        $teachers_json = File::get("database/seeders/seed-data/teachers.json");
        $teachers = json_decode($teachers_json, true);
        // DB::table('teachers')->delete();
        // DB::table('teachers')->insert($teachers);

        $chunksTeachers = array_chunk($teachers, 1000);
        DB::table('teachers')->delete();
        foreach ($chunksTeachers as $chunk) {
            DB::table('teachers')->insert($chunk);
        }


        $designationJson = File::get("database/seeders/seed-data/designation.json");
        $designation = json_decode($designationJson, true);
        DB::table('designations')->delete();
        DB::table('designations')->insert($designation);
    }
}

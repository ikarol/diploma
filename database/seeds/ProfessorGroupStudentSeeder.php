<?php

use Illuminate\Database\Seeder;

class ProfessorGroupStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GroupsTableSeeder::class);
        $this->call(ProfessorsTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
    }
}

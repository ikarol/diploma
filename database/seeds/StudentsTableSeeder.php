<?php

use App\Models\Group;
use App\Models\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = Group::all();
        try {
            User::create([
                'name' => str_random(10),
                'surname' => str_random(10),
                'email' => str_random(5) . '@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now()->format('Y-d-m'),
            ])->student()->save(new Student([
                'group_id' => $groups->random()->id,
            ]));
        } catch (\Illuminate\Database\QueryException $e) {
            User::create([
                'name' => str_random(10),
                'surname' => str_random(10),
                'email' => str_random(5) . '@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now()->format('Y-m-d'),
            ])->student()->save(new Student([
                'group_id' =>  $groups->random()->id,
            ]));
        }
    }
}

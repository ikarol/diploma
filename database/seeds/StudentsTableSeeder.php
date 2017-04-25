<?php

use App\Models\Group;
use App\Models\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
        User::create([
            'name' => str_random(10),
            'surname' => str_random(10),
            'email' => str_random(1).'@gmail.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now()->format('Y-d-m'),
        ])->student()->save(new Student([
            'group_id' => mt_rand($groups->first()->id, $groups->last()->id),
        ]));
    }
}

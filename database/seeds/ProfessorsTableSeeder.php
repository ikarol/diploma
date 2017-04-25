<?php

use App\Models\Professor;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProfessorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => str_random(10),
            'surname' => str_random(10),
            'email' => str_random(5).'@gmail.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now()->format('Y-d-m'),
        ])->professor()->save(new Professor([
            'occupation' => str_random(10),
            'degree' => str_random(10),
        ]));
    }
}

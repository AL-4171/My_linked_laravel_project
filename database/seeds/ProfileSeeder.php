<?php

use Illuminate\Database\Seeder;
use App\Profile;
use App\User;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function ($user) {
            factory(Profile::class)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (!User::query()
            ->count())
            User::factory()
                ->count(50)
                ->create();
        else
            $this->command->warn('users has already been created');
    }
}

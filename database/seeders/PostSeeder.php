<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        if (!Post::query()
            ->count())
            Post::factory()
                ->count(100)
                ->create();
        else
            $this->command->warn('posts has already been created');
    }
}

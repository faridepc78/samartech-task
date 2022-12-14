<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::query()
                ->inRandomOrder()
                ->limit(1)
                ->pluck('id')[0],
            'name' => fake()->unique()->name(),
            'slug' => fake()->unique()->slug(),
            'summary' => fake()->text(2000),
            'status' => Post::ACTIVE
        ];
    }
}

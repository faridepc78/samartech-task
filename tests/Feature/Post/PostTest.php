<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_see_all_posts()
    {
        $this->makeDataTables();

        $this->getJson(route('api.v1.dev.posts.index'))
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'code',
                'data' =>
                    [
                        [
                            'id',
                            'name',
                            'slug',
                            'summary',
                            'status',
                            'created_at',
                            'updated_at',
                            'user' => [
                                'id',
                                'name',
                                'email',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ],
                'status'
            ]);
    }

    public function test_user_can_see_post_by_id()
    {
        $this->makeDataTables();

        $post = Post::query()
            ->find(1);

        $this->getJson(route('api.v1.dev.posts.show', $post->id))
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'code',
                'data' =>
                    [
                        'id',
                        'name',
                        'slug',
                        'summary',
                        'status',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                'status'
            ]);
    }

    public function test_user_can_not_see_post_by_invalid_id()
    {
        $this->makeDataTables();

        $this->getJson(route('api.v1.dev.posts.show', 500))
            ->assertStatus(404);
    }

    public function test_user_can_not_create_post_because_user_id_is_not_valid()
    {
        $this->postJson(route('api.v1.dev.posts.store'), [
            'user_id' => 50,
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => Post::ACTIVE
        ])
            ->assertStatus(422);
    }

    public function test_user_can_not_create_post_because_status_is_not_valid()
    {
        $user = $this->createUser();

        $this->postJson(route('api.v1.dev.posts.store'), [
            'user_id' => $user->id,
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => 'test'
        ])
            ->assertStatus(422);
    }

    public function test_user_can_create_post()
    {
        $user = $this->createUser();

        $this->createPost();

        $this->postJson(route('api.v1.dev.posts.store'), [
            'user_id' => $user->id,
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => Post::ACTIVE
        ])
            ->assertSuccessful()
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'code',
                'data' =>
                    [
                        'id',
                        'name',
                        'slug',
                        'summary',
                        'status',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                'status'
            ]);

        $this->assertEquals(2, Post::count());
    }

    public function test_user_can_not_update_post_because_idd_is_not_valid()
    {
        $this->putJson(route('api.v1.dev.posts.update', 1), [
            'user_id' => 1,
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => Post::ACTIVE
        ])->assertStatus(404);
    }

    public function test_user_can_not_update_post_because_user_id_is_not_valid()
    {
        $post = $this->createPost();

        $this->putJson(route('api.v1.dev.posts.update', $post->id), [
            'user_id' => 100,
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => Post::ACTIVE
        ])->assertStatus(422);
    }

    public function test_user_can_not_update_post_because_status_is_not_valid()
    {
        $user = $this->createUser();

        $post = $this->createPost();

        $this->putJson(route('api.v1.dev.posts.update', $post->id), [
            'user_id' => $user->id,
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => 'pending'
        ])->assertStatus(422);
    }

    public function test_user_can_update_post()
    {
        $user = $this->createUser();

        $post = $this->createPost();

        $this->putJson(route('api.v1.dev.posts.update', $post->id), [
            'user_id' => $user->id,
            'name' => 'laravel',
            'slug' => $this->faker->unique()->slug(),
            'summary' => $this->faker->text(1000),
            'status' => Post::ACTIVE
        ])->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'code',
                'data' =>
                    [
                        'id',
                        'name',
                        'slug',
                        'summary',
                        'status',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                'status'
            ]);

        $this->assertEquals(1, Post::whereName('laravel')->count());
    }

    public function test_user_can_not_delete_post_by_invalid_id()
    {
        $this->deleteJson(route('api.v1.dev.posts.destroy', 100))
            ->assertStatus(404);
    }

    public function test_user_can_delete_post()
    {
        $post = $this->createPost();

        $this->deleteJson(route('api.v1.dev.posts.destroy', $post->id))
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'code',
                'data'
            ]);

        $this->assertCount(0, Post::all());
    }

    private function makeDataTables()
    {
        $this->seed([
            UserSeeder::class,
            PostSeeder::class
        ]);
    }

    private function createUser()
    {
        return User::factory()->create();
    }

    private function createPost()
    {
        $this->createUser();

        return Post::factory()->create();
    }
}

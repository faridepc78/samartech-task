<?php

namespace App\Http\Controllers\Api\V1\Dev;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dev\Post\CreatePostRequest;
use App\Http\Requests\Api\V1\Dev\Post\UpdatePostRequest;
use App\Http\Resources\Api\V1\Dev\Post\PostPaginateResource;
use App\Http\Resources\Api\V1\Dev\Post\PostResource;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(): PostPaginateResource
    {
        $posts = $this->postRepository->paginate(10);

        return new PostPaginateResource($posts);
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $state = $this->postRepository->create($request->validated());

        return $this->success_response(new PostResource($state),
            'post has been successfully created', 201);
    }

    public function show(Post $post): JsonResponse
    {
        return $this->success_response(new PostResource($post),
            'post information');
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $this->postRepository->update(array_filter($request->validationData()),
            $post->id);

        return $this->success_response(new PostResource($post->refresh()),
            'post has been successfully updated');
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->postRepository->delete($post->id);

        return $this->success_response(null,
            'post has been successfully deleted');
    }
}

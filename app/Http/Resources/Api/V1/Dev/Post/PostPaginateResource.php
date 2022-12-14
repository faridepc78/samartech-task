<?php

namespace App\Http\Resources\Api\V1\Dev\Post;

use App\Http\Resources\Api\V1\Dev\User\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostPaginateResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'status' => 'Success',
            'message' => 'list of posts by paginate',
            'code' => 200,
            'data' => $this->collection->map(function ($item) {

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'summary' => $item->summary,
                    'status' => $item->status,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                    'user' => new UserResource($item->user)
                ];
            })
        ];
    }
}

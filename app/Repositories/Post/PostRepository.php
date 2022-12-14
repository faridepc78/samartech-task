<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\Contract\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public Model $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }
}

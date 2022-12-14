<?php

namespace App\Traits;

use App\Http\Requests\Api\V1\Dev\Post\CreatePostRequest;
use App\Http\Requests\Api\V1\Dev\Post\UpdatePostRequest;

trait CheckPostSlug
{
    protected function prepareForValidation(): CreatePostRequest|UpdatePostRequest
    {
        return $this->merge([
            'slug' => make_slug(request()->input('slug'))
        ]);
    }
}

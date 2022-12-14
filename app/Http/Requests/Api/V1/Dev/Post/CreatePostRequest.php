<?php

namespace App\Http\Requests\Api\V1\Dev\Post;

use App\Models\Post;
use App\Traits\CheckPostSlug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePostRequest extends FormRequest
{
    use CheckPostSlug;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:200', 'unique:posts,name'],
            'slug' => ['required', 'string', 'max:200', 'unique:posts,slug'],
            'summary' => ['required', 'string', 'min:50', 'max:20000'],
            'status' => ['required', Rule::in(Post::$statuses)]
        ];
    }
}

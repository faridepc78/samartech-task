<?php

namespace App\Http\Requests\Api\V1\Dev\Post;

use App\Models\Post;
use App\Traits\CheckPostSlug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    use CheckPostSlug;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = request()->post->id;

        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:200', 'unique:posts,name,' . $id],
            'slug' => ['nullable', 'string', 'max:200', 'unique:posts,slug,' . $id],
            'summary' => ['nullable', 'string', 'min:50', 'max:20000'],
            'status' => ['nullable', Rule::in(Post::$statuses)]
        ];
    }
}

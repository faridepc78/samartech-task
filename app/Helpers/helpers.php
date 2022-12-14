<?php

use Illuminate\Support\Str;

if (!function_exists('make_slug')) {
    function make_slug($value): string
    {
        return Str::slug($value);
    }
}

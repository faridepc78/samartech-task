<?php

namespace App\Repositories\Contract;

interface BaseRepositoryInterface
{
    public function paginate($per_page);

    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function show($id, bool $fail = false, $withTrashed = false);

    public function firstByColumn($column, $value, bool $fail = false, $withTrashed = false);
}

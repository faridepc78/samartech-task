<?php

namespace App\Repositories\Contract;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    // model property on class instances
    protected Model $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model by paginate
    public function paginate($per_page): LengthAwarePaginator
    {
        return $this->model::query()
            ->paginate($per_page);
    }

    // Get all instances of model
    public function all(): Collection
    {
        return $this->model::all();
    }

    // create a new record in the database
    public function create(array $data): Model|Builder
    {
        return $this->model::query()
            ->create($data);
    }

    // update record in the database
    public function update(array $data, $id): bool|int
    {
        $record = $this->show($id);
        return $record->update($data);
    }

    // remove record from the database
    public function delete($id): int
    {
        return $this->model->destroy($id);
    }

    // show the record with the given id
    public function show($id, bool $fail = false, $withTrashed = false): Model|Collection|Builder|array|null
    {
        if ($fail == true) {
            if ($withTrashed == true)
                return $this->model::query()
                    ->withTrashed()
                    ->findOrFail($id);
            else
                return $this->model::query()
                    ->findOrFail($id);
        } else {
            if ($withTrashed == true)
                return $this->model::query()
                    ->withTrashed()
                    ->find($id);
            else
                return $this->model::query()
                    ->find($id);
        }
    }

    // first the record with the given column
    public function firstByColumn($column, $value, $fail = false, $withTrashed = false): Model|Builder|null
    {
        if ($fail == true) {
            if ($withTrashed == true)
                return $this->model::query()
                    ->withTrashed()
                    ->where($column, '=', $value)
                    ->firstOrFail();
            else
                return $this->model::query()
                    ->where($column, '=', $value)
                    ->firstOrFail();
        } else {
            if ($withTrashed == true)
                return $this->model::query()
                    ->withTrashed()
                    ->where($column, '=', $value)
                    ->first();
            else
                return $this->model::query()
                    ->where($column, '=', $value)
                    ->first();
        }
    }

    // Get the associated model
    public function getModel(): Model
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model): static
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations): Builder
    {
        return $this->model->with($relations);
    }
}

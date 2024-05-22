<?php

namespace App\Services;

use App\Contracts\TodoServiceContract;
use App\Models\Todo;
use Illuminate\Support\Collection;

class TodoService implements TodoServiceContract
{

    public function get(): Collection
    {
        return Todo::query()->get();
    }

    public function find(int $id): Todo
    {
        return Todo::query()->where('id', $id)->first();
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);
        return $todo;
    }

    public function create(array $data): Todo
    {
        return Todo::query()->create(array_merge($data, ['user_id' => auth()->user()->id]));
    }

    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }

}

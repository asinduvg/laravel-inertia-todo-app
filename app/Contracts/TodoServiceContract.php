<?php

namespace App\Contracts;

use App\Models\Todo;
use Illuminate\Support\Collection;

interface TodoServiceContract
{
    public function get() : Collection;

    public function find(int $id): ?Todo;

    public function update(Todo $todo, array $data) : Todo;

    public function create(array $data) : Todo;

    public function delete(Todo $todo) : bool;
}

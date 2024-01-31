<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(auth()->user()->cannot('viewAny', Todo::class), 403);

        return inertia('Todo/Index', [
            'todos' => fn() => cache()
                ->get('todo-' . auth()->user()->id,
                    fn() => TodoResource::collection(Todo::query()->get()))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        abort_if(auth()->user()->cannot('create', Todo::class), 403);

        Todo::query()->create(array_merge($request->validated(), ['user_id' => auth()->user()->id]));

        cache()->forget('todo-' . auth()->user()->id);

        return redirect()->route('todo.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        abort_if(auth()->user()->cannot('update', $todo), 403);

        $todo->update($request->validated());

        cache()->forget('todo-' . auth()->user()->id);

        return redirect()->route('todo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        abort_if(auth()->user()->cannot('delete', $todo), 403);

        $todo->delete();

        cache()->forget('todo-' . auth()->user()->id);

        return redirect()->route('todo.index');
    }
}

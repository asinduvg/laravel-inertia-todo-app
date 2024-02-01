<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_todo(): void
    {
        $todoCount = 3;
        $user = User::factory()->create();
        Todo::factory($todoCount)->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/todo')
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Todo/Index')
                ->has('todos.data', $todoCount));
    }

    public function test_add_todo(): void
    {
        $user = User::factory()->create();

        $title = 'TodoTest';
        $this->actingAs($user)->post('/todo', ['title' => $title]);

        $this->actingAs($user)->get('/todo')
            ->assertInertia(fn ($page) => $page
                ->component('Todo/Index')
                ->has('todos.data', 1)
                ->where('todos.data.0.title', $title));
    }

    public function test_mark_todo_done(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
            'done' => false
        ]);

        $this->actingAs($user)->put('/todo/' . $todo->id, ['done' => true]);

        $this->actingAs($user)->get('/todo')
            ->assertInertia(fn ($page) => $page
                ->component('Todo/Index')
                ->has('todos.data', 1)
                ->where('todos.data.0.done', 1));
    }

    public function test_delete_todo(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
            'done' => false
        ]);

        $this->actingAs($user)->delete('/todo/' . $todo->id, ['done' => true]);

        $this->actingAs($user)->get('/todo')
            ->assertInertia(fn ($page) => $page
                ->component('Todo/Index')
                ->has('todos.data', 0));
    }

}

<?php

namespace Tests\Unit;

use App\Jobs\DeleteExpiredTodos;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteExpiredTodosTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_done_todos_are_deleted(): void
    {
        $count = 5;
        $user = User::factory()->create();
        $doneTodos = Todo::factory($count)->expired()->done()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($count, $doneTodos->count());

        DeleteExpiredTodos::dispatchSync();

        $this->assertEquals(0, Todo::query()->count());

    }

    public function test_expired_not_done_todos_are_not_deleted(): void
    {
        $count = 5;
        $user = User::factory()->create();
        $doneTodos = Todo::factory($count)->expired()->done()->create([
            'user_id' => $user->id,
        ]);

        $notDoneTodos = Todo::factory($count)->expired()->notDone()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($count, $doneTodos->count());
        $this->assertEquals($count, $notDoneTodos->count());

        DeleteExpiredTodos::dispatchSync();

        $this->assertEquals($count, Todo::query()->count());

    }
}

<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function task_has_an_assigned_user()
    {
        $task = factory('App\Task')->create();

        $this->assertInstanceOf('App\User', $task->assignedUser);
    }
}

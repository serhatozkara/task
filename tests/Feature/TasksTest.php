<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @test
     */
    public function authenticated_user_can_create_a_task()
    {
        $attributes = factory('App\Task')->raw();

        $this->post('/tasks', $attributes)->assertRedirect('login');
    }

    /**
     * @test
     */
    public function a_user_can_create_a_task()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'overview' => $this->faker->paragraph,
            'badge' => $this->faker->numberBetween(1,9),
            'assigned_user_id' => factory('App\User')->create()->id
        ];

        $this->post('/tasks', $attributes)->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', $attributes);

        $this->get('/tasks')->assertSee($attributes['title']);

    }

    /**
     * @test
     */
    public function a_task_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Task')->raw(['title' => '']);

        $this->post('/tasks', $attributes)->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_task_requires_a_overview()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Task')->raw(['overview' => '']);

        $this->post('/tasks', $attributes)->assertSessionHasErrors('overview');
    }

    /**
     * @test
     */
    public function a_user_can_view_a_task()
    {
        $this->withoutExceptionHandling();

        $task = factory('App\Task')->create();

        $this->get('/tasks/' . $task->id)
            ->assertSee($task->title)
            ->assertSee($task->overview);
    }
}

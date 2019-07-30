<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Task;
use App\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{

  use RefreshDatabase;

  /** @test */
public function aTaskBelongsToProject(){

  $task = factory(Task::class)->create();

  $this->assertInstanceOf(Project::class, $task->project);
}


  /** @test */
  public function itHasAPath(){

      $task = factory(Task::class)->create();

      $this->assertEquals('/projects/' . $task->project->id . '/tasks/' .$task->id, $task->path());


  }

  /** @test */
  public function itCanBeCompleted(){

      $task = factory(Task::class)->create();
      $this->assertFalse($task->completed);
      $task->complete();
      $this->assertTrue($task->fresh()->completed);

  }

  /** @test */
  public function itCanBeIncompleted(){

      $task = factory(Task::class)->create(['completed'=>true]);
      $this->assertTrue($task->completed);
      $task->incomplete();
      $this->assertFalse($task->fresh()->completed);

  }
}

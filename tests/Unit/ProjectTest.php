<?php

namespace Tests\Unit;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function itHasAPath(){

    $project = factory('App\Project')->create();

    $this->assertEquals('/projects/'. $project->id , $project->path());

  }

  /** @test */

  public function aProjectBelongsToUser(){

    $project = factory('App\Project')->create();

    $this->assertInstanceOf('App\User',$project->owner);

  }

  /** @test */

  public function itCanAddTask(){

    $project = factory('App\Project')->create();

     $task =$project->addTask('Task task');

    $this->assertCount(1,$project->tasks);

    $this->assertTrue($project->tasks->contains($task));
  }

    /** @test */

    public function itCanInviteUser(){

        $project = factory('App\Project')->create();
        $project->invite($user = factory(User::class)->create());
        $this->assertTrue($project->members->contains($user));

    }
}

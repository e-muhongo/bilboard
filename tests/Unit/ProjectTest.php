<?php

namespace Tests\Unit;

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
}

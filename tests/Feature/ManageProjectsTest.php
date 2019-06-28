<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{

  use WithFaker, RefreshDatabase;

  /** @test */
  public function guestsMayNotManageProjects(){

    $project = factory('App\Project')->create();

    $this->get('/projects')->assertRedirect('login');

    $this->get('/projects/create')->assertRedirect('login');

    $this->get($project->path())->assertRedirect('login');

    $this->post('/projects',$project->toArray())->assertRedirect('login');



  }



  /** @test */

  public function aUserCanCreateAProject()
  {

  $this->withoutExceptionHandling();

   $this->actingAs(factory('App\User')->create());

   $this->get('projects/create')->assertStatus(200);

    $attributes = [
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph
    ];

    $this->post('/projects', $attributes)->assertRedirect('/projects');

    $this->assertDatabaseHas('projects', $attributes);

    $this->get('/projects')->assertSee($attributes['title']);
  }


  /** @test */

  public function aProjectRequireTitle(){


    $this->actingAs(factory('App\User')->create());

    $attributes = factory('App\Project')->raw(['title' => '']);

    $this->post('/projects',$attributes)->assertSessionHasErrors('title');

  }

  /** @test */
  public function aProjectRequireDescription(){


    $this->actingAs(factory('App\User')->create());

    $attributes = factory('App\Project')->raw(['description' => '']);

    $this->post('/projects',$attributes)->assertSessionHasErrors('description');
  }

  /** @test */
  public function aUserCanViewTheirProject(){

      $this->be(factory('App\User')->create());

      $this->withoutExceptionHandling();

      $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

      $this->get($project->path())
          ->assertSee($project->title)
          ->assertSee($project->description);
  }

   /** @test */
  public function aUserCanotViewProjectOfOthers(){

    $this->be(factory('App\User')->create());

    $project = factory('App\Project')->create();

    $this->get($project->path())->assertStatus(403);


  }



}

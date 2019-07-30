<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;
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

    $this->get('/projects/edit')->assertRedirect('login');

    $this->get($project->path())->assertRedirect('login');

    $this->post('/projects',$project->toArray())->assertRedirect('login');
  }



    /** @test */
  public function aUserCanCreateAProject()
  {
    $this->signIn();
    $this->get('projects/create')->assertStatus(200);

    $this->followingRedirects()
        ->post('/projects',$attributes =  factory(Project::class)->raw())
          ->assertSee($attributes['title'])
          ->assertSee($attributes['description'])
          ->assertSee($attributes['notes']);
  }

    /** @test */
    public function taskCanBeIncludedAsNewProject(){
        $this->signIn();
        $attributes = factory(Project::class)->raw();
        $attributes['tasks'] = [
            ['body' => ' Task 1'],
            ['body' => ' Task 2']
        ];
        $this->post('/projects',$attributes);
        $this->assertCount(2,Project::first()->tasks);
    }
    /** @test */
    public function unauthorizedUsersCannotDeleteProject(){
        $project = ProjectFactory::create();
        $this->delete($project->path())
            ->assertRedirect('/login');
        $user =$this->signIn();
        $this->delete($project->path())->assertStatus(403);
        $project->invite($user);
        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

  /** @test */
  public function userCanDeleteProject(){
      $project = ProjectFactory::create();
      $this->actingAs($project->owner)
          ->delete($project->path())
          ->assertRedirect('/projects');
      $this->assertDatabaseMissing('projects',$project->only('id'));

  }

  /** @test */
  public function aProjectRequireTitle(){
    $this->signIn();
    $attributes = factory('App\Project')->raw(['title' => '']);
    $this->post('/projects',$attributes)->assertSessionHasErrors('title');
  }

  /** @test */
  public function aProjectRequireDescription(){
        $this->signIn();
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects',$attributes)->assertSessionHasErrors('description');
  }

  /** @test */
  public function aUserCanViewTheirProject(){
      $this->signIn();
      $project = ProjectFactory::create();
      $this->actingAs($project->owner)
          ->get($project->path())
          ->assertSee($project->title)
          ->assertSee($project->description);
  }

    /** @test */
    public function aUserCanViewProjectsThatTheyAreInvitedOnTheirDashboard(){

        $project = tap(ProjectFactory::create())->invite($this->signIn());

        $this->get('/projects')->assertSee($project->title);
    }

   /** @test */
  public function aUserCanotViewProjectOfOthers(){

    $this->signIn();

    $project = factory('App\Project')->create();

    $this->get($project->path())->assertStatus(403);
  }


     /** @test */
     public function userCanUpdateProject(){

      $project = ProjectFactory::create();

       $this->actingAs($project->owner)
          ->patch($project->path(),$attributes =['title' => 'changed', 'description' => 'changed','notes' => 'changed'])
          ->assertRedirect($project->path());

      $this->get($project->path().'/edit')->assertOk();

      $this->assertDatabaseHas('projects',$attributes);

     }


     /** @test */
     public function userCanUpdateProjectGeneralNotes(){


      $project = ProjectFactory::create();

       $this->actingAs($project->owner)
          ->patch($project->path(),$attributes =['notes' => 'changed'])
          ->assertRedirect($project->path());

      $this->get($project->path().'/edit')->assertOk();

       $this->assertDatabaseHas('projects',$attributes);

     }


     /** @test */
    public function aUserCanotUpdateProjectOfOthers(){

      $this->signIn();

      $project = factory('App\Project')->create();

      $this->patch($project->path())->assertStatus(403);
    }




}

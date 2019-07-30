<?php

namespace Tests\Feature;


use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
      use RefreshDatabase;

    /** @test */

    public function projectCanHaveTaks(){
      $project = ProjectFactory::create();
      $this->actingAs($project->owner)
        ->post($project->path() . '/tasks', ['body' =>'Test task']);

      $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function guestCantAddTasksToProject(){

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');

    }


    /** @test */

    public function onlyTheOwnerCanAddTaskToProject(){

      $this->signIn();

      $project = factory('App\Project')->create();

      $this->post($project->path() . '/tasks', ['body' =>'Test task'])
          ->assertStatus(403);

      $this->assertDatabaseMissing('tasks', ['body'=>'Test task']);



    }


    /** @test */

    public function aTaskRequireBody(){

      $project = ProjectFactory::create();

      $attributes = factory('App\Task')->raw(['body' => '']);

      $this->actingAs($project->owner)
          ->post($project->path(). '/tasks',$attributes)->assertSessionHasErrors('body');

    }


    /** @test */
    public function taskCanBeUpdated(){

      $project = ProjectFactory::withTasks(1)->create();

      $this->actingAs($project->owner)
          ->patch($project->tasks[0]->path(), [

          'body' => 'changed',
        ]);

      $this->assertDatabaseHas('tasks',[

        'body' =>'changed',
      ]);

    }

    /** @test */
    public function taskCanBeMarkedCompleted(){

      $project = ProjectFactory::withTasks(1)->create();

      $this->actingAs($project->owner)
          ->patch($project->tasks[0]->path(), [
          'body' => 'changed',
          'completed' => true

        ]);

      $this->assertDatabaseHas('tasks',[
        'body' =>'changed',
        'completed' => true

      ]);

    }

    /** @test */
    public function taskCanBeMarkedIncompleted(){

      $this->withoutExceptionHandling();

      $project = ProjectFactory::withTasks(1)->create();

      $this->actingAs($project->owner)
          ->patch($project->tasks[0]->path(), [
          'body' => 'changed',
          'completed' => true

        ]);

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false

          ]);

      $this->assertDatabaseHas('tasks',[
        'body' =>'changed',
        'completed' => false

      ]);

    }

    /** @test */

    public function onlyOwnerOfProjectCanUpdateTask(){

      $this->signIn();

      $project = ProjectFactory::withTasks(1)->create();


      $this->patch($project->tasks[0]->path(), ['body' =>'changed'])
          ->assertStatus(403);

      $this->assertDatabaseMissing('tasks', ['body'=>'changed']);

    }

}

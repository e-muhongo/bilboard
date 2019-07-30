<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class TriggerActivityTest extends TestCase
{

   use WithFaker , RefreshDatabase;

  /** @test */
  public function createProject(){

        $project = ProjectFactory::create();

        $this->assertCount(1,$project->activity);


        tap($project->activity->last(),function($activity) {

          $this->assertEquals('created_project',$activity->description);

          $this->assertNull($activity->changes);
        });
  }

  /** @test */
  public function updatingProject(){

        $project = ProjectFactory::create();
        $originalTitle = $project->title ;

        $project->update(['title' =>'Changed']);

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity) use ($originalTitle  ) {

          $this->assertEquals('updated_project',$activity->description);

          $expected = [
            'before' => ['title'=>$originalTitle],

            'after' => ['title' => 'Changed']
          ];

          $this->assertEquals($expected, $activity->changes);
        });
  }

  /** @test */
  public function creatingNewTaskForProject(){

        $project = ProjectFactory::create();

        $project->addTask('Some task');

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity){
          $this->assertEquals('created_task',$activity->description);
          $this->assertInstanceOf(Task::class, $activity->subject);
          $this->assertEquals('Some task',$activity->subject->body);
        });

  }

  /** @test */
  public function completingTaskForProject(){

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
          'body'=>'foobar',
          'completed' => true
        ]);

        $this->assertCount(3,$project->activity);

        tap($project->activity->last(),function($activity){

          $this->assertEquals('completed_task',$activity->description);

          $this->assertInstanceOf(Task::class, $activity->subject);

        });

  }

  /** @test */
  public function incompletingTaskForProject(){

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
          'body'=>'foobar',
          'completed' => true
        ]);

        $this->assertCount(3,$project->activity);

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
          'body'=>'foobar',
          'completed' => false
        ]);

        $project->refresh();

        $this->assertCount(4,$project->activity);

        tap($project->activity->last(),function($activity){

          $this->assertEquals('incompleted_task',$activity->description);

          $this->assertInstanceOf(Task::class, $activity->subject);

        });


  }

  /** @test */
  public function deletingTaskForProject(){

    $project = ProjectFactory::withTasks(1)->create();

    $project->tasks[0]->delete();

    $this->assertCount(3,$project->activity);


  }

}

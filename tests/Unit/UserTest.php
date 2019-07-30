<?php

namespace Tests\Unit;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

  use RefreshDatabase;
  /** @test */
  public function has_project(){

      $user = factory('App\User')->create();

      $this->assertInstanceOf(Collection::class, $user->projects);
  }

    /** @test */
    public function hasAccessibleProject(){
        $john = $this->signIn();
        ProjectFactory::ownedBy($john)->create();
        $this->assertCount(1,$john->accessibleProjects());

        $saly = factory(\App\User::class)->create();
        ProjectFactory::ownedBy($saly)->create()->invite($john);
        $this->assertCount(2,$john->accessibleProjects());


    }
  
}

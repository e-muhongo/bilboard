<?php

namespace Tests\Feature;
use App\User;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function nonOwnersMayNotInvite(){
        $project = ProjectFactory::create();
        $user = factory(User::class)->create();

        $assertInvitationForbidden = function() use($user,$project){
            $this->actingAs($user)
                ->post($project->path().'/invitations')
                ->assertStatus(403);
        };
        $assertInvitationForbidden();
        $project->invite($user);
        $assertInvitationForbidden();

    }

    /** @test */
    public function projectOwnerCanInviteUser(){
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $userToInvite = factory(User::class)->create();
        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
            'email' => $userToInvite->email
        ])
            ->assertRedirect($project->path());
        $this->assertTrue($project->members->contains($userToInvite));

    }

    /** @test */
    public function theInvitedUserMustHaveAnAccount(){
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
            'email' =>'notauser@example.com'
        ])
        ->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a BirdBoard account.'
        ], null, 'invitations');


    }

    /** @test */
    public function invitedUsersMayUpdateProjectDetails(){
        $project = ProjectFactory::create();
        $project->invite($newUser = factory(User::class)->create());
        $this->signIn($newUser);
        $this->post(action('ProjectTasksController@store',$project),$task =['body' => 'Foo task']);
        $this->assertDatabaseHas('tasks',$task);
    }
}


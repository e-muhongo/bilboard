<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Http\Requests\UpdateProjectRequest;
class ProjectsController extends Controller
{
    //

  public function index(){

    $projects = auth()->user()->accessibleProjects();
    return view('projects.index',compact('projects'));

  }

  public function create(){

    return view('projects.create');

  }

    /**
     * Persist a new project
     *
     * @return mixed
     */

 public function store()  {

    $project = auth()->user()->projects()->create($this->validateRequest());

     if($tasks = request('tasks')){
         $project->addTasks($tasks);
     }

    if(request()->wantsJson()){
        return ['message' =>$project->path()];
    }

   return redirect($project->path());
 }


 public function show(Project $project){
   //
   // if (auth()->user()->isNot($project->owner)){
   //   abort(403);
   // }
    $this->authorize('update',$project);

    return view('projects.show', compact('project'));

  }

  public function edit(Project $project){

    return view('projects.edit', compact('project'));

  }

  public function update(UpdateProjectRequest $request,Project $project){

    $project->update($request->validated());

     return redirect($project->path());
   }

    public function destroy(Project $project){

        $this->authorize('manage',$project);

        $project->delete();
        return redirect('/projects');
    }


    protected function validateRequest(){
         return request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3'
         ]);
    }






}

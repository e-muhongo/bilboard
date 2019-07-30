@extends('layouts.app')

@section('content')

      <header class="flex items-center mb-6 pb-4 " >

          <div class="flex justify-between items-end w-full">
                <p class="text-muted font-light ">
                    <a href="/projects"
                       class="text-muted no-underline hover:underline">
                        My Projects</a> / {{ $project->title }}
                </p>

              <div class="flex items-center">
                      @foreach($project->members as $member)
                          <img src="{{gravatarUrl($member->email)}}" alt="{{$member->name}}'s avatar " class="rounded-full w-8 mr-2">
                      @endforeach
                          <img src="{{gravatarUrl($project->owner->email)}} " alt="{{$project->owner->name}}'s avatar " class="rounded-full w-8 mr-2">
                          <a class="button ml-4" href="{{ $project->path().'/edit' }}">Edit Project</a>
              </div>
          </div>

      </header>


      <main>

        <div class="lg:flex -mx-3 ">

            <div class="lg:w-3/4 px-3 mb-6">

                <div class="mb-8">
                    {{-- tasks --}}

                    <h2 class=" text-lg text-muted font-light mb-3 ">Tasks</h2>

                        @foreach ($project->tasks as $task)

                            <div class="card mb-3">

                                <form action="{{$task->path()}}" method="post">

                                  @method('PATCH')

                                  @csrf

                                    <div class="flex">

                                      <input type="text" name="body" value ="{{ $task->body }}" class="text-default bg-card w-full {{ $task->completed ? 'line-through text-muted' : '' }}" >

                                      <input type="checkbox" name="completed" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>

                                    </div>

                                </form>

                            </div>

                        @endforeach

                          <div class="card mb-3">

                            <form method="post" action="{{ $project->path(). '/tasks' }}">

                              @csrf

                              <input type="text" name="body" placeholder=" Add a new task..." class=" bg-card text-default w-full">

                            </form>

                          </div>
                </div>

                <div class="">
                    {{--  General Notes --}}

                    <h2 class=" text-lg text-default font-light mb-3 ">General Notes</h2>

                    <form class="" method="POST" action="{{ $project->path() }}">

                      @method('PATCH')
                      @csrf

                      <textarea class="card w-full mb-4 text-default" style="min-height:150px;" name="notes" placeholder="Write something important for your project here..." >{{ $project->notes }}</textarea>

                      <button type="submit" class="button">Save</button>

                    </form>

                    @include('projects.errors')

                </div>

            </div>

            <div class="lg:w-1/4 px-3 lg:py-8" >

                  @include('projects.card')
                  @include('projects.activity.card')

                @can('manage',$project)
                  @include('projects.invite')
                @endcan

            </div>


        </div>
      </main>





@endsection

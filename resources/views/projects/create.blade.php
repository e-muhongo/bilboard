@extends('layouts.app')

@section('content')

  <div class=" lg:w1/2 lg:mx-auto bg-card p-6 md:py-12 md:px-16 rounded shadow ">

    <h1 class="text-2xl font-normal mb-10 text-center">Create a project</h1>

    <form method="POST" action="/projects" >
        @csrf

        @include('projects.form',
        ['project' => new App\Project,
          'buttonText' => 'Create Project'])

    </form>

  </div>



@endsection

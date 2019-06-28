@extends('layouts.app')

@section('content')

      <div class="flex mb-3">

          <a href="/projects/create">New Project</a>

      </div>


      <div class="flex">
          @forelse ($projects as $project)
            <div class="bg-white mr-4 rounded shadow">

                <h3>{{ $project->title }}</h3>

                <div class="">
                  {{ $project->description }}
                </div>

            </div>
        @empty

            <div class="">
                  No projects yet
            </div>


        @endforelse

      </div>

@endsection

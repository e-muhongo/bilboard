@extends('layouts.app')

@section('content')

    <form method="POST" action="/projects">
        @csrf

      <h1 class="heading is-1">Create Project</h1>


      <div class="field">

          <label for="title">Title</label>

          <div class="control">
              <input type="text" name="title" id="title" class="input"placeholder="Enter the title of your project">
          </div>

      </div>


      <div class="field">

          <label for="description">Description</label>

          <div class="control">
              <textarea name="description" id="description" class="textarea" ></textarea>
          </div>

      </div>

       <div class="field">

          <div class="control">

              <button type="submit" class="button is-link">Create Project</button>
              <a href="/projects"> Cancel</a>

          </div>

       </div>

    </form>

@endsection

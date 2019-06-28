@extends('layouts.app')

@section('content')

    <h1>{{ $project->title }}</h1>

    <div class="">{{ $project->description }}</div>

    <a href="/projects">Go Back</a>

@endsection

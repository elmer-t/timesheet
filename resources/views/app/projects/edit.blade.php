@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <livewire:projects.create-edit :id="$project->id" />
@endsection

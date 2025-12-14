@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <livewire:users.create-edit :id="$user->id" />
@endsection

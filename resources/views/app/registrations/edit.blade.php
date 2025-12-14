@extends('layouts.app')

@section('title', 'Edit Time Registration')

@section('content')
    <livewire:time-registrations.create-edit :id="$registration->id" />
@endsection

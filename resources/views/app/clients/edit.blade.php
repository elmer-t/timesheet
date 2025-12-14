@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
    <livewire:clients.create-edit :id="$client->id" />
@endsection

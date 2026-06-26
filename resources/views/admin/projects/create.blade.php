@extends('admin.layouts.app')

@section('title', 'New Project')
@section('heading', 'New Project')
@section('subheading', 'Add a new card to the "Our Projects" section on the Fidya page.')

@section('content')
    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.projects._form')
    </form>
@endsection

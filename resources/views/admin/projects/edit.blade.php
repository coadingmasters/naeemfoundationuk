@extends('admin.layouts.app')

@section('title', 'Edit Project')
@section('heading', 'Edit Project')
@section('subheading', 'Update this card in the "Our Projects" section on the Fidya page.')

@section('content')
    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.projects._form')
    </form>
@endsection

@extends('admin.layouts.app')

@section('title', 'New Hero Slide')
@section('heading', 'New Hero Slide')
@section('subheading', 'Add a new slide to the homepage hero slider.')

@section('content')
    <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.hero-slides._form')
    </form>
@endsection

@extends('admin.layouts.app')

@section('title', 'Edit Hero Slide')
@section('heading', 'Edit Hero Slide')
@section('subheading', 'Update the slide shown in the homepage hero slider.')

@section('content')
    <form method="POST" action="{{ route('admin.hero-slides.update', $slide) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.hero-slides._form')
    </form>
@endsection

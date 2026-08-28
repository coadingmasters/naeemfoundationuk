@extends('admin.layouts.app')

@section('title', 'Set a Page Video')
@section('heading', 'Set a Page Video')
@section('subheading', 'Pick a Giving page and give it its own video. It replaces the built-in default straight away.')

@section('content')
    <form method="POST" action="{{ route('admin.page-videos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.page-videos._form', ['mode' => 'create'])
    </form>
@endsection

@extends('admin.layouts.app')

@section('title', 'New Community Video')
@section('heading', 'New Community Video')
@section('subheading', 'Add a video to the gallery on the Community Centre page.')

@section('content')
    <form method="POST" action="{{ route('admin.community-videos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.community-videos._form')
    </form>
@endsection

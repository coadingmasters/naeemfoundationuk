@extends('admin.layouts.app')

@section('title', 'Edit Community Video')
@section('heading', 'Edit Community Video')
@section('subheading', 'Update this video in the gallery on the Community Centre page.')

@section('content')
    <form method="POST" action="{{ route('admin.community-videos.update', $video) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.community-videos._form')
    </form>
@endsection

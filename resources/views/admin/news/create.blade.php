@extends('admin.layouts.app')
@section('title', 'New Story')
@section('heading', 'New Story')
@section('subheading', 'Publish a news item, press release or blog post to the News & Press page.')
@section('content')
    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.news._form')
    </form>
@endsection

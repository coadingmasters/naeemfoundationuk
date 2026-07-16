@extends('admin.layouts.app')
@section('title', 'Edit Story')
@section('heading', 'Edit Story')
@section('subheading', 'Update this story on the News & Press page.')
@section('content')
    <form method="POST" action="{{ route('admin.news.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.news._form')
    </form>
@endsection

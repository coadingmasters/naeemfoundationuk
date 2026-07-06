@extends('admin.layouts.app')

@section('title', 'Edit Hajj Video')
@section('heading', 'Edit Hajj Video')
@section('subheading', 'Update this video in the "Recent Hajj Reviews" gallery on the Hajj 2027 page.')

@section('content')
    <form method="POST" action="{{ route('admin.hajj-videos.update', $video) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.hajj-videos._form')
    </form>
@endsection

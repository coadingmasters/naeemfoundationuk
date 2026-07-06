@extends('admin.layouts.app')

@section('title', 'New Hajj Video')
@section('heading', 'New Hajj Video')
@section('subheading', 'Add a video to the "Recent Hajj Reviews" gallery on the Hajj 2027 page.')

@section('content')
    <form method="POST" action="{{ route('admin.hajj-videos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.hajj-videos._form')
    </form>
@endsection

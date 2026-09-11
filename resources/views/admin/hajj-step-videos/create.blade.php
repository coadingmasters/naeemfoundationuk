@extends('admin.layouts.app')

@section('title', 'New Hajj Step Video')
@section('heading', 'New Hajj Step Video')
@section('subheading', 'Add a video to the "Steps of Hajj" gallery on the Hajj 2027 page.')

@section('content')
    <form method="POST" action="{{ route('admin.hajj-step-videos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.hajj-step-videos._form')
    </form>
@endsection

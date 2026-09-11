@extends('admin.layouts.app')

@section('title', 'Edit Hajj Step Video')
@section('heading', 'Edit Hajj Step Video')
@section('subheading', 'Update this video in the "Steps of Hajj" gallery on the Hajj 2027 page.')

@section('content')
    <form method="POST" action="{{ route('admin.hajj-step-videos.update', $video) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.hajj-step-videos._form')
    </form>
@endsection

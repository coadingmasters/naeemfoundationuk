@extends('admin.layouts.app')

@section('title', 'Set a Hero Banner')
@section('heading', 'Set a Hero Banner')
@section('subheading', 'Pick a Giving page and upload a photo for its hero background. It replaces the built-in default straight away.')

@section('content')
    <form method="POST" action="{{ route('admin.page-heroes.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.page-heroes._form', ['mode' => 'create'])
    </form>
@endsection

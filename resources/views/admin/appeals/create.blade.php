@extends('admin.layouts.app')

@section('title', 'New Appeal')
@section('heading', 'New Appeal')
@section('subheading', 'Add a new card to the homepage "Latest Appeals" section.')

@section('content')
    <form method="POST" action="{{ route('admin.appeals.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.appeals._form')
    </form>
@endsection

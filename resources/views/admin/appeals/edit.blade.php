@extends('admin.layouts.app')

@section('title', 'Edit Appeal')
@section('heading', 'Edit Appeal')
@section('subheading', 'Update this card in the homepage "Latest Appeals" section.')

@section('content')
    <form method="POST" action="{{ route('admin.appeals.update', $appeal) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.appeals._form')
    </form>
@endsection

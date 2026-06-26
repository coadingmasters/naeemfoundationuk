@extends('admin.layouts.app')

@section('title', 'New Cause')
@section('heading', 'New Cause')
@section('subheading', 'Add a new card to the homepage donate carousel.')

@section('content')
    <form method="POST" action="{{ route('admin.causes.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.causes._form')
    </form>
@endsection

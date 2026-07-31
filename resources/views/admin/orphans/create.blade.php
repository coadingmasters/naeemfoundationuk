@extends('admin.layouts.app')

@section('title', 'New Orphan')
@section('heading', 'New Orphan')
@section('subheading', 'Add a child to the "Sponsor an Orphan" page.')

@section('content')
    <form method="POST" action="{{ route('admin.orphans.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.orphans._form')
    </form>
@endsection

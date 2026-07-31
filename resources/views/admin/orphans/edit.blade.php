@extends('admin.layouts.app')

@section('title', 'Edit Orphan')
@section('heading', 'Edit Orphan')
@section('subheading', 'Update this child on the "Sponsor an Orphan" page.')

@section('content')
    <form method="POST" action="{{ route('admin.orphans.update', $orphan) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.orphans._form')
    </form>
@endsection

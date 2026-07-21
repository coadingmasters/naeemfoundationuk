@extends('admin.layouts.app')

@section('title', 'New Product')
@section('heading', 'New Product')
@section('subheading', 'Add a new item to the shop.')

@section('content')
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
    </form>
@endsection

@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('heading', 'Edit Product')
@section('subheading', $product->name)

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products._form')
    </form>
@endsection

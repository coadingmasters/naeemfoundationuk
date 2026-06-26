@extends('admin.layouts.app')

@section('title', 'Edit Cause')
@section('heading', 'Edit Cause')
@section('subheading', 'Update this card in the homepage donate carousel.')

@section('content')
    <form method="POST" action="{{ route('admin.causes.update', $cause) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.causes._form')
    </form>
@endsection

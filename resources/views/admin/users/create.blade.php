@extends('admin.layouts.app')

@section('title', 'New Admin')
@section('heading', 'New Admin')
@section('subheading', 'Create a super admin or a region-scoped admin.')

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users._form')
    </form>
@endsection

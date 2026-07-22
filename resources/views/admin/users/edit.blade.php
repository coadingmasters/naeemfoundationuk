@extends('admin.layouts.app')

@section('title', 'Edit Admin')
@section('heading', 'Edit Admin')
@section('subheading', $user->name)

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users._form')
    </form>
@endsection

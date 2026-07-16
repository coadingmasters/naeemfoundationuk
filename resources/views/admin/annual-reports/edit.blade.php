@extends('admin.layouts.app')

@section('title', 'Edit Annual Report')
@section('heading', 'Edit Annual Report')
@section('subheading', 'Update this report or replace its PDF.')

@section('content')
    <form method="POST" action="{{ route('admin.annual-reports.update', $report) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.annual-reports._form')
    </form>
@endsection

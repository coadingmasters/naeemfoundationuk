@extends('admin.layouts.app')

@section('title', 'New Annual Report')
@section('heading', 'New Annual Report')
@section('subheading', 'Upload a report PDF — it appears instantly on the public Annual Reports page.')

@section('content')
    <form method="POST" action="{{ route('admin.annual-reports.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.annual-reports._form')
    </form>
@endsection

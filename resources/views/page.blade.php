@extends('adminlte::page')

@section('content')
    {{$dataTable->table()}}
@endsection

@push('script')
    {{$dataTable->scripts()}}
@endpush


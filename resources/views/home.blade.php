{{--
@extends('layouts.app')
--}}


{{--@section('content')
    <div class = "container">
        <div class = "row justify-content-center">
            <div class = "col-md-8">
                <div class = "card">
                    <div class = "card-header">{{ __('Dashboard') }}</div>

                    <div class = "card-body">
                        @if (session('status'))
                            <div class = "alert alert-success" role = "alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in  ^_^ ') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection--}}


{{--
@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    {{$dataTable->table()}}
@stop

@section('css')
    <link rel = "stylesheet" href = "/css/admin_custom.css">
@stop




@push('script')
    {{$dataTable->scripts()}}
@endpush
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
@stack('scripts')--}}

@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <p>Welcome to this beautiful admin panel.</p>

    {{--   {{$dataTable->table()}}--}}
@endsection

{{--
@push('datatable_script')
    {{$dataTable->scripts()}}
@endpush
--}}

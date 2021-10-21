@extends('adminlte::page')


@section('title', 'Admins')

@section('content')
    <div class = "container">
        @include('flash::message')
    </div>
    @if(Session::has('error'))
        <div class = "alert alert-danger text-center" role = "alert"
             style = "text-align: center; width: 50%;height:100%">
            <div style = "margin-top:-3px;margin-left:-15px">
                {{Session::get('error')}}
                <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <div class = "container pt-2">
        <table class = "table table-striped data-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!-- modal delete -->
        <div class = "modal fade" style = "margin-top: 200px" id = "modal-delete">
            <div class = "modal-dialog">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h4 class = "modal-title"><i class = "fas fa-trash text-danger"></i> Delete User</h4>
                        <button type = "button" class = "close border-danger" data-dismiss = "modal"
                                aria-label = "Close">×
                        </button>
                    </div>
                    <div class = "modal-body">

                        <p>Are you sure you want to delete this User</p>
                    </div>
                    <div class = "modal-footer justify-content-between">
                        <button type = "button" class = "btn btn-default" data-dismiss = "modal">Close</button>
                        <a href = "bb" class = "btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal  edit -->
        <div class = "modal fade" id = "modal-edit">
            <div class = "modal-dialog modal-lg">
                <div class = "modal-content">
                    <div class = "modal-header" style = "width:100%">
                        <h4 style = "margin-left:15%; text-align: center; color:#555555">Modifier les informations
                                                                                         personnelles
                                                                                         de patient </h4>
                        <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                            <span aria-hidden = "true">&times;</span>
                        </button>
                    </div>
                    <div class = "card-body">
                        <div style = "width: 98%; margin-left:1%; margin-top:1%">
                            <form method = "POST" action = "{{route('users.edit')}}"
                                  id = "formEdit" class = "form-g">
                                @csrf
                                <input hidden name = "id" id = "idUpdate">
                                {{-- <input id = "idUpdate" type = "hidden" name = "id">--}}

                                <div class = "form-c row">
                                    <label for = "role" class = "col-sm-4 col-form-label"
                                           style = "color:#555555">Role</label>
                                    <div class = "col-sm-8">
                                        <div>
                                            <select class = "form-control" name = "role" id = "roleUpdate">
                                                <option value = "1">User</option>
                                                <option value = "2">Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <button type = "submit" id = "submitButtonFormEdit"
                                        class = "btn btn-block bg-gradient-primary"
                                        style = "margin-left: 40%;margin-top:20px ; width: 120px;color: white">
                                    Save
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop

        @section('css')
            <link rel = "stylesheet" href = "/css/admin_custom.css">
        @stop
        @push('datatable_script')
            <script src = "{{ asset('js/admins.js') }}"></script>

    @endpush

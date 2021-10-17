@extends('adminlte::page')


@section('title', 'Admins')


{{--
@section('content')
    <div class="container">
        @include('flash::message')
    </div>
    {{$dataTable->table()}}
    <div><h1>Hello</h1></div>
    <button  class="dzf btn btn-danger btn-sm ml-2" data-id="'.$row -> id.'">dzdss</button>
    <button  class="dzf btn btn-danger btn-sm ml-2" data-id="'.$row -> id.'">Desasa</button>
    <button   class="dzf btn btn-danger btn-sm ml-2" data-id="'.$row -> id.'">sasadsa</button>
    <!-- modal -->
    <div class="modal fade" style="margin-top: 200px" id="modal-delete" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-trash text-danger"></i> Delete User</h4>
                    <button type="button" class="close border-danger" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">

                    <p>Are you sure you want to delete this User</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href="bb" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>


@stop

@section('css')
    <link rel = "stylesheet" href = "/css/admin_custom.css">
@stop

@push('datatable_script')
    {{$dataTable->scripts()}}
            <script>
                document.addEventListener("DOMContentLoaded", function(e) {
                    console.log("Hi there")
                    const deleteButtons = document.getElementsByClassName('delete');
                    console.log(deleteButtons);
                    const array  = Array.from(deleteButtons)
                    const testButtons = document.getElementsByClassName('dzf');
                    console.log(testButtons);
                    const testArray  = Array.from(testButtons)
                    // const array=  Array.prototype.slice.call(deleteButtons)
                    //const array  = [...deleteButtons]
                    console.log(array)
                    console.log(testArray)

                    array.forEach(element => {
                        element.addEventListener('click', event => {
                            const idValue = element.dataset.id;

                            console.log(idValue)
                            idUpdate.value=idValue;
                        })

                    })
                })
            </script>

@endpush
--}}
@section('content')

    <div class = "container pt-2">
        <table class = "table table-striped data-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!-- modal -->
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

        @stop

        @section('css')
            <link rel = "stylesheet" href = "/css/admin_custom.css">
        @stop
        @push('datatable_script')

            <script type = "text/javascript">
                $(function () {

                    let table = $('.data-table').DataTable({
                        processing: true,
                        serverSide: true,
                        autoWidth: false,
                        order: [["0", "desc"]],
                        ajax: "{{ route('users.index') }}",/*
            drawCallback: function( settings ) {
                //alert( 'DataTables has redrawn the table' );
            },*/
                        columns: [
                            {data: 'id', name: 'id'},
                            {data: 'username', name: 'username'},
                            {data: 'email', name: 'email'},
                            {data: 'role', name: 'role'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],

                    });
                    table.on('draw', function () {
                        console.log("Hi there")
                        const deleteButtons = document.getElementsByClassName('delete');
                        console.log(deleteButtons);
                        const array = Array.from(deleteButtons)
                        // const array=  Array.prototype.slice.call(deleteButtons)
                        //const array  = [...deleteButtons]
                        console.log(array)

                        array.forEach(element => {
                            element.addEventListener('click', event => {
                                const idValue = element.dataset.id;

                                console.log(idValue)
                            })

                        })
                    });

                });
            </script>
    @endpush

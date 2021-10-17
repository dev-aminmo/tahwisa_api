@extends('adminlte::page')


@section('title', 'Tags')


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
    <form class = "row pl-3 pr-3 pt-1" action = "{{route('tags.create')}}" method = "POST">
        @csrf

        <div class = "form-group col-md-6 col-sm-12" style = "margin-bottom:0;">
            <label for = "title">Add a tag:</label>
            <input type = "text" class = "form-control" id = "taskTitle" name = "name">

        </div>
        <div class = "col-sm-6 mt-1 text-left" style = "display: flex;
    align-items: end">
            <button type = "submit" class = "btn btn-success pl-4 pr-4">Add</button>
        </div>

    </form>
    <hr style = "visibility: hidden">

    <div class = "container pt-2">

        <table class = "table table-striped data-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
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

                        ajax: "{{ route('tags.index') }}",
                        columns: [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
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

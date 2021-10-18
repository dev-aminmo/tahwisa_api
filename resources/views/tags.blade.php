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
            <input required type = "text" class = "form-control" id = "taskTitle" name = "name">

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
        <!-- modal  delete -->
        <form method = "post" id = "delete-form" action = " {{route('tags.delete')}}">
            {{--            <input id = "delete-id" type = "hidden" name = "id">--}}
            <input id = "delete-id" hidden name = "id">
            @csrf
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
                            <input id = "btn" type = "submit" class = "btn btn-danger" value = "Delete">
                        </div>
                    </div>
                </div>
            </div>
        </form>

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
                            <form method = "POST" action = "{{route('tags.edit')}}"
                                  id = "formEdit" class = "form-g">
                                @csrf
                                <input hidden name = "id" id = "idUpdate">
                                {{-- <input id = "idUpdate" type = "hidden" name = "id">--}}

                                <div class = "form-c row">
                                    <label for = "name" class = "col-sm-4 col-form-label"
                                           style = "color:#555555">Nom</label>
                                    <div class = "col-sm-8">
                                        <div>
                                            <input required type = "text" class = "form-control" name = "name"
                                                   id = "nameEdit"
                                                   placeholder = "Tag name">
                                            <i class = "fas fa-check-circle"></i>
                                            <i class = "fas fa-exclamation-circle"></i>
                                            <small>Error message</small>
                                        </div>
                                    </div>
                                </div>
                                <div class = "form-c row">
                                    <label for = "surname" class = "col-sm-4 col-form-label"
                                           style = "color:#555555">Prénom</label>
                                    <div class = "col-sm-8">
                                        <div>
                                            <input type = "text" class = "form-control" name = "picture"
                                                   id = "pictureEdit"
                                                   placeholder = "Tag picture">
                                            <i class = "fas fa-check-circle"></i>
                                            <i class = "fas fa-exclamation-circle"></i>
                                            <small>Error message</small>
                                        </div>
                                    </div>
                                </div>


                                <button type = "button" id = "submitButtonFormEdit"
                                        class = "btn btn-block bg-gradient-primary"
                                        style = "margin-left: 40%;margin-top:20px ; width: 120px;color: white">
                                    Enregistrer
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
                        const deleteArray = Array.from(deleteButtons)
                        const editButtons = document.getElementsByClassName('edit');
                        const editArray = Array.from(editButtons)

                        deleteArray.forEach(element => {
                            element.addEventListener('click', event => {
                                const idValue = element.dataset.id;
                                //console.log(element.dataset.picture)
                                //   $('#delete-form').action = "/AAA";
                                //  $("#delete-form").attr('action', $("#delete-form").attr('action') + "/" + idValue);
                                $('#delete-id').val(idValue);
                            })

                        })
                        editArray.forEach(element => {
                            element.addEventListener('click', event => {
                                const idUpdate = element.dataset.id;
                                console.log(element.dataset.id)

                                $('#idUpdate').val(element.dataset.id);
                                $('#nameEdit').val(element.dataset.name);
                                $('#pictureEdit').val(element.dataset.picture);
                                console.log($('#idUpdate').val())

                                //   $('#delete-form').action = "/AAA";
                                //  $("#delete-form").attr('action', $("#delete-form").attr('action') + "/" + idValue);
                                // $('#delete-id').val(idValue);
                            })

                        })
                        $("#submitButtonFormEdit").click(function () {
                            console.log("clicked")
                            $('#nameEdit').val($.trim($('#nameEdit').val()))
                            $('#formEdit').submit();
                            /*     $('#formEdit').submit(function(e){
                                     e.preventDefault();
                                     checkEditInputs();

                                 });
                                 function checkEditInputs() {
                                     $('#nameEdit').val($.trim( $('#nameEdit').val()))
                                     $('#formEdit').submit();
                                 }*/
                        });


                    });

                });
            </script>
    @endpush

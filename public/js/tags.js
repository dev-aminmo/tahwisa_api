$(function () {

    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        order: [["0", "desc"]],
        ajax: route('tags.index'),
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
                $('#delete-id').val(idValue);
            })

        })
        editArray.forEach(element => {
            element.addEventListener('click', event => {
                $('#nameEdit-error').css('display', 'none');
                $('#pictureEdit-error').css('display', 'none');

                $('#idUpdate').val(element.dataset.id);
                $('#nameEdit').val(element.dataset.name);
                $('#pictureEdit').val(element.dataset.picture);
                $('#topTagUpdate').val(element.dataset.top);
            })

        })
        let isPicture = true;
        $("#submitButtonFormEdit").click(function () {
            $('#nameEdit').val($.trim($('#nameEdit').val()))
            if ($('#nameEdit').val().length == 0) {
                $('#nameEdit-error').css('display', 'inline-block');
            } else {
                if ($('#pictureEdit').val().length > 0) {
                    getData($('#pictureEdit').val()).then(function (r) {
                        isPicture = true;
                    }).catch(function (e) {
                        isPicture = false;
                    }).finally(() => {
                        if (!isPicture) {
                            $('#pictureEdit-error').css('display', 'inline-block');
                        } else {
                            $('#formEdit').submit();
                        }
                    });
                } else {
                    $('#formEdit').submit();

                }


            }

        });

        function getData(value) {
            return new Promise(function (resolve, reject) {
                return $.ajax({
                    type: "get",
                    url: value,
                    success: function (message, text, response) {
                        if (response.getResponseHeader('Content-Type').indexOf("image") != -1) {
                            resolve(true);
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        reject(false);
                    }
                });
            });
        };

        /*       $.validator.addMethod(
                   "imageUrl",
                   function (value, element) {
                       return isPicture;
                   },
                   "make sure picture Url is valid"
               );*/

        /*      $('#formEdit').validate({
                  rules: {
                      name: {
                          required: true,
                          minlength: 1
                      },
                      picture: {
                          required: false,
                          imageUrl: true
                      }
                  }

              });*/


    });

});

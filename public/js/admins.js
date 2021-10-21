$(function () {
    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        order: [["0", "desc"]],
        ajax: route('users.index'),
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
        const deleteButtonsArray = Array.from(deleteButtons)
        const editButtons = document.getElementsByClassName('edit');
        const editArray = Array.from(editButtons)
        deleteButtonsArray.forEach(element => {
            element.addEventListener('click', event => {
                const idValue = element.dataset.id;
            })

        })
        editArray.forEach(element => {
            element.addEventListener('click', event => {
                $('#idUpdate').val(element.dataset.id);
                $('#roleUpdate').val(element.dataset.role);
                // $('#nameEdit').val(element.dataset.name);
                //$('#pictureEdit').val(element.dataset.picture);
                //$('#pictureEdit').val(element.dataset.picture);
            })

        })

    });

});

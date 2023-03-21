
var DT = "";
var panelId = "";
$('#modal-add').on('shown.bs.modal', function (e) {
    let btn = e.relatedTarget;
    panelId = btn.dataset.panelId;
    $('#scheduled_list').DataTable().destroy();
    DT = $('#scheduled_list').DataTable({
        processing: true,
       // serverSide : true,
        'language':{
        "loadingRecords": "&nbsp;",
            "processing": "Processing..."
    },
        //ajax: 'server_processing.php',
        ajax: 'gpt/writer_actions.php?for=list&&panel_id='+panelId,
        pageLength: 10,
        searching: false,
        columns:
            [
                {"data": "Type"},
                {"data": "Title"},
                {"data": "Content"},
                {"data": "Image"}
            ],

        "columnDefs": [{
            "targets": 4, "data": "", "render": function (data, type, row, meta) {
                if(row.Is_Executed === "1"){
                    return '' +
                        '<span class="badge badge-success">Completed</span>';
                }else{
                    return '' +
                        '<span class="badge badge-warning">Pending</span>';
                }

            }
        },
            {
                "targets": 5, "data": "", "render": function (data, type, row, meta) {
                    let buttons = '';
                    if(row.Is_Executed === "1"){
                        buttons += '<button class="m-1 btn btn-block btn-primary btn-sm" onclick="viewAction(' + row.Id + ')" ><i class="fas fa-eye"></i> View</button>';
                    }else{
                        buttons += '<button class="m-1 btn btn-block btn-primary btn-sm" onclick="viewAction(' + row.Id + ')" disabled><i class="fas fa-eye"></i> View</button>';
                    }

                    buttons +=  '<button class="m-1 btn btn-block btn btn-info btn-sm" onclick="editAction(' + row.Id + ')"><i class="fas fa-pencil-alt"></i> Edit</button>' +
                        '<button class="m-1 btn btn-block btn btn-danger btn-sm" onclick="deleteConfirmation(' + row.Id + ')"><i class="fas fa-trash"></i> Delete</button>';

                    return buttons;

                }
            }
        ]

    });
});
function viewAction(row) {
    $.ajax({
        type: 'post',
        url: 'gpt/query_actions.php',
        data: {Id: row, for: "select"},
        success: function (response) {
            var data = JSON.parse(response)
            var container = document.getElementById("view_html")
            container.innerHTML = data['Html'];
            $('#view').modal('show');
        }

    });
}

function nl2br (str) {
    var breakTag = '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function editAction(row) {
    $.ajax({
        type: 'post',
        url: 'gpt/query_actions.php',
        data: {Id: row, for: "edit"},
        success: function (response) {
            var data = JSON.parse(response)
            if(data[0]['Type'] === 'Query'){
                $("#InputTitle").val(data[0]['Title'])
                $("#InputContent").val(data[0]['Content'])
                $("#InputImage").val(data[0]['Image'])
                $("#InputSummarize").val(data[0]['Summarize'])
                $("#InputId").val(data[0]['Id'])

                $('#edit').modal('show');
            }else{
                $("#aiKeyword").val(data[0]['Title'])
                $("#aiType").val(data[0]['Content_Slug'])
                $("#aiVariant").val(data[0]['Variants'])
                $("#aiSummarize").val(data[0]['Summarize'])
                $("#aiImage").val(data[0]['Image'])
                $("#aiId").val(data[0]['Id'])

                $('#writer_edit').modal('show');
            }
        }

    });

}


function deleteConfirmation(row) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'gpt/query_actions.php',
                data: {Id: row, for: "delete"},
                success: function (data) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Record has been deleted.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    })
                    DT.ajax.reload();
                }
            });
        }
    })
}

$(function () {
    $.ajax({
        type: 'post',
        url: 'gpt/query_actions.php',
        data: {for: "rss"},
        success: function (response) {
            if(response){
                $("#myInput").val(response)
            }
        }
    });

    $('#query_form').on('submit', function (e) {

        e.preventDefault();
        
        $.ajax({
            type: 'post',
            url: 'gpt/query_actions.php?panel_id='+panelId,
            data: $('#query_form').serialize(),
            success: function () {
                Swal.fire({
                    title: 'Added!',
                    text: 'Record has been added.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                })
                DT.ajax.reload();
                var frm = document.getElementsByName('query_form')[0];
                frm.reset();  // Reset all form data
            }
        });

    });

    $('#edit_query').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'gpt/query_actions.php',
            data: $('#edit_query').serialize(),
            success: function () {
                $('#edit').modal('hide');
                Swal.fire({
                    title: 'Updated!',
                    text: 'Record has been updated.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                })
                DT.ajax.reload();
            }
        });

    });

    $('#writer_add_form').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'gpt/writer_actions.php?panel_id='+panelId,
            data: $('#writer_add_form').serialize(),
            success: function () {
                Swal.fire({
                    title: 'Added!',
                    text: 'Record has been added.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                })
                DT.ajax.reload();
                var frm = document.getElementsByName('writer_add_form')[0];
                frm.reset();  // Reset all form data
            }
        });

    });

    $('#edit_writer_form').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'gpt/writer_actions.php',
            data: $('#edit_writer_form').serialize(),
            success: function () {
                $('#writer_edit').modal('hide');
                Swal.fire({
                    title: 'Updated!',
                    text: 'Record has been updated.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                })
                DT.ajax.reload();
            }
        });

    });


    $('#query_form_button').on('click', function (e) {

        if (!$("#query_form").valid()) {
            return false;
        }
        e.preventDefault();

        Swal.fire({
            title: 'Importing Post',
            html: 'Please Wait',
            showConfirmButton: false,
            allowOutsideClick: false,
            timerProgressBar: true
        });
        
        $.ajax({
            type: 'post',
            url: 'gpt/import.php?action=query&panel_id='+panelId,
            data: $('#query_form').serialize(),
            success: function () {
                swal.close();
                Swal.fire({
                    title: 'Added!',
                    text: 'Post has been added.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                })
                DT.ajax.reload();
                var frm = document.getElementsByName('query_form')[0];
                frm.reset();  // Reset all form data
            }
        });

    });


    $('#writer_add_form_button').on('click', function (e) {

        if (!$("#writer_add_form").valid()) {
            return false;
        }
        e.preventDefault();

        Swal.fire({
            title: 'Importing Post',
            html: 'Please Wait',
            showConfirmButton: false,
            allowOutsideClick: false,
            timerProgressBar: true
        });
        
        $.ajax({
            type: 'post',
            url: 'gpt/import.php?action=writer&panel_id='+panelId,
            data: $('#writer_add_form').serialize(),
            success: function () {
                swal.close();
                Swal.fire({
                    title: 'Added!',
                    text: 'Post has been added.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                })
                DT.ajax.reload();
                var frm = document.getElementsByName('writer_add_form')[0];
                frm.reset();  // Reset all form data
            }
        });

    });

});


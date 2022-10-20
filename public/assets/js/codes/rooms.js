$(document).ready(function(){ 
 
 

    var users = $('#RoomsdataTable').DataTable();
    RoomsDatatable(); 

    
    function RoomsDatatable() // datatable
    {     
        users.destroy();
        users = $('#RoomsdataTable').DataTable({
            processing:true,
            serverSide:true,
            info: true,
            stateSave: false,
            fixedHeaders: true,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ], 
            ajax: {
                "url": '/rooms/getAllRooms',
                "type": "GET",
                 "data": {
                    search_option: $('#searchby').val()  
                }
            },
            columns:[
                {"data":'id','className':'id'},  
                {
                    "data": function (data) {
                        return '<span class="dot" style="background-color:' + data.room_color +'; margin-right: 5px;"></span> ' + data.name;
                    }, "orderable": false
                },
                {"data":'room_color','className':'room_color', "orderable": false}, 
                {"data":'description', "orderable": false}, 
                {"data":'added_by', "orderable": false},
                {"data":'created_at', "orderable": false},
                {
                    "data": function (data) {
                        return ' <button class="btn btn-primary btn-sm" id="edit" ><li class = "fas fa-edit"></li></button> <button class="btn btn-danger btn-sm" id="delete" ><li class = "fas fa-trash"></li></button>';
                    }, "orderable": false
                },
            ],
            order: [
                [0, 'asc']
            ]
        });
    }




    
    $("#RoomsdataTable").on('click', '#delete', function () { // delete room action
        $('#prof').show("fast", function () { });
        var currentRow = $(this).closest("tr");
        var id         = currentRow.find("td:eq(0)").text(); 
        var name       = currentRow.find("td:eq(1)").text(); 
         
        Swal.fire({
            title:              'Are you sure you want to delete ' + name + '?', 
            icon:               'warning',
            showCancelButton:   true, 
            cancelButtonColor:  '#d33',
            confirmButtonText:  'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                delete_room(id); // reset password function
            }
        })
    });

    function delete_room(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/users/deleteRoom',
            type: "POST",
            data: {
                id: id,
                _token: CSRF_TOKEN
            },
            cache: false,
            beforeSend: function () {
                $('#modal_process').show();
            },
            complete: function () {
                $('#modal_process').hide();
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    noti(dataResult.message, 'Success!', 'success');
                    RoomsDatatable();
                } else {
                    noti(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
    }
     


    function noti(message, title, icons) { // alert modal
        Swal.fire({
            icon: icons,
            title: title, 
            text: message 
        })

    }
     
 

    $("#cancelroommodal").on('click', function () { // hide modal
        $('#add_new_room').modal('hide');
    });
 


    $("#add_rooms").on('click', function () { // reset modal data, when clicking add room button
        $('#add_room_title').text('Add New Room');
        resetData();
        $('#room_name').css( "background", 'none' );
        $('#room_name').css( "color", 'gray' );
    });


    function resetData(){ // reset modal data
        $('#room_id').val('');
        $('#room_name').val('');
        $('#room_description').val(''); 
    }


    
    $('#add_room_form').on('submit', function (event) { // add and edit room actions
        event.preventDefault(); 

        var url = '';
        var id = $('#room_id').val();  
        if(id == ''){
            url =  '/users/addRoom';  
        }else{ 
            url =  '/users/updateRoom'; 
        } 

        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#modal_process').show();
            },
            complete: function () {
                $('#modal_process').hide();
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);

                if (dataResult.statusCode == 200) {
                    noti(dataResult.message, 'Success!', 'success');
                    $('#add_new_room').modal('hide');
                    RoomsDatatable(); 
                    resetData();

                }else if (dataResult.statusCode == 202) { 
                    $('#modal_process').hide();

                    var array_message = ''; 
                    $.each(dataResult.message, function( index, value ) { //LOOP VALIDATION ERRORS
                        array_message =  value;
                        return false;
                    });
                      
                    noti(array_message, 'Error', 'error');

                } else { 
                    $('#modal_process').hide();
                    noti(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again.', 'Success!', 'success');
            }
        });
    });



    
    $("#RoomsdataTable").on('click', '#edit', function () {  // get edit room data (modal)
        var currentRow = $(this).closest("tr");
        var id = currentRow.find("td:eq(0)").text();  
        

        $.ajax({
            url: 'users/getRoom/' + id,
            type: "GET", 
            cache: false,
            beforeSend: function () {
                $('#modal_process').show();
            },
            complete: function () {
                $('#modal_process').hide();
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) { 
                    
                    resetData();
                    $('#room_id').val(dataResult.data.id);
                    $('#room_name').val(dataResult.data.name);
                    $('#room_description').val(dataResult.data.description); 
                    $('#room_color').val(dataResult.data.room_color);
                    $('#room_name').css( "background", dataResult.data.room_color );
                    $('#room_name').css( "color", 'white' );
                    $('#add_room_title').text('Edit Room ('+ dataResult.data.name +')');

                    $('#add_new_room').modal('show');
                } else {
                    noti(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
        
    });


    $('#room_color').on('change', function(){ 
        $('#room_name').css( "background", this.value );
        $('#room_name').css( "color", 'white' );
    });
});
$(document).ready(function(){ 
 
 

    var users = $('#dataTable').DataTable();
    UsersDatatable(); 

    
    function UsersDatatable() // datatable
    {     
        users.destroy();
        users = $('#dataTable').DataTable({
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
                "url": '/users/getAllUser',
                "type": "GET",
                 "data": {
                    status: $('#user_status').val(),
                }
            },
            columns:[
                {"data":'id','className':'id'},
                {"data":'first_name',"orderable": false},
                {"data":'last_name', "orderable": false},
                {"data":'email', "orderable": false}, 
                {"data":'role', "orderable": false},
                {"data":'created_at', "orderable": false},
                {
                    "data": function (data) {  
                            return adjustButtons(data['status']);
                     
                    }, "orderable": false
                },
            ],
            order: [
                [0, 'asc']
            ]
        });
    }


    function adjustButtons(status) { // adjust buttons depending on the data status
        if (status == '2') {   
            return '  <button class="btn btn-primary btn-sm" id="edit" ><li class = "fas fa-edit"></li></button> <button class="btn btn-success btn-sm" id="approve" ><li class = "fas fa-check"></li></button> <button class="btn btn-danger btn-sm" id="delete" ><li class = "fas fa-trash"></li></button>';
        }else{
            return ' <button class="btn btn-primary btn-sm" id="edit" ><li class = "fas fa-edit"></li></button> <button class="btn btn-danger btn-sm" id="delete" ><li class = "fas fa-trash"></li></button>';
        }
   }

    
    $("#dataTable").on('click', '#delete', function () { // delete user action
        $('#prof').show("fast", function () { });
        var currentRow  = $(this).closest("tr");
        var id          = currentRow.find("td:eq(0)").text(); 
        var first_name  = currentRow.find("td:eq(1)").text(); 
        var user_status = $('#user_status').val(); 
        var title       = '';

        user_status === '1' ? title =  'Are you sure you want to delete ' + first_name + '?' : title = 'Are you sure you want to decline ' + first_name + '?';

        Swal.fire({
            title: title, 
            icon: 'warning',
            showCancelButton: true, 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                delete_user(id); // reset password function
            }
        })
    });

    function delete_user(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/users/deleteUser',
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
                    UsersDatatable();
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
     

    $("#cancelmodal").on('click', function () { // hide modal
        $('#add_new_user').modal('hide');
    });
    


    $("#add_users").on('click', function () { // reset data when clicking add new user button
        $('#add_user_title').text('Add New User');
        resetData();
    });

    
    function resetData(){ // reset all modal data
        $('#id').val('');
        $('#first_name').val('');
        $('#middle_name').val('');
        $('#last_name').val('');
        $('#phone_number').val('');
        $('#email').val('');
        $('#username').val(''); 
        $("#role").val("2");
    }


    
    $('#add_user_form').on('submit', function (event) { //add and update user actions
        event.preventDefault(); 

        var url = '';
        var id = $('#id').val();  
        if(id == ''){
            url =  '/users/addUser';  
        }else{ 
            url =  '/users/updateUser'; 
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
                    $('#add_new_user').modal('hide');
                    UsersDatatable(); 
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



    
    
    
    $("#dataTable").on('click', '#edit', function () { //get all edit data (modal)
        var currentRow = $(this).closest("tr");
        var id = currentRow.find("td:eq(0)").text();  
          

        $.ajax({
            url: 'users/getUser/' + id,
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
                    $('#id').val(dataResult.data.id);
                    $('#first_name').val(dataResult.data.first_name);
                    $('#middle_name').val(dataResult.data.middle_name);
                    $('#last_name').val(dataResult.data.last_name);
                    $('#phone_number').val(dataResult.data.phone_number);
                    $('#role').val(dataResult.data.role);
                    $('#email').val(dataResult.data.email);
                    $('#username').val(dataResult.data.username);  
                    $('#add_user_title').text('Edit User ('+ dataResult.data.first_name + ' ' +dataResult.data.last_name +')');

                    $('#add_new_user').modal('show');
                } else {
                    noti(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
        
    });

    
    $('#user_status').on('change', function () {   // datatable filter
        UsersDatatable();   
    });



    
    $("#dataTable").on('click', '#approve', function () { // approve registrant
        $('#prof').show("fast", function () { });
        var currentRow = $(this).closest("tr");
        var id = currentRow.find("td:eq(0)").text(); 
        var first_name = currentRow.find("td:eq(1)").text(); 
         
        Swal.fire({
            title: 'Are you sure you want to approve the request of ' + first_name + '?', 
            icon: 'question',
            showCancelButton: true, 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                approve_user(id);  
            }
        })
    });

    function approve_user(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/users/approveUser',
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
                    UsersDatatable();
                } else {
                    noti(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
    }
     

    
    $("#cancelbulkmodal").on('click', function () { // hide bulk modal 
        $('#bulk_upload_modal').modal('hide');
    });


    $("#errorlog").keypress(function (e) { //prevent user from typing in the error log
        e.preventDefault(); 
    });

 



    
    $('#bulk_upload_form').on('submit', function (event) { // bulk upload ajax
        event.preventDefault();  
        

        $.ajax({
            url: '/users/BulkUploadUser',
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
                $('#errorlog').text(''); 


                if (dataResult.statusCode == 200) {

                    noti(dataResult.message, 'Success!', 'success');
                    $('#bulk_upload_modal').modal('hide');
                    UsersDatatable();  

                }else if (dataResult.statusCode == 202) { 

                    $('#modal_process').hide();  
                    var array_message = ''; 

                    $.each(dataResult.message, function( index, value ) { //LOOP VALIDATION ERRORS
                        array_message +=  '------------------------------------\n';
                        array_message +=  'Line number (' + value.row_number + ') \n';  
  
                        $.each(value.first_name, function( index, value ) { //First Name 
                            array_message += value  + '\n';
                        }); 
                        $.each(value.middle_name, function( index, value ) { //Middle Name  
                            array_message += value  + '\n';
                        }); 
                        $.each(value.last_name, function( index, value ) { //Last Name  
                            array_message += value  + '\n';
                        }); 
                        $.each(value.email, function( index, value ) { //Email  
                            array_message += value  + '\n';
                        }); 
                        $.each(value.phone_number, function( index, value ) { //Phone Number 
                            array_message += value  + '\n';
                        }); 
                        $.each(value.duplicate, function( index, value ) { //Duplicate 
                            array_message += value  + '\n';
                        }); 
                        $.each(value.exist, function( index, value ) { //Exist 
                            array_message += value  + '\n';
                        }); 
                    });

                    $('#errorlog').append(array_message); 

                } else { 
                    $('#modal_process').hide();
                    noti(dataResult.message, 'Error!', 'error');
                }

                $('#file').val('');

            },
            error: function (e) {
                noti('An error occured, please try again.', 'Success!', 'success');
            }
        });
    });


});
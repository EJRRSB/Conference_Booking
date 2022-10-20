$(document).ready(function () {
 
    

    function noti(message, title, icons) {
        Swal.fire({
            icon:  icons,
            title: title, 
            text:  message 
        })

    }
    
    $("#cancelchangepasswordmodal").on('click', function () {
        $('#changepassword_modal').modal('hide');
        resetData(); 
    });

    
    
    $('#changepassword_form').on('submit', function (event) {
        event.preventDefault(); 
 
        

        $.ajax({
            url: '/users/changePassUser',
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

                $('#changepassword_errors').text(''); 
                if (dataResult.statusCode == 200) {

                    $('#modal_process').hide();
                    noti(dataResult.message, 'Success!', 'success');
                    $('#changepassword_modal').modal('hide');
                    resetData();

                }else if (dataResult.statusCode == 202) { 

                    $('#modal_process').hide();
                    var array_message = ''; 
                    $.each(dataResult.message, function( index, value ) { //LOOP VALIDATION ERRORS 
                        $('#changepassword_errors').append(value + '<br>'); 
                    });
 

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



    function resetData(){
        $('#current_password').val('');
        $('#new_password').val('');
        $('#password_confirmation').val('');
        $('#changepassword_errors').hide();
    }

    
});

$(document).ready(function(){ 
 
  
    
    var date_range = moment(); //Get the current date 
    $('input[name="daterange"]').daterangepicker({ 
        locale: {
          format: 'YYYY-MM-DD'
        },
        minDate: new Date(),
        startDate: date_range.format("YYYY-MM-DD"),
        endDate:   date_range.add(3,'month').format("YYYY-MM-DD")
    });

    var date1;
    var date2;
    getDates();
    function getDates(){ 
        var dates = $('#daterange').val().split(" - ");
        date1 = dates[0] + ' 00:00:00';
        date2 = dates[1] + ' 23:59:59';
    }

    $('#daterange').on('change', function () {   
        getDates(); 
    });
    

    $('#ListOfPendingBookings').on('click', function () {   
        BookingReports('ListOfPendingBookings.csv','2');
    });

    $('#ListOfApprovedBookings').on('click', function () {   
        BookingReports('ListOfApprovedBookings.csv','1');
    });

    $('#ListOfDeclinedBookings').on('click', function () {   
        BookingReports('ListOfDeclinedBookings.csv','3');
    });

    $('#ListOfCanceledBookings').on('click', function () {   
        BookingReports('ListOfCanceledBookings.csv','4');
    });

    function BookingReports(filename, status){ 
        $.ajax({
            url: '/reports/ListOfBookingsByStatus',
            type: "GET", 
            cache: false,
            data: {
                status:     status, 
                book_date1: date1,
                book_date2: date2
            },
            beforeSend: function () {  
                $('#modal_process').show();
            }, 
            complete: function () {
                $('#modal_process').hide();
                process_counter = 0;
            }, 
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult); 

                if (dataResult.statusCode == 202) { // ERROR

                    var errors = '';  
                    $.each(dataResult.message, function (index, val) {    
                        errors += val;
                    });
                    alert(errors);
                    noti(errors, 'Error!', 'error');

                } else if (dataResult.statusCode == 201) { // ERROR

                    noti(dataResult.message, 'Error!', 'error');

                } else if (dataResult.statusCode == 200) { // SUCCESS! 

                    var csv = 'Room, Booked By,Start Time,End Time,Status, Participants, Mode, Type, Internal Info, Client Number, Client Name, Client Type, Purpose, Agenda, It Requirements,Approver, Date Added' + '\n'; // CREATE CSV
                     

                    $.each(dataResult.message, function (index, val) {  
                        csv += val['room_name'] + ", ";
                        csv += val['added_by'] + ", ";
                        csv += val['start_time'].replace(",", " ") + ", ";
                        csv += val['end_time'] + ", ";
                        csv += val['status'] + ", ";  
                        var participants = '';
                        $.each(val.participants, function( index, value ) {   
                            if(value.user){  
                                participants += value.user.first_name + ' ' + value.user.last_name + ' ; '
                            }else{
                                participants += value.guest_email  + ' ; '   
                            }
                        });   
                        csv += participants + ", "; 

                        csv += val['mode'] + ", ";
                        csv += val['type'] + ", ";
                        csv += (val['internal_option_others'] != '') ? val['internal_option'] + ' : ' + val['internal_option_others'] + ", " : val['internal_option'] + ",";
                        csv += val['client_number'] + ", ";
                        csv += val['client_name'] + ", ";
                        csv += (val['client_type_others'] != '') ? val['client_type'] + ' : ' + val['client_type_others'] + ", " : val['client_type'] + ",";
                        csv += val['purpose'] + ", ";
                        csv += val['agenda'] + ", ";
                        csv += (val['it_requirements_others'] != '') ? val['it_requirements'] + ' : ' + val['it_requirements_others'] + ", " : val['it_requirements'] + ",";
                        csv += val['approver'].replace(",", " ")  + ", ";
                        // csv += val['declined_by'].replace(",", " ")  + ", ";
                        csv += val['created_at'].replace(",", " ")  + ", ";
                        csv += "\n";
                    });

                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = filename;
                    hiddenElement.click();
                }

            },
            error: function (e) {
                $('#modal_process').hide(); 
                noti('An error occured, please try again.', 'Error!', 'error');
            }
        });
    }

    function noti(message, title, icons) {
        Swal.fire({
            icon: icons,
            title: title, 
            text: message 
        })

    }


    

    $('#ListOfArchivedBookings').on('click', function () {   
        $.ajax({
            url: '/reports/ListOfArchivedBookings',
            type: "GET", 
            cache: false, 
            beforeSend: function () {  
                $('#modal_process').show();
            }, 
            complete: function () {
                $('#modal_process').hide();
                process_counter = 0;
            }, 
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult); 

                if (dataResult.statusCode == 202) { // ERROR

                    var errors = '';  
                    $.each(dataResult.message, function (index, val) {    
                        errors += val;
                    });
                    alert(errors);
                    noti(errors, 'Error!', 'error');

                } else if (dataResult.statusCode == 201) { // ERROR

                    noti(dataResult.message, 'Error!', 'error');

                } else if (dataResult.statusCode == 200) { // SUCCESS! 

                    var csv = 'Room, Booked By,Start Time,End Time,Status, Participants, Mode, Type, Internal Info, Client Number, Client Name, Client Type, Purpose, Agenda, It Requirements,Approver, Date Added' + '\n'; // CREATE CSV
                     

                    $.each(dataResult.message, function (index, val) {  
                        csv += val['room_name'] + ", ";
                        csv += val['added_by'] + ", ";
                        csv += val['start_time'].replace(",", " ") + ", ";
                        csv += val['end_time'] + ", ";
                        csv += val['status'] + ", ";  
                        var participants = '';
                        $.each(val.participants, function( index, value ) {   
                            if(value.user){  
                                participants += value.user.first_name + ' ' + value.user.last_name + ' ; '
                            }else{
                                participants += value.guest_email  + ' ; '   
                            }
                        });   
                        csv += participants + ", "; 

                        csv += val['mode'] + ", ";
                        csv += val['type'] + ", ";
                        csv += (val['internal_option_others'] != '') ? val['internal_option'] + ' : ' + val['internal_option_others'] + ", " : val['internal_option'] + ",";
                        csv += val['client_number'] + ", ";
                        csv += val['client_name'] + ", ";
                        csv += (val['client_type_others'] != '') ? val['client_type'] + ' : ' + val['client_type_others'] + ", " : val['client_type'] + ",";
                        csv += val['purpose'] + ", ";
                        csv += val['agenda'] + ", ";
                        csv += (val['it_requirements_others'] != '') ? val['it_requirements'] + ' : ' + val['it_requirements_others'] + ", " : val['it_requirements'] + ",";
                        csv += val['approver'].replace(",", " ")  + ", "; 
                        csv += val['created_at'].replace(",", " ")  + ", ";
                        csv += "\n";
                    });

                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'ListOfArchivedBookings.csv';
                    hiddenElement.click();
                }

            },
            error: function (e) {
                $('#modal_process').hide(); 
                noti('An error occured, please try again.', 'Error!', 'error');
            }
        });
    });








    

    $('#ListOfActiveUsers').on('click', function () {   
        UsersReports('ListOfActiveUsers.csv','1');
    });

    

    $('#ListOfPendingUsers').on('click', function () {   
        UsersReports('ListOfPendingUsers.csv','2');
    });
    

    function UsersReports(filename, status){ 
        $.ajax({
            url: '/reports/ListOfUsersByStatus',
            type: "GET", 
            cache: false,
            data: {
                status: status, 
            },
            beforeSend: function () {  
                $('#modal_process').show();
            }, 
            complete: function () {
                $('#modal_process').hide();
                process_counter = 0;
            }, 
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult); 

                if (dataResult.statusCode == 202) { // ERROR

                    var errors = '';  
                    $.each(dataResult.message, function (index, val) {    
                        errors += val;
                    });
                    alert(errors);
                    noti(errors, 'Error!', 'error');

                } else if (dataResult.statusCode == 201) { // ERROR

                    noti(dataResult.message, 'Error!', 'error');

                } else if (dataResult.statusCode == 200) { // SUCCESS! 

                    var csv = 'First Name,Middle Name,Last Name,Email,Role,Date Added' + '\n'; // CREATE CSV
                     

                    $.each(dataResult.message, function (index, val) {  
                        csv += val['first_name'] + ", ";
                        csv += val['middle_name'] + ", ";
                        csv += val['last_name'] ? val['last_name'].replace(",", " ") + ", " : '';
                        csv += val['email'] + ", ";
                        csv += val['role'] + ", ";
                        csv += val['created_at'] + ", "; 
                        csv += "\n";
                    });

                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = filename;
                    hiddenElement.click();
                }

            },
            error: function (e) {
                $('#modal_process').hide(); 
                noti('An error occured, please try again.', 'Error!', 'error');
            }
        });
    }

    
    $('#ListOfAllRooms').on('click', function () {   
        $.ajax({
            url: '/reports/ListOfAllRooms',
            type: "GET", 
            cache: false, 
            beforeSend: function () {  
                $('#modal_process').show();
            }, 
            complete: function () {
                $('#modal_process').hide();
                process_counter = 0;
            }, 
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult); 

                if (dataResult.statusCode == 202) { // ERROR

                    var errors = '';  
                    $.each(dataResult.message, function (index, val) {    
                        errors += val;
                    }); 
                    noti(errors, 'Error!', 'error');

                } else if (dataResult.statusCode == 201) { // ERROR

                    noti(dataResult.message, 'Error!', 'error');

                } else if (dataResult.statusCode == 200) { // SUCCESS! 

                    var csv = 'Room Name,Description,Added By,Date Added' + '\n'; // CREATE CSV
                     

                    $.each(dataResult.message, function (index, val) {  
                        csv += val['name'] + ", ";
                        csv += val['description'] + ", ";
                        csv += val['added_by'].replace(",", " ") + ", ";
                        csv += val['created_at'] + ", "; 
                        csv += "\n";
                    });

                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'ListOfAllRooms.csv';
                    hiddenElement.click();
                }

            },
            error: function (e) {
                $('#modal_process').hide(); 
                noti('An error occured, please try again.', 'Error!', 'error');
            }
        });
    });




    
    
});
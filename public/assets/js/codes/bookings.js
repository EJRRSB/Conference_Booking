$(document).ready(function(){  

    $('#weekdays').weekdays();

    var date = moment(); //Get the current date 
    $('#book_date').val(date.format("YYYY-MM-DD"));  

    var bookingdate = moment(); //Get the current date 
    $('#booking_date').attr('min', bookingdate.format("YYYY-MM-DD")); // date selection present and future only
    $('#recurring_end_date').attr('min', bookingdate.format("YYYY-MM-DD")); // date selection present and future only 
    $('#booking_date').attr('max', bookingdate.add(1,'month').format("YYYY-MM-DD")); // add 1 month set max date selection 
    $('#recurring_end_date').attr('max', bookingdate.format("YYYY-MM-DD"));  // set max date selection 
    

    var date_range = moment(); //Get the current date 
    $('input[name="daterange"]').daterangepicker({ // date range picker set up date format
        locale: {
          format: 'YYYY-MM-DD'
        },
        minDate: new Date(),
        startDate: date_range.format("YYYY-MM-DD"),
        endDate: date_range.add(3,'month').format("YYYY-MM-DD")
    });
    
    var date1;
    var date2;
    getDates();
    function getDates(){ // split date range into 2 dates
        var dates = $('#daterange').val().split(" - ");
        date1 = dates[0] + ' 00:00:00';
        date2 = dates[1] + ' 23:59:59';
    }


    
    $('#daterange').on('change', function () {   //date range on change
        getDates();
        BookingsDatatable(); 
    });

    var bookings = $('#BookingdataTable').DataTable();
    BookingsDatatable(); 

    
    function BookingsDatatable() // datatable
    {     
        bookings.destroy();
        bookings = $('#BookingdataTable').DataTable({ 
            processing:true,
            serverSide:true,
            info: true,
            stateSave: false,
            fixedHeaders: true,
            scrollX: true,
            scrollY: true,
            lengthMenu: [
                [10, 15],
                [10, 15]
            ], 
            ajax: {
                "url": '/bookings/getAllBookings',
                "type": "GET",
                "data": {
                    status: $('#booking_type').val(),
                    // book_date: $('#book_date').val(),
                    book_date1: date1,
                    book_date2: date2
                }
            }, 
            columns:[
                {"data":'id','className':'id'},
                {"data":'room_id','className':'room_id',"orderable": false},  
                {
                    "data": function (data) { 
                        return '<span class="dot" style="background-color:' + data.room_color +'; margin-right: 5px;"></span> ' + data.room_name;
                    }, "orderable": false
                }, 
                {"data":'booking_number', "orderable": false },
                {"data":'purpose', "orderable": false},  
                {"data":'start_time', "orderable": false},
                {"data":'end_time', "orderable": false},
                {"data":'status', "orderable": false},
                {"data":'added_by', "orderable": false},
                {"data":'approver','className':'approver', "orderable": false},
                // {"data":'declined_by','className':'declined_by', "orderable": false},
                {"data":'created_at', "orderable": false},
                {
                    "data": function (data) {
                        return adjustButtons(data['status'], data['is_archived']) ;
                    }, "orderable": false
                },
                {"data":'is_archived', 'className':'is_archived',"orderable": false},
            ],
            'columnDefs': [
                {
                   'targets': 2,
                   'checkboxes': {
                      'selectRow': false
                   }
                }
             ], 
            order: [
                [0, 'asc']
            ]
        });
    }



    $('#approve_booking_button').on('click', function(){ // approve booking
        Swal.fire({
            title: 'Are you sure you want to approve the booking of <br> '+ $('#booked_by').text() + ' <br> for ' + $('#venue').text() + '?', 
            icon: 'question',
            showCancelButton: true, 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                update_status_booking($('#booking_view_id').text(), '1'); 
            }
        })
    });
 

    function update_status_booking(id, status) { // booking status actions
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/bookings/updateStatusBooking',
            type: "POST",
            data: {
                id: id,
                status: status,
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
                    noti_success(dataResult.message, 'Success!', 'success');
                    // BookingsDatatable(); 
                    $('#calendarinfomodal').modal('hide');
                } else {
                    noti(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
    }




    $('#decline_booking_button').on('click', function(){ //decline booking
        Swal.fire({
            title:             'Are you sure you want to decline the booking of <br> '+ $('#booked_by').text() + ' <br> for ' + $('#venue').text() + '?', 
            icon:              'warning',
            showCancelButton:  true, 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {  
                update_status_booking($('#booking_view_id').text(), '3'); 
            }
        })
    });
     
 

    getMultipleActions();
    function getMultipleActions()
    {
        $('#action_approve_multiple').hide();
        $('#action_decline_multiple').hide();
        $('#action_cancel_multiple').hide();  
        $('#action_noavailable_multiple').hide();  
        if($('#booking_type').val() === '2'){ 
            if($('#role').text() == '1'){
                $('#action_approve_multiple').show();
                $('#action_decline_multiple').show();
                $('#action_cancel_multiple').show(); 
            }else{
                $('#action_cancel_multiple').show();  
            }
        }else if($('#booking_type').val() === '1'){  
            $('#action_cancel_multiple').show();
        }else if($('#booking_type').val() === '3' || $('#booking_type').val() === '4' || $('#booking_type').val() === '5'){ 
            $('#action_noavailable_multiple').show(); 
        }
    }
 

    $('#booking_type').on('change', function () {   //filter by booking type
        BookingsDatatable();  
        getMultipleActions();
    });

    
    $('#book_date').on('change', function () {   //filter by booking date range
        BookingsDatatable();   
    });

 
    function adjustButtons(status, is_archived) {  //adjust buttons on table depending on the booking status

        if(is_archived === '1'){
            return '<button class="btn btn-success btn-sm" id="view" title="View"><li class = "fas fa-align-justify"></li></button>';
        }
        if (status == 'Pending') {  
            if($('#role').text() == '1'){
                return '<button class="btn btn-primary btn-sm" id="edit" ><li class = "fas fa-edit"></li></button> <button class="btn btn-success btn-sm" id="view" title="View"><li class = "fas fa-align-justify"></li></button>   ';
            }else{
                return '<button class="btn btn-primary btn-sm" id="edit" ><li class = "fas fa-edit"></li></button> <button class="btn btn-success btn-sm" id="view" title="View"><li class = "fas fa-align-justify"></li></button>  ';
            }
            
        }else if (status == 'Approved') {  
            return '<button class="btn btn-primary btn-sm" id="edit" ><li class = "fas fa-edit"></li></button> <button class="btn btn-success btn-sm" id="view" title="View"><li class = "fas fa-align-justify"></li></button>';
        }else{
            return '<button class="btn btn-success btn-sm" id="view" title="View"><li class = "fas fa-align-justify"></li></button>';
        } 
    }


    

    $("#cancelbookingmodal").on('click', function () { //hide modal
        $('#add_new_booking').modal('hide');
    });
 


    $("#add_booking").on('click', function () { // reset data when clicking book room button
        $('#add_booking_title').text('Book Room');
        resetData(); 
        $('#recurring_row').hide("fast", function () { });
        $('#date_row').show("fast", function () { });   
        $('#booking_option').val('Does Not Repeat');
        $('#booking_option').prop('disabled', false);      
    });



    $('#action_approve_multiple').on('click', function() { 
        updateStatusMultipleItems(1,'Would you like to confirm the approval of the selected bookings?');  
    });
 
    $('#action_decline_multiple').on('click', function() {  
        updateStatusMultipleItems(3,'Would you like to decline the approval of the selected bookings?');
    });
 
    $('#action_cancel_multiple').on('click', function() {  
        updateStatusMultipleItems(4,'Would you like to cancel the approval of the selected bookings?');
    });

    function updateStatusMultipleItems(status, message){
        var ids = [];
        bookings.$('input[type="checkbox"]').each(function(index){ 
            if(this.checked){  
                ids.push(bookings.rows(index).data()[0].id);
            } 
        }); 
        if(ids.length === 0){ 
            noti('Please select row/s to apply an action.', 'Error!', 'error');
        }else{
            Swal.fire({
                title: message, 
                icon: 'question',
                showCancelButton: true, 
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    update_multiple_status_booking(ids, status); 
                }
            })
        }
    }

    
    function update_multiple_status_booking(ids, status) { // booking status actions
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/bookings/updateMultipleStatusBooking',
            type: "POST",
            data: {
                ids: ids,
                status: status,
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
                    noti_success(dataResult.message, 'Success!', 'success');
                } else { 
                    noti_success(dataResult.message, 'Error!', 'error');
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
    }
    
    function resetData(){ // reset modal data
        
        $('#booking_date').prop('disabled', false);
        $('#booking_start_time').prop('disabled', false);
        $('#booking_end_time').prop('disabled', false);
        $('#booking_room').prop('disabled', false);
        $('#booking_date').val('');
        $('#booking_id').val('');
        $('#booking_start_time').val(''); 
        $('#booking_end_time').val(''); 
        $('#booking_purpose').val(''); 
        $('#booking_room').empty();
        $('#booking_room').append('<option value="" selected="selected" disabled>- Select Room -</option>');   
        $('#booking_type_menu').val('');
        $('#external').hide("fast", function () { });
        $('#internal').hide("fast", function () { });
        $('#internal_menu').val(''); 
        $('#internal_menu_others').val(''); 
        $('#booking_engagement_number').val(''); 
        $('#booking_client_name').val(''); 
        $('#prospective_input').val(''); 
        $('#booking_engagement_number').prop('disabled', true);
        $('input[name="radiobutton_client_type"]:radio').prop("checked", false);
        $('#booking_agenda').val(''); 
        $('.checkbox_it_req').prop( "checked", false );
        $('#it_requirements_others').val(''); 
        $('#booking_date').attr("required", true);
        $('#recurring_end_date').attr("required", false);
        
        $('#weekdays').empty();
        $('#weekdays').weekdays([]);
        $('#recurring_end_date').val('');
    }
 



    $('#booking_date').on('change', function () {   //available rooms filter by date  
        getAvailableRooms();
    });

    $('#booking_start_time').on('change', function () {   //available rooms filter by time
        getAvailableRooms();
    });

    $('#booking_end_time').on('change', function () {    //available rooms filter by time 
        getAvailableRooms();
    });



    $('#booking_option').on('change', function() {  // show and hide date and recurring date rows
        if($(this).val() == 'Recurring'){ 
            $('#weekdays').empty();
            $('#weekdays').weekdays([]);
            $('#recurring_row').show("fast", function () { });
            $('#date_row').hide("fast", function () { });
            $('#recurring_end_date').val('');
            $('#recurring_end_date').attr("required", true);
            $('#booking_date').attr("required",false);
            $('#booking_room').empty();
            $('#booking_room').append('<option value="" selected="selected" disabled>- Select Room -</option>');   
        }else{
            $('#booking_date').val('');
            $('#booking_date').attr("required", true);
            $('#recurring_end_date').attr("required", false);
            $('#recurring_row').hide("fast", function () { });
            $('#date_row').show("fast", function () { }); 
        }  
        getAvailableRooms();
    });



    function getAvailableRooms(){ // call api for available rooms

        var booking_id     = $('#booking_id').val();
        var booking_option = $('#booking_option').val();
        var date           = $('#booking_date').val();
        var start_time     = $('#booking_start_time').val();
        var end_time       = $('#booking_end_time').val();
 
 
        $('#booking_room').empty();
        $('#booking_room').append('<option value="" selected="selected" disabled>- Select Room -</option>'); 

        if(booking_option == 'Does Not Repeat'){
            if(date == '')   { return;}
        } 
        if(start_time == '') { return;}
        if(end_time == '')   { return;}

        start_time = date + ' ' + start_time + ':00'
        end_time   = date + ' ' + end_time + ':00' 

        $.ajax({
            url: 'bookings/getAvailableRooms',
            type: "GET", 
            cache: false,
            data: {
                start_time:     start_time,
                end_time:       end_time,
                booking_option: booking_option,
                booking_id:     booking_id
            },
            beforeSend: function () {
                $('#modal_process').show();
            },
            complete: function () {
                $('#modal_process').hide();
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);
                $('#booking_room').empty();
                $('#booking_room').append('<option value="" selected="selected" disabled>- Select Room -</option>'); 

                if (dataResult.statusCode == 200) {  
  
                    for (var i = 0; i < dataResult.data.length; i++) {
                        $('#booking_room').append('<option value="' + dataResult.data[i]['room_id'] + '">' + dataResult.data[i]['room_name'] + ' (' + dataResult.data[i]['max_seats'] + ' seats) </option>');
                    }

                }else if (dataResult.statusCode == 202) { 
                    $('#modal_process').hide();

                    var array_message = ''; 
                    $.each(dataResult.message, function( index, value ) { //LOOP VALIDATION ERRORS
                        array_message =  value;
                        return false;
                    });
                      
                    noti(array_message, 'Error', 'error');

                }  else {
                    noti(dataResult.message, 'Error!', 'error');  
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });

        
    }
 
    // noti('sadsda', 'sadsda', 'warning')
    function noti(message, title, icons) { // alert message
        Swal.fire({
            icon : icons,
            title: title, 
            text : message 
        });  
    } 

    function noti_success(message, title, icons){
        Swal.fire({
            icon             : icons,
            title            : title, 
            text             : message,
            confirmButtonText: 'Ok', 
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                BookingsDatatable();  
            } else {
                BookingsDatatable();  
            }
        }) 
    }


    $('#add_booking_form').on('keydown', function (event) { // form preventer enter keycode
        if (event.keyCode == 13) { 
            event.preventDefault();
            return false;
        }
    });
 
    
    var is_rebook = false;
    $('#add_booking_form').on('submit', function (event) { //add and edit booking action
        event.preventDefault();  
        var formData = new FormData(this); 
 
        var participants_id    = []; 
        var participants_email = [];
        $('#ul_pariticipants li').each(function() {  // get all participants email and id and store it on array
            participants_id.push(this.id)
            participants_email.push($(this).text())  
        });  
        if(participants_email.length == 0 || participants_id.length == 0) {
            noti('Please input participants', 'Error!', 'error'); return;
        }

        // AGENDA
        var agenda = []; 
        $('#ul_booking_agenda li').each(function() {  // get all agenda and store it on array
            agenda.push(this.id)  
        });  
        
        // IT
        var it_requirements = []; 
        $('#ul_it_requirements li').each(function() {  // get all it req and store it on array
            it_requirements.push(this.id)  
        });   


        // RECURRING DAYS    
        var dates = []; 
        if($('#booking_option').val() == 'Recurring'){ 
            var date_now = moment();  
            var days = $('#weekdays').selectedDays(); 
            $.each(days, function( index, value ) { //LOOP VALIDATION ERRORS    
                $.merge(dates, getAllRecurringDatesPerDay($('#recurring_end_date').val(),value));
            });  
        } 

        formData.append('is_rebook', is_rebook);  // add all arrays to formdata
        formData.append('participants_id', participants_id);  // add all arrays to formdata
        formData.append('participants_email', participants_email); 
        formData.append('it_requirements', it_requirements);  
        formData.append('agenda', agenda);  
        formData.append('recurring_dates', dates.sort());  // recurring dates

        var api_url = '';
        $('#booking_id').val() == '' ? api_url = '/bookings/addBooking' :  api_url = '/bookings/editBooking';
        
        $.ajax({
            url: api_url,
            type: "POST",
            data: formData,
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
                    noti_success(dataResult.message, 'Success!', 'success'); 
                    resetData();
                    $('#add_new_booking').modal('hide'); 

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
                    getAvailableRooms();
                }
            },
            error: function (e) {
                noti('An error occured, please try again.', 'Error!', 'error');
            }
        });
    });


         

    $('#booking_type_menu').on('change', function () {   // show and hide external/internal rows
        $('#external').hide("fast", function () { });
        $('#internal').hide("fast", function () { });
        if($(this).val() == 'External'){ 
            $('#external').show("fast", function () { });
        }else if($(this).val() == 'Internal'){ 
            $('#internal').show("fast", function () { });
        }

    });

    

    $('#internal_menu').on('change', function () {    // show and hide internal others input
        $('#internal_menu_others_row').hide("fast", function () { });
        $('#internal_menu_others_row').hide("fast", function () { });
        if($(this).val() == 'Others'){ 
            $('#internal_menu_others_row').show("fast", function () { });
        }
    });



    $('input[name="radiobutton_client_type"]:radio').on('click', function() {   // enable and disable client number input
        if($(this).val() == 'Existing'){
            $('#booking_engagement_number').prop('disabled', false);
        }else{ 
            $('#booking_engagement_number').prop('disabled', true);
        }
    });

 

    
    
    $("#BookingdataTable").on('click', '#view', function () { // view booking info (modal)
        var currentRow = $(this).closest("tr");
        var id = currentRow.find("td:eq(0)").text();  
        var is_archived = currentRow.find("td:eq(12)").text();  
 
 
        $.ajax({
            url: 'bookings/getBookingById/' + id,
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
                    
                    document.getElementById("booking_number").innerHTML = dataResult.message.booking_number;
                    document.getElementById("title").innerHTML          = 'Booking Number[' + dataResult.message.booking_number +']';
                    document.getElementById("booked_by").innerHTML      = dataResult.message.added_by;
                    document.getElementById("venue").innerHTML          = dataResult.message.room_name;
                    document.getElementById("description").innerHTML    = dataResult.message.purpose;
                    document.getElementById("start").innerHTML          = dataResult.message.start_time;
                    document.getElementById("end").innerHTML            = dataResult.message.end_time;
                    
                    if(dataResult.message.participants){ // GET ALL PARTICIPANTS TAGS
                        $('#view_participants').empty();    
                        $.each(dataResult.message.participants, function( index, value ) {  
                            if(value.user){
                                $('#view_participants').append('<p>' + value.user.first_name + ' ' + value.user.last_name + '</p>');    
                            }else{
                                $('#view_participants').append('<p>' + value.guest_email + ' (guest)</p>');    
                            }
                        });  
                    }
                    
                    document.getElementById("view_mode").innerHTML = dataResult.message.mode;
                    document.getElementById("view_type").innerHTML = dataResult.message.type;
                    if(dataResult.message.type == 'Internal'){ // SHOW/HIDE EXTERNAL or INTERNAL
                        $('#external_row1, #external_row2').hide();
                        $('#internal_row').show();
                    }else{
                        $('#internal_row').hide();
                        $('#external_row1, #external_row2').show();
                    }
                    document.getElementById("view_internal_menu").innerHTML         = dataResult.message.internal_option + '<br>' + dataResult.message.internal_option_others; // INTERNAL
                    
                    document.getElementById("view_external_clientnumber").innerHTML = dataResult.message.client_number; // EXTERNAL
                    document.getElementById("view_external_clientname").innerHTML   = dataResult.message.client_name;
                    document.getElementById("view_external_clienttype").innerHTML   = dataResult.message.client_type + '<br>' + dataResult.message.client_type_others;
                    
                     
                    $('#view_agenda').empty();    
                    if(dataResult.message.agenda){  // GET ALL AGENDA TAGS
                        var arr = dataResult.message.agenda.split(',');
                        $.each(arr, function( index, value ) {  
                            $('#view_agenda').append('<p>' + value + '</p>');     
                        });   
                    } 
 
                    $('#view_it_req').empty();    
                    if(dataResult.message.it_requirements){  // GET ALL IT REQUIREMENTS TAGS
                        var arr = dataResult.message.it_requirements.split(',');
                        $.each(arr, function( index, value ) {  
                            $('#view_it_req').append('<p>' + value + '</p>');    
                        });   
                    } 
 
                    document.getElementById("booking_status").innerHTML  = dataResult.message.status;
                    document.getElementById("booking_view_id").innerHTML = dataResult.message.id;
                    
                    $('#approve_booking_button').hide();
                    $('#decline_booking_button').hide();
                    $('#cancel_booking_button').hide();
                    if(dataResult.message.status == 'Pending'){  // SHOW HIDE BUTTONS
                        if($('#role').text() == '1'){
                            $('#approve_booking_button').show();
                            $('#decline_booking_button').show();
                        }else{ 
                            $('#cancel_booking_button').show();
                        }
                    
                    }else if (dataResult.message.status == 'Approved') {  
                        $('#cancel_booking_button').show();
                    } else if (dataResult.message.status == 'Canceled') {  
                        $('#rebook_booking_button').show();
                    } 

                    if(is_archived == '1'){ 
                        $('#approve_booking_button').hide();
                        $('#decline_booking_button').hide();
                        $('#cancel_booking_button').hide();
                    }
                    $('#calendarinfomodal').modal('show');
                    
                }else if (dataResult.statusCode == 201) {  
                    noti(dataResult.message, 'Error!', 'error'); 
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
    });

 
    $("#cancelcalendarmodal").on('click', function () { //hide modal
        $('#calendarinfomodal').modal('hide');
    });
 
 

    
    $('#cancel_booking_button').on('click', function(){  // cancel booking

        var title = 'Are you sure you want to cancel your booking in ' + $('#venue').text() + '?';
        if($('#role').text() == '1'){
            title = 'Are you sure you want to cancel the booking of <br> '+  $('#booked_by').text() + ' <br> for ' + $('#venue').text() + '?'
        }
        Swal.fire({
            title:              title, 
            icon:               'warning',
            showCancelButton:   true, 
            cancelButtonColor:  '#d33',
            confirmButtonText:  'Yes'
        }).then((result) => {
            if (result.isConfirmed) { 
                update_status_booking($('#booking_view_id').text(), '4'); 
            }
        })
 
    });
 



    
    $('#rebook_booking_button').on('click', function(){  // cancel booking
        $('#calendarinfomodal').modal('hide'); 
        Swal.fire({
            title:             'Would you like to confirm the rebooking of booking number: ' + $('#booking_number').text() + '?', 
            icon:              'warning',
            showCancelButton:  true, 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {  
                resetData();
                $('#recurring_row').hide("fast", function () { });
                $('#date_row').show("fast", function () { });   
                $('#booking_option').val('Does Not Repeat');
                $('#booking_option').prop('disabled', true);
                $('#add_booking_title').text('Rebook Booking - ' + $('#title').text());
                is_rebook = true;
                var id = $('#booking_view_id').text(); 
                showEditData(id); 
            }
        })
      
      
 
    });



    function getTimeformat(date){  // get time format
        const d     = new Date(date);
        let hour    = (d.getHours()).toString().padStart(2, "0");
        let minutes = (d.getMinutes()).toString().padStart(2, "0");
        return  hour + ':' + minutes;
    }

    function getDateFormat(date){ // get date format
        const d     = new Date(date);
        let month   = (d.getMonth() + 1).toString().padStart(2, "0")
        let year    = d.getFullYear();
        let day     = (d.getDate()).toString().padStart(2, "0"); 
        return year + '-' +  month + '-' + day;
    }

    

 
    $("#BookingdataTable").on('click', '#edit', function () { // edit view info (modal)
        resetData();
        $('#recurring_row').hide("fast", function () { });
        $('#date_row').show("fast", function () { });   
        $('#booking_option').val('Does Not Repeat');
        $('#booking_option').prop('disabled', true);
        $('#add_booking_title').text('Edit Booking');
        is_rebook = false;
        var currentRow = $(this).closest("tr");
        var id = currentRow.find("td:eq(0)").text();   
        showEditData(id);
       
    });

    

    function showEditData(id){ 
        $.ajax({
            url: 'bookings/getBookingById/' + id,
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
   
                    if(dataResult.message.status == 'Pending' || dataResult.message.status == 'Canceled'){ // DISABLE EDIT IF ALREADY APPROVED
                        $('#booking_date').prop('disabled', false);
                        $('#booking_start_time').prop('disabled', false);
                        $('#booking_end_time').prop('disabled', false);
                        $('#booking_room').prop('disabled', false);
                    }else{
                        $('#booking_date').prop('disabled', true);
                        $('#booking_start_time').prop('disabled', true);
                        $('#booking_end_time').prop('disabled', true);
                        $('#booking_room').prop('disabled', true);
                    }
                     

                    $('#booking_id').val(dataResult.message.id);
                    $('#booking_status_input').val(dataResult.message.status);
                    $('#booking_date').val(getDateFormat(dataResult.message.start_time));
                    $('#booking_start_time').val(getTimeformat(dataResult.message.start_time));
                    $('#booking_end_time').val(getTimeformat(dataResult.message.end_time));

                    $('#booking_room').empty();  
                    $('#booking_room').append('<option value=""  disabled>- Select Room -</option>'); 
                    $('#booking_room').append('<option value="' + dataResult.message.room_id + '"selected="selected"> ' + dataResult.message.room_name + '</option>'); // APPEND ROOM ID/NAME


                    if(dataResult.message.participants){ //GET ALL PARTCIPANTS
                        $('#view_participants').empty();    
                        tags_participants.length = 0;
                        $.each(dataResult.message.participants, function( index, value ) { 
                            if(value.user){
                                $('#view_participants').append('<p>' + value.user.first_name + ' ' + value.user.last_name + '</p>');    
                                tags_participants.push(value.user.email);
                            }else{
                                $('#view_participants').append('<p>' + value.guest_email + ' (guest)</p>');      
                                tags_participants.push(value.guest_email);
                            }
                        });  
                        createTag();
                    } 
                      
                    $("input[name=radio_mode][value='" + dataResult.message.mode + "']").prop("checked",true); // CHECK RADIO BUTTON (MODE)

                    $('#booking_type_menu').val(dataResult.message.type);
                    
                    if(dataResult.message.type == 'Internal'){  // SHOW AND HIDE INTERNAL AND EXTERNAL TYPE
                        $('#internal').show("fast", function () { });
                        $('#external').hide("fast", function () { }); 
                    }else if(dataResult.message.type == 'External'){ 
                        $('#external').show("fast", function () { });
                        $('#internal').hide("fast", function () { }); 
                    }


                    $('#internal_menu').val(dataResult.message.internal_option); // UNDER INTERNAL
                    (dataResult.message.internal_option == 'Others') ?  $('#internal_menu_others_row').show() : $('#internal_menu_others_row').hide();
                    $('#internal_menu_others').val(dataResult.message.internal_option_others); 


                    $('#booking_engagement_number').val(dataResult.message.client_number); // UNDER EXTERNAL
                    $("input[name=radiobutton_client_type][value='" + dataResult.message.client_type + "']").prop("checked",true); 
                    (dataResult.message.client_type == 'Existing') ?  $('#booking_engagement_number').prop('disabled', false) : $('#booking_engagement_number').prop('disabled', true);
                    $('#booking_client_name').val(dataResult.message.client_name);
                    

                    $('#booking_purpose').val(dataResult.message.purpose)
                     

                    $('#view_agenda').empty();    
                    if(dataResult.message.agenda){ // GET ALL AGENDA TAGS
                        var arr = dataResult.message.agenda.split(',');
                        tags_agenda.length = 0;
                        $.each(arr, function( index, value ) {  
                            $('#view_agenda').append('<p>' + value + '</p>');   
                            tags_agenda.push(value);  
                        });   
                    } 
 
                    $('#view_it_req').empty();    
                    if(dataResult.message.it_requirements){  // GET ALL IT REQUIREMENTS TAGS
                        var arr = dataResult.message.it_requirements.split(',');
                        tags_it_req.length = 0;
                        $.each(arr, function( index, value ) {  
                            $('#view_it_req').append('<p>' + value + '</p>'); 
                            tags_it_req.push(value);  
                        });      
                    }  
                    
                    $('#approve_booking_button').hide(); 
                    $('#decline_booking_button').hide();
                    $('#cancel_booking_button').hide();
                     

                    $('#add_new_booking').modal('show');
                    
                }else if (dataResult.statusCode == 201) {  
                    noti(dataResult.message, 'Error!', 'error'); 
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });
    }
 


    

    function getAllRecurringDatesPerDay(book_end_date, type_of_day){ // get all dates in requested days until end date
 
        var dates    = []; 
        var today    = moment(); 
        var end_date = moment(book_end_date);  

        var day      = moment() 
            .startOf('date')
            .day(type_of_day);
 
        while(end_date.format("YYYY-MM-DD") >= day.format("YYYY-MM-DD")){   
            if (day.format("YYYY-MM-DD") >= today.format("YYYY-MM-DD")) dates.push(day.format("YYYY-MM-DD")); 
            day.add(7,'d');
        }

        return dates; 
    }

 

});
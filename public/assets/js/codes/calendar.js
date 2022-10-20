$(document).ready(function () {

    getAllCalendarRooms();
    function getAllCalendarRooms(){
 

        $.ajax({
            url: 'calendar/getAllCalendarRooms',
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

                    $('#rooms').empty();
                    $('#rooms').append('<option value="" selected="selected">- All Rooms -</option>');   
                    for (var i = 0; i < dataResult.data.length; i++) {
                        $('#rooms').append('<option value="' + dataResult.data[i]['room_id'] + '">' + dataResult.data[i]['room_name'] + '</option>');
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
                    $('#booking_room').empty();
                    $('#booking_room').append('<option value="" selected="selected" disabled>- Select Room -</option>');  
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });

        
    }



    $('#rooms').on('change', function () {    
        $('#calendar_div').empty();
        $('#calendar_div').append('<div id="calendar"></div>');
        getCalendarInfo();
    });
    
    $('#mybookings').on('change', function () {    
        $('#calendar_div').empty();
        $('#calendar_div').append('<div id="calendar"></div>');
        getCalendarInfo();
    });

    





    getCalendarInfo();
    function getCalendarInfo(){
        $.ajax({
            url: '/calendar/getCalendarInfo',
            type: "GET", 
            cache: false, 
            data: {
                room_id:  $('#rooms').val(),
                mybookings:  $('#mybookings').val()
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

                if (dataResult.statusCode == 201) { // ERROR

                    noti(dataResult.message, 'Error!', 'error');

                } else if (dataResult.statusCode == 200) { // SUCCESS! 
 
 

                
                    var booking = dataResult.message 

                    $('#calendar').fullCalendar({
                        header: {
                            left: 'prev, next',
                            // left: 'prev, next today',
                            center: 'title',
                            right: '  ' 
                            // right: 'month, agendaWeek, agendaDay', room

                        },
                        events: booking,
                        selectable: true,
                        selectHelper: true,
                        select: function(start, end) {
                            const mon = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                            var date = start._d;
                            const d = new Date(date);
                            let month = mon[d.getMonth()];
                            let year = d.getFullYear();
                            let day = d.getDate();
                            let hour = d.getHours();
                            let minutes = d.getMinutes();
                            var bookingDate = month + ' ' + day + ', ' + year + ' 12:00 AM';
                        }, 
                        // editable: false,
                        eventClick: function(event) {
                            const mon = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                            var date = event.start;
 
                            const d = new Date(date);
                            let month = mon[d.getMonth()];
                            let year = d.getFullYear();
                            let day = d.getDate();
                            var hour = d.getHours();
                            let minutes = d.getMinutes();
                            var meridiem = hour >= 12 ? "PM" : "AM";
                            var bookingDateStart = month + ' ' + day + ', ' + year + ' ' + hour + ':' + minutes + ' ' + meridiem;

                            document.getElementById("title").innerHTML       = 'Booking Number[' + event.booking_number +']';
                            document.getElementById("booked_by").innerHTML   = event.added_by;
                            document.getElementById("venue").innerHTML       = event.room_name;
                            document.getElementById("description").innerHTML = event.purpose;
                            document.getElementById("start").innerHTML       = event.start_time;
                            document.getElementById("end").innerHTML         = event.end_time;
                            
                            if(event.participants){ 
                                $('#view_participants').empty();   
                                $.each(event.participants, function( index, value ) { //LOOP VALIDATION ERRORS  
                                    if(value.user){
                                        $('#view_participants').append('<p>' + value.user.first_name + ' ' + value.user.last_name + '</p>');   
                                    }else{
                                        $('#view_participants').append('<p>' + value.guest_email + ' (guest)</p>');   
                                    }  
                                });  
                            }
                            
                            document.getElementById("view_mode").innerHTML = event.mode;
                            document.getElementById("view_type").innerHTML = event.type;
                            if(event.type == 'Internal'){
                                $('#external_row1, #external_row2').hide();
                                $('#internal_row').show();
                            }else{
                                $('#internal_row').hide();
                                $('#external_row1, #external_row2').show();
                            }
                            document.getElementById("view_internal_menu").innerHTML         = event.internal_option + ', ' + event.internal_option_others;
                            
                            document.getElementById("view_external_clientnumber").innerHTML = event.client_number;
                            document.getElementById("view_external_clientname").innerHTML   = event.client_name;
                            document.getElementById("view_external_clienttype").innerHTML   = event.client_type + ', ' + event.client_type_others;
                            
                            // document.getElementById("view_agenda").innerHTML = event.agenda;
                            // document.getElementById("view_it_req").innerHTML = event.it_requirements + ', ' + event.it_requirements_others;

                            $('#view_agenda').empty();    
                            if(event.agenda){ 
                                var arr = event.agenda.split(',');
                                $.each(arr, function( index, value ) {  
                                    $('#view_agenda').append('<p>' + value + '</p>');     
                                });   
                            } 
        
                            $('#view_it_req').empty();    
                            if(event.it_requirements){ 
                                var arr = event.it_requirements.split(',');
                                $.each(arr, function( index, value ) {  
                                    $('#view_it_req').append('<p>' + value + '</p>');    
                                });    
                            } 
 
                            document.getElementById("booking_status").innerHTML = event.status; 

                            // document.getElementById("id").value = event.id;  
                            $('#calendarinfomodal').modal('show'); 
                        },
                        timeFormat: "hh:mm A",
                    })

 
                }

            },
            error: function (e) {
                $('#modal_process').hide(); 
                noti('An error occured, please try again.', 'Error!', 'error');
            }
        });
    }
     

    // $('body').on('click', 'button.fc-prev-button', function () {
    //     var tglCurrent = $('#calendar').fullCalendar('getDate');
    //     var date = new Date(tglCurrent); 
    //     var year = date.getFullYear();
    //     var month = date.getMonth();
    //     alert('Year is '+year+' Month is '+ month);
    // });
    
    // $('body').on('click', 'button.fc-next-button', function () {
    //     var tglCurrent = $('#calendar').fullCalendar('getDate');
    //     var date = new Date(tglCurrent); 
    //     var year = date.getFullYear();
    //     var month = date.getMonth();
    //     alert('Year is '+year+' Month is '+ month);
    // });

    
    $("#cancelcalendarmodal").on('click', function () { 
        $('#calendarinfomodal').modal('hide');
    });
 


    function noti(message, title, icons) {
        Swal.fire({
            icon: icons,
            title: title, 
            text: message 
        })

    }
     


    
});

$(document).ready(function(){ 
 
    var date_range = moment(); //Get the current date 
    $('input[name="daterange"]').daterangepicker({ 
      locale: {
        format: 'YYYY-MM-DD'
      },
      startDate: date_range.format("YYYY-MM-DD"),
      endDate: date_range.add(3,'month').format("YYYY-MM-DD")
    });
   
    var date1;
    var date2;
    getDates();
    function getDates(){ 
      var dates = $('#daterange').val().split(" - ");
      date1 = dates[0] + ' 00:00:00';
      date2 = dates[1] + ' 23:59:59';
    }

    // var date = moment(); //Get the current date 
    // $('#book_date').val(date.format("YYYY-MM-DD"));


    $('#daterange').on('change', function () {   
      getDates();
      $('#BookingInfoChart_container').empty();
      $('#MostBookedRoomChart_container').empty();
      $('#BookingInfoChart_container').append('<canvas id="BookingInfoChart"></canvas>');
      $('#MostBookedRoomChart_container').append('<canvas id="MostBookedRoomChart"></canvas>'); 
      getCountsData();
      getChartsData(); 
    });
    
    
    // $('#book_date').on('change', function () {  
    //   $('#BookingInfoChart_container').empty();
    //   $('#MostBookedRoomChart_container').empty();
    //   $('#BookingInfoChart_container').append('<canvas id="BookingInfoChart"></canvas>');
    //   $('#MostBookedRoomChart_container').append('<canvas id="MostBookedRoomChart"></canvas>'); 
    //   getCountsData();
    //   getChartsData(); 
    // });
    
    getCountsData();
    
    function getCountsData() {
        $.ajax({
          url: "/dashboard/getCountsData",
          type: "GET",
          cache: false,
          data: { 
              book_date1: date1,
              book_date2: date2
          },
          success: function (dataResult) {
            var datass = JSON.parse(dataResult);    

            $('#total_active_users').text(  datass.data.users); 
            $('#total_rooms').text( datass.data.rooms);
            $('#total_pending_booking').text( datass.data.bookings);
            $('#total_pending_users').text( datass.data.users_pending);

          }
        });
    }


    
    getChartsData();

    function getChartsData() {
        $.ajax({
          url: "/dashboard/getChartData",
          type: "GET",
          cache: false,
          data: { 
              book_date1: date1,
              book_date2: date2
          },
          beforeSend: function () {
              $('#modal_process').show();
          },
          complete: function () {
              $('#modal_process').hide();
          },
          success: function (dataResult) {
            var datass = JSON.parse(dataResult);   
            
            
            // booking info
            var bookinginfolabel = [];
            var bookinginfodata = [];
            $.each(datass.data.booking_info, function (index, val) {
              if(val.status == '1'){
                bookinginfolabel.push('APPROVED');
              }else if(val.status == '2'){
                bookinginfolabel.push('PENDING');
              }if(val.status == '3'){
                bookinginfolabel.push('DECLINED');
              }if(val.status == '4'){
                bookinginfolabel.push('CANCELED');
              }
              bookinginfodata.push(val.count);  
            });
            BookingInfoChart(bookinginfolabel, bookinginfodata);
 


            
            // Most booked room
            var mostbookedroomlabel = [];
            var mostbookedroomdata = [];
            $.each(datass.data.most_booked_room, function (index, val) {
              mostbookedroomlabel.push(val.room.name); 
              mostbookedroomdata.push(val.count);   
            }); 
            
            MostBookedRoomChart(mostbookedroomlabel, mostbookedroomdata);

          }
        });
    }

    

    function BookingInfoChart(bookinginfolabel, bookinginfodata)
    {
        new Chart(
            document.getElementById('BookingInfoChart'),{
                type: 'pie',
                data: {
                    labels: bookinginfolabel,
                    datasets: [{
                        label: '# of Entries',
                        data: bookinginfodata,
                        backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        '#1cc88a'
                        ], 
                    }]
                },
            }
        );
    }

 

    function MostBookedRoomChart(bookinginfolabel, bookinginfodata)
    {
        new Chart(
            document.getElementById('MostBookedRoomChart'),{
                type: 'bar',
                data: {
                    labels: bookinginfolabel,
                    datasets: [{
                        label: 'Most Booked Room',
                        data: bookinginfodata,
                        backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(255, 205, 250)',
                        'rgb(255, 20, 132)',
                        ],
                        hoverOffset: 4 
                    }]
                },
                options: {
                  maintainAspectRatio: false,
                  layout: {
                    padding: {
                      left: 10,
                      right: 25,
                      top: 25,
                      bottom: 0
                    }
                  },
                  scales: {
                    xAxes: [{
                      time: {
                        unit: 'date'
                      },
                      gridLines: {
                        display: false,
                        drawBorder: false
                      },
                      ticks: {
                        maxTicksLimit: 7
                      }
                    }],
                    yAxes: [{
                      ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                          return number_format(value);
                        }
                      },
                      gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                      }
                    }],
                  },
                  legend: {
                    display: false
                  },
                  tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                      label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ':' + number_format(tooltipItem.yLabel);
                      }
                    }
                  }
                }
            }
        );
    }

     

});
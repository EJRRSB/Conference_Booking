@extends('layouts.main', ['active' => 'calendar'])
@section('content') 
    <style>
        .id {
            display: none;
        }
    </style>
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}"/>
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">
            Calendar
            <div class="ml-2 float-right">
                <div id="dropdown" class="dropdown float-right"> 
                    <select id="rooms" name="rooms" class="custom-select custom-select-sm form-control form-control-sm" style="width: 150px; margin-right: 10px; float:right;">
                        <option value="" selected="selected">-----</option>
                    </select>
                </div>
                <div id="dropdown" class="dropdown float-right"> 
                    <select id="mybookings" name="mybookings" class="custom-select custom-select-sm form-control form-control-sm" style="width: 150px; margin-right: 10px; float:right;">
                        <option value="" selected="selected">-All Bookings-</option>
                        <option value="{{ auth()->user()->id }}">My Bookings</option> 
                    </select>
                </div>
            </div>
        </h1>
        <div class="card shadow mb-4 p-5" id="calendar_div"> 
            <div id="calendar"></div>
        </div>

    </div>  




    

    @section('scripts') 
        <!-- DATATABLE -->  

         

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
        
        <!-- <script src=" {{ asset('assets/js/core/jquery.min.js') }}"></script> -->
        <script src=" {{ asset('assets/js/core/popper.min.js') }}"></script>
        <script src=" {{ asset('assets/js/core/bootstrap-material-design.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/moment.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/jquery.validate.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/jquery.bootstrap-wizard.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/bootstrap-selectpicker.js') }}"></script>v
        <script src=" {{ asset('assets/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/bootstrap-tagsinput.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/jasny-bootstrap.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/jquery-jvectormap.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/nouislider.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <script src=" {{ asset('assets/js/plugins/arrive.min.js') }}"></script> 
        <script src=" {{ asset('assets/js/plugins/chartist.min.js') }}"></script>
        <script src=" {{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script> 

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>


        <script src="{{ asset('assets/js/codes/calendar.js') }}"></script> 
         
    @endsection
@endsection
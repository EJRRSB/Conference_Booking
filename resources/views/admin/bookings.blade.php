@extends('layouts.main', ['active' => 'bookings'])
@section('content') 
<style>
    .id, .room_id, .is_archived, .added_by_id {
        display: none;
    } 
    .dot {
        height: 10px;
        width: 10px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
    }
    table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child{
        width: 80px;
    }  
</style>
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}"/>
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet"> 
<link href="{{ asset('assets/css/tags.css') }}" rel="stylesheet"> 
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">

    <!-- Begin Page Content -->
    <div class="container-fluid">

    <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Booking Information</h1> 
        <br>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3"> 
                <h6 class="m-0 font-weight-bold text-primary">List of Bookings</h6>   
            </div>
            <div class="row" style="margin-top: 10px; margin-right: 10px;">
                <div class="col-md-12">
                    
                    <a href="#" class="btn btn-success btn-sm" style="float: right !important;" id="add_booking" data-toggle="modal" data-target="#add_new_booking" data-backdrop="static" data-keyboard="false" >
                        Book Rooms 
                    </a>
                    
                    <a class="btn btn-success btn-sm"  href="#" id="navbarDropdown" style="margin-left: 20px; !important;"
                        role="button" data-toggle="dropdown" aria-haspopup="true" id="actions_booking"
                        aria-expanded="false">
                        Actions
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated--grow-in" style="float:left; margin-left: 20px; !important;"
                        aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" id="action_approve_multiple">Approve Booking</a>
                        <a class="dropdown-item" href="#" id="action_decline_multiple">Declined Booking</a>
                        <a class="dropdown-item" href="#" id="action_cancel_multiple">Cancel Booking</a>
                        <a class="dropdown-item" href="#" id="action_noavailable_multiple" style="display: none;">No Available Action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="action_close_multiple">Close</a>
                    </div>
                    
                    <div class="ml-2 float-right"> 
                        <select id="booking_type" name="booking_type" class="custom-select custom-select-sm form-control form-control-sm" style="width: 150px; margin-right: 10px;">
                            <option value="2" selected="selected">Pending</option>
                            <option value="1">Approved</option> 
                            <option value="3">Declined</option> 
                            <option value="4">Canceled</option> 
                            <option value="5">Archived</option> 
                          </select>
                    </div>
                    
                    <!-- <div class="ml-2 float-right"> 
                          <input type="date" class="form-control form-control-sm" id="book_date" name="book_date">
                    </div> -->
                    <div class="ml-2 float-right" style="float:right;">
                        <div id="dropdown" class="dropdown float-right"> 
                            <input type="text" class="form-control  form-control-sm" name="daterange" id="daterange" style="float:right; width: 200px;"/>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="BookingdataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr> 
                                <th>ID</th>
                                <th>Room ID</th>
                                <th>Room</th>
                                <th>Booking No.</th>
                                <th>Purpose</th>
                                <th>Start Time</th>
                                <th>End Time</th> 
                                <th>Status</th>
                                <th>Added By</th>
                                <th>Approver</th> 
                                <th>Date Filed</th>
                                <th>Actions</th>
                                <th>Archived</th>
                                <th>Added By Id</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr> 
                                <th>ID</th>
                                <th>Room ID</th>
                                <th>Room</th>
                                <th>Booking No.</th>
                                <th>Purpose</th>
                                <th>Start Time</th>
                                <th>End Time</th> 
                                <th>Status</th>
                                <th>Added By</th>
                                <th>Approver</th> 
                                <th>Date Filed</th>
                                <th>Actions</th>
                                <th>Archived</th>
                                <th>Added By Id</th>
                            </tr>
                        </tfoot>
                        <tbody> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div> 


    <p id="user_id" style="display: none;">{{auth()->user()->id}}</p>
    <p id="role" style="display: none;">{{auth()->user()->role}}</p>
    <p id="auth_email" style="display: none;">{{auth()->user()->email}}</p>

    

    @section('scripts') 
        <!-- DATATABLE -->  

        <!-- weekdays  -->
        <link href="{{ asset('assets/js/weekdays/jquery-weekdays.css') }}" rel="stylesheet" /> 
        <script src="{{ asset('assets/js/weekdays/jquery-weekdays.js') }}"></script>
        
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


            
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

        <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
        <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
  
        <script>
            tags_agenda = [ ];
            tags_it_req = [ ];
            tags_participants = [ ];
        </script>
        
        <script src="{{ asset('assets/js/codes/bookings.js') }}"></script> 
        <script src="{{ asset('assets/js/codes/tags/tags.js') }}"></script> 
        <script src="{{ asset('assets/js/codes/tags/it_req_tags.js') }}"></script> 
        <script src="{{ asset('assets/js/codes/tags/agenda_tags.js') }}"></script> 
        
 

    @endsection
@endsection
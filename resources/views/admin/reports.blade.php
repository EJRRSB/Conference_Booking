@extends('layouts.main', ['active' => 'reports'])
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

        <!-- Page Heading -->
        <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4"> -->
        <h1 class="h3 mb-2 text-gray-800">
            Report
            <div class="ml-2 float-right" style="float:right;">
                <div id="dropdown" class="dropdown float-right"> 
                    <input type="text" class="form-control  form-control-sm" name="daterange" id="daterange" style="float:right; width: 200px;"/>
                </div>
            </div>
        </h1>
        <br>
        <!-- </div> -->

        <div class="row" style="justify-content:center;">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4"> 
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Generate Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">List of Pending Bookings</div>
                            </div>
                            <div class="col-auto">   
                                <button class="btn btn-primary btn-circle" id="ListOfPendingBookings">
                                    <i class="fas fa-download fa-sm"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Generate Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">List of Approved Bookings</div>
                            </div>
                            <div class="col-auto">
                                <button  class="btn btn-success btn-circle" id="ListOfApprovedBookings">
                                    <i class="fas fa-download fa-sm"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    GENERATE REPORT</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">List of Declined Bookings</div>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-warning btn-circle" id="ListOfDeclinedBookings">
                                    <i class="fas fa-download fa-sm"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row" style="justify-content:center;">
            <!-- Earnings (Annual) Card Example -->

            <div class="col-xl-4 col-md-6 mb-4"> 
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    Generate Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">List of Archived Bookings</div>
                            </div>
                            <div class="col-auto">   
                                <button class="btn btn-dark btn-circle" id="ListOfArchivedBookings">
                                    <i class="fas fa-download fa-sm"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4"> 
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Generate Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">List of Canceled Bookings</div>
                            </div>
                            <div class="col-auto">   
                                <button class="btn btn-danger btn-circle" id="ListOfCanceledBookings">
                                    <i class="fas fa-download fa-sm"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === '1')
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Generate Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">List of All Active Users</div>
                            </div>
                            <div class="col-auto">
                                <button  class="btn btn-secondary btn-circle" id="ListOfActiveUsers">
                                    <i class="fas fa-download fa-sm"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            @endif 

        </div>

            
        @if(auth()->user()->role === '1')
            <div class="row" style="justify-content:center;"> 
            

                <!-- Earnings (Annual) Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Generate Report</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">List of All Pending Users</div>
                                </div>
                                <div class="col-auto">
                                    <button  class="btn btn-info btn-circle" id="ListOfPendingUsers">
                                        <i class="fas fa-download fa-sm"></i> 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="col-xl-6 col-md-6 mb-4"> 
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Generate Report</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">List of All Rooms</div>
                                </div>
                                <div class="col-auto">  
                                    <button class="btn btn-success btn-circle" id="ListOfAllRooms">
                                        <i class="fas fa-download fa-sm"></i> 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        @endif

    </div>  




    

    @section('scripts') 
        <!-- DATATABLE -->  

         

        
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

        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

        <script src="{{ asset('assets/js/codes/reports.js') }}"></script> 
  

    @endsection
@endsection
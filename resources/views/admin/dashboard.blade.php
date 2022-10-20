@extends('layouts.main', ['active' => 'dashboard'])
@section('content')
    <style>
        
        .id, .room_id {
            display: none;
        } 
    </style>

    <div class="container-fluid">

        <!-- Page Heading -->
        <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4"> -->
        <h1 class="h3 mb-0 text-gray-800">
            Dashboard 
            <div class="ml-2 float-right" style="float:right;">
                <div id="dropdown" class="dropdown float-right"> 
                    <input type="text" class="form-control  form-control-sm" name="daterange" id="daterange" style="float:right; width: 200px;"/>
                </div>
            </div>
        </h1>
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        <!-- <div class="ml-2 float-right"> 
                <input type="date" class="form-control form-control-sm" id="book_date" name="book_date">
        </div> --> 
        <br>
        <!-- </div> -->

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Number of Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_active_users">-</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Number of Total Rooms</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_rooms">-</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total  Booking
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="total_pending_booking">-</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Pending Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_pending_users">-</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Most Booked Room</h6>
                        <div class="dropdown no-arrow">
                            <!-- <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div> -->
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area" id="MostBookedRoomChart_container">
                            <canvas id="MostBookedRoomChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Booking Info</h6>
                        <div class="dropdown no-arrow">
                            <!-- <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a> -->
                            <!-- <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div> -->
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2" id="BookingInfoChart_container">
                            <canvas id="BookingInfoChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small"><br>
                            <!-- <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> PENDING
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> APPROVED
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> DECLINED
                            </span> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- <div class="row">
            <div class="card shadow mb-4 col-xl-12 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List of Booking</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="BookingdataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr> 
                                    <th>ID</th>
                                    <th>Room ID</th>
                                    <th>Room</th>
                                    <th>Purpose</th>
                                    <th>Start Time</th>
                                    <th>End Time</th> 
                                    <th>Status</th>
                                    <th>Added By</th>
                                    <th>Approved By</th>
                                    <th>Declined By</th>
                                    <th>Date Added</th> 
                                </tr>
                            </thead>
                            <tfoot>
                                <tr> 
                                    <th>ID</th>
                                    <th>Room ID</th>
                                    <th>Room</th>
                                    <th>Purpose</th>
                                    <th>Start Time</th>
                                    <th>End Time</th> 
                                    <th>Status</th>
                                    <th>Added By</th>
                                    <th>Approved By</th>
                                    <th>Declined By</th>
                                    <th>Date Added</th> 
                                </tr>
                            </tfoot>
                            <tbody> 
                                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  -->
    </div> 

    @section('scripts') 
        <!-- DATATABLE -->  

            
        <!-- <script src=" {{ asset('assets/js/core/jquery.min.js') }}"></script> -->   
        <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/chart.js/Chart.min.js ') }}"></script>

        <!-- Page level custom scripts -->
        <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script> 
        <script src="{{ asset('assets/js/codes/dashboard.js') }}"></script> 

        

        <script src=" {{ asset('assets/js/plugins/moment.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endsection
    
@endsection
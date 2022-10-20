<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Meeting Room Reservation System</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" >
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>
 

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <!-- <i class="fas fa-dharmachakra" ></i> -->
                    <!-- <img src="img/array-trademark.png" style="height: 30px;">  -->
                </div>
                <!-- <div class="sidebar-brand-text mx-3">MRRS </div> -->
                <div class="sidebar-brand-text mx-3">Meeting Room Reservation </div>
            </a>
  
            <hr class="sidebar-divider my-0">

            <li class="nav-item {{$active == 'dashboard' ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('home') }}" >
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li> 
 
            <hr class="sidebar-divider"> 

            <!-- Heading -->
            <div class="sidebar-heading">
                Components
            </div> 
 
            @if(auth()->user()->role === '1')
                <li class="nav-item {{$active == 'rooms' ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('rooms') }}">
                        <i class="fas fa-money-check"></i>
                        <span>Rooms</span></a>
                </li>
            @endif
 
            <li class="nav-item {{$active == 'bookings' ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('bookings') }}">
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Bookings</span></a>
            </li> 

            <li class="nav-item {{$active == 'calendar' ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('calendar') }}">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Calendar</span></a>
            </li>

            @if(auth()->user()->role === '1')
                <li class="nav-item {{$active == 'user' ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('users') }}" >
                        <i class="fas fa-users"></i>
                        <span>Users</span></a>
                </li> 
            @endif
 
            <li class="nav-item {{$active == 'reports' ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('reports') }}">
                    <i class="fas fa-fw fa-file"></i>
                    <span>Reports</span></a>
            </li> 
 <!-- 
            <li class="nav-item">
                <a class="nav-link active" href="settings.html">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span></a>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

             <!-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                   <!--  <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">1</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">September 27, 2022</div>
                                        <span class="font-weight-bold">Feature unavailable at the moment.</span>
                                    </div>
                                </a>
                                <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a> -->
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li> 

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> {{ auth()->user()->first_name }} {{auth()->user()->last_name }}</span>
                                <!-- <img class="{{ asset('assets/img-profile rounded-circle"
                                    src="img/Elton.jpg') }}"> -->
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a> -->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changepassword_modal" data-backdrop="static" data-keyboard="false" >
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a> 
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-info-circle fa-sm fa-fw mr-2 text-gray-400"></i>
                                    About
                                </a>
                                <a class="dropdown-item" href="{{ asset('about/Meeting Room Reservation System_User Manual_v1.0_20221017.pdf') }}" target="_blank">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    User Manual
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                
                <!-- /.container-fluid -->
 
                <main>
                    @yield('content')
                </main>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <span id="trademark">Your Website -</span></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select Logout below to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>




    @if($active == 'user')
        <!-- Add new Users Modal--> 
        <div class="modal fade" id="add_new_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form method="POST" id="add_user_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold" id="add_user_title">Add New User</h4>
                        </div>
                        <div class="modal-body">

                            <ul class="list-group">
                                <div class="row" id="tran">
                                    <div class="col-md-12"> 
                                        <div class="card-body">  
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label style="font-size: .8rem;">First Name*</label>
                                                    <input type="text" class="form-control form-control-user" id="id" name="id" style="display: none;">
                                                    <input type="text" class="form-control form-control-user" id="first_name" name="first_name"
                                                        required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label style="font-size: .8rem;">Middle Name</label>
                                                    <input type="text" class="form-control form-control-user" id="middle_name" name="middle_name"
                                                          >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label style="font-size: .8rem;">Last Name*</label>
                                                    <input type="text" class="form-control form-control-user" id="last_name" name="last_name"
                                                        required>
                                                </div>
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label style="font-size: .8rem;">User Type*</label>
                                                    <select class="form-control form-control-user" id="role" name="role">
                                                        <option value="2">User</option> 
                                                        <option value="1">Admin</option>
                                                    </select>
                                                </div> 
                                            </div>  
                                            <div class="form-group row"> 
                                                <div class="col-sm-9">
                                                <label style="font-size: .8rem;">Phone Number</label>
                                                    <input type="text" class="form-control form-control-user"
                                                            id="phone_number" name="phone_number"   >
                                                </div> 
                                            </div> 
                                            <div class="form-group row"> 
                                                <div class="col-sm-9">
                                                    <label style="font-size: .8rem;">Email*</label>
                                                    <input type="email" class="form-control form-control-user" id="email" name="email"
                                                         required>
                                                </div> 
                                            </div> 
                                            <button type="submit" class="btn btn-primary btn-sm" style="float:right;">Save</button>
                                            <input type="button" class="btn btn-secondary btn-sm" id="cancelmodal" value="Cancel" style="float:right; margin-right: 3px;"/>
                                            <div class="clearfix"></div> 
                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>




        <div class="modal fade" id="bulk_upload_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <form method="#" id="bulk_upload_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold" id="bulk_upload_title">Bulk Upload</h4>
                        </div>
                        <div class="modal-body">

                            <ul class="list-group"> 
                                <div class="row" id="tran">
                                    <div class="col-md-12"> 
                                        <div class="card-body">  
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                        <label style="font-size: .8rem;">Excel File*</label>
                                                        <input type="file" class="form-control form-control-user" id="file" name="file"
                                                        placeholder="File" required>
                                                </div> 
                                            </div>
                                             
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                    <label style="font-size: .8rem;">Error log</label>
                                                    <div class="form-group"> 
                                                        <textarea class="form-control" rows="10" style="color:red;" id="errorlog"></textarea>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div> 
                                            
                                            <a  href="{{ route('downloadEmployeeBulkUploadTemplate') }}" style="margin-left: 20px;" >Download Template</a>
                                            <button type="submit" class="btn btn-primary btn-sm" style="float:right;">Save</button>
                                            <input type="button" class="btn btn-secondary btn-sm" id="cancelbulkmodal" value="Cancel" style="float:right; margin-right:3px;" />
                                            <div class="clearfix"></div>   
                                            
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif













    
    @if($active == 'rooms')
        <!-- Add new Rooms Modal--> 
        <div class="modal fade" id="add_new_room" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <form method="POST" id="add_room_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold" id="add_room_title">Add New Room</h4>
                        </div>
                        <div class="modal-body">

                            <ul class="list-group">

                                <div class="row" id="tran">
                                    <div class="col-md-12"> 
                                        <div class="card-body">   
                                            <!-- <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">  
                                                    <select id="room_color" name="room_color" class="custom-select  form-control" required>
                                                        <option value="" selected="selected" disabled>- Select Color -</option> 
                                                        <option value="RoyalBlue">RoyalBlue</option> 
                                                        <option value="#Gold">Gold</option> 
                                                        <option value="LightSeaGreen">LightSeaGreen</option> 
                                                        <option value="LightCoral">LightCoral</option> 
                                                        <option value="SeaGreen">SeaGreen</option> 
                                                        <option value="RosyBrown">RosyBrown</option> 
                                                        <option value="Tan">Tan</option> 
                                                        <option value="Yellow">Yellow</option> 
                                                        <option value="Turquoise">Turquoise</option> 
                                                        <option value="SpringGreen">SpringGreen</option> 
                                                    </select> 
                                                </div>
                                            </div> -->
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                    <label style="font-size: .8rem;">Select Room Color</label>
                                                    <input type="color" class="form-control form-control-user" id="room_color" name="room_color" value="#ff0000"
                                                        placeholder="Color" required> 
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                    <label style="font-size: .8rem;">Room Name*</label>
                                                    <input type="text" class="form-control form-control-user" id="room_id" name="room_id" style="display: none;">
                                                    <input type="text" class="form-control form-control-user" id="room_name" name="room_name"   
                                                          required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label style="font-size: .8rem;">Description*</label>
                                                    <!-- <input type="text" class="form-control form-control-user" id="room_description" name="room_description"
                                                    placeholder="Description" rows="5" required> -->
                                                    <textarea class="form-control form-control-user"  id="room_description" name="room_description"
                                                        rows="5" required></textarea>
                                                </div>
                                            </div>    

                                            <hr>
                                            <button type="submit" class="btn btn-primary btn-sm" style="float:right;" >Save</button>
                                            <input type="button" class="btn btn-secondary btn-sm" id="cancelroommodal" value="Cancel" style="float:right; margin-right: 3px;" />
                                            <div class="clearfix"></div> 
                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif






    
    @if($active == 'bookings')
        <!-- Add new Bookings Modal--> 
        <div class="modal fade" id="add_new_booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form method="POST" id="add_booking_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold" id="add_booking_title">Book Room</h4>
                        </div>
                        <div class="modal-body">

                            <ul class="list-group">
                                <div class="row" id="tran">
                                    <div class="col-md-12"> 
                                        <div class="card-body">   
                                            
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">  
                                                    <input type="text" class="form-control form-control-user" id="booking_id" name="booking_id" style="display: none;"> 
                                                    <input type="text" class="form-control form-control-user" id="booking_status_input" name="booking_status_input" style="display: none;"> 
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">  
                                                    <label style="font-size: .8rem;">Booking Option*</label> 
                                                    <select id="booking_option" name="booking_option" class="custom-select  form-control">
                                                        <option value="" selected="selected" disabled>- Select Booking Option -</option> 
                                                        <option value="Does Not Repeat" selected="selected">Does Not Repeat</option> 
                                                        <option value="Recurring">Recurring</option> 
                                                    </select> 
                                                </div>
                                            </div> 
                                            
                                            <div id="recurring_row" style="display: none;">
                                                <div class="form-group row">
                                                    <div class="col-sm-7 mb-3 mb-sm-0"> 
                                                        <label style="font-size: .8rem;">Select Recurring Days*</label> 
                                                        <div id="weekdays"> </div> 
                                                    </div> 
                                                    <div class="col-sm-5 mb-3 mb-sm-0"> 
                                                        <label style="font-size: .8rem;">Recurring End Date*</label>
                                                        <input type="date" class="form-control form-control-user" id="recurring_end_date" name="recurring_end_date"
                                                        placeholder="Date">
                                                    </div>
                                                </div> 
                                            </div>
                                            <div id="date_row">
                                                <div class="form-group row">
                                                    <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                        <label style="font-size: .8rem;">Date*</label>
                                                        <input type="date" class="form-control form-control-user" id="booking_date" name="booking_date"
                                                        placeholder="Date" required="required">
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label style="font-size: .8rem;">Start Time*</label>
                                                        <input type="time" class="form-control form-control-user" id="booking_start_time" name="booking_start_time"
                                                        placeholder="Start Time" required>
                                                        
                                                </div> 
                                                <div class="col-sm-6 mb-3 mb-sm-0"> 
                                                    <label style="font-size: .8rem;">End Time*</label>
                                                    <input type="time" class="form-control form-control-user" id="booking_end_time" name="booking_end_time"
                                                    placeholder="End Time" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">  
                                                    <label style="font-size: .8rem;">Available Rooms*</label>
                                                    <select id="booking_room" name="booking_room" class="custom-select  form-control" required>
                                                        <option value="" selected="selected" disabled>- Select Room -</option> 
                                                    </select> 
                                                </div>  
                                            </div>  
                                            <div class="form-group row">
                                                <div class="col-sm-12">  
                                                        <div class="contents">
                                                            <label style="font-size: .8rem;">Participants* (Press Enter button to add guest participant)</label>
                                                            <ul id="ul_pariticipants">
                                                                <input type="text" id="participants_input" spellcheck="false" list="participant_list"> 
                                                            </ul> 
                                                        
                                                        </div>
                                                        <div class="results">
                                                            <ul id="result_ul"> 
                                                            </ul>
                                                        </div> 
                                                </div>
                                            </div>  
                                            <div class="form-group row">
                                                <div class="col-sm-12"> 
                                                    <label style="font-size: .8rem;">Mode*</label>
                                                    <div style="justify-content: center;">
                                                        <input type="radio" id="radio_mode" name="radio_mode" value="Face to Face" checked/> <label for="Face to face" style="margin-right: 10px;">Face to face</label>
                                                        <input type="radio" id="radio_mode" name="radio_mode" value="Online"/> <label for="Online " style="margin-right: 10px;">Online </label> 
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">  
                                                    <label style="font-size: .8rem;">Type*</label>
                                                    <select id="booking_type_menu" name="booking_type_menu" class="custom-select  form-control" required>
                                                        <option value="" selected="selected" disabled>- Select Type -</option> 
                                                        <option value="Internal">Internal</option> 
                                                        <option value="External">External</option> 
                                                    </select> 
                                                </div>
                                            </div> 
                                            <div id="internal" style="display: none;">
                                                <div class="form-group row">
                                                    <div class="col-sm-12"> 
                                                        <label style="font-size: .8rem;">Menu*</label>
                                                        <select id="internal_menu" name="internal_menu" class="custom-select  form-control">
                                                            <option value="" selected="selected" disabled>- Select Menu -</option> 
                                                            <option value="Training">Training</option> 
                                                            <option value="Group meeting">Group meeting</option> 
                                                            <option value="Partners meeting">Partners meeting</option> 
                                                            <option value="Personnel Committee">Personnel Committee</option> 
                                                            <option value="IT Committee">IT Committee</option> 
                                                            <option value="Finance">Finance</option> 
                                                            <option value="ISQM1/QAU">ISQM1/QAU</option> 
                                                            <option value="Others">Others, please specify</option> 
                                                        </select> 
                                                    </div>
                                                </div> 
                                                <div class="form-group row" id="internal_menu_others_row" name="internal_menu_others_row" style="display: none;">
                                                    <div class="col-sm-6"> 
                                                        <label style="font-size: .8rem;">Others*</label> 
                                                        <!-- <input type="text" class="form-control form-control-user" id="external_menu_others" name="external_menu_others"
                                                        placeholder="Enter Other Menu" required> -->
                                                        <input type="text" id="internal_menu_others" name="internal_menu_others" class="form-control form-control-user"  placeholder="Other Menu"
                                                            /> 
                                                    </div>
                                                </div> 
                                            </div> 
                                            
                                            <div id="external"  style="display: none;">
                                                <div class="form-group row" >
                                                    <div class="col-sm-6"> 
                                                            <label style="font-size: .8rem;">Engagement Number*</label> 
                                                            <input type="text" class="form-control form-control-user" id="booking_engagement_number" name="booking_engagement_number"
                                                            placeholder="Enter Engagement Number" >
                                                    </div> 
                                                    <div class="col-sm-6"> 
                                                        <label style="font-size: .8rem;">Client Type*</label>
                                                        <div style="justify-content: center;">
                                                            <input type="radio" id="radiobutton_client_type" name="radiobutton_client_type" value="Existing"/> <label for="Face to face" style="margin-right: 10px;">Existing</label>
                                                            <input type="radio" id="radiobutton_client_type" name="radiobutton_client_type" value="Prospective"/> <label for="Online " style="margin-right: 10px;">Prospective</label> 
                                                            <!-- <input type="text" id="prospective_input" name="prospective_input" class="form-control form-control-user"   disabled/>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row" >
                                                    <div class="col-sm-12"> 
                                                            <label style="font-size: .8rem;">Client Name*</label> 
                                                            <input type="text" class="form-control form-control-user" id="booking_client_name" name="booking_client_name"
                                                            placeholder="Enter Client Name" >
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="form-group row" >
                                                <div class="col-sm-12"> 
                                                        <label style="font-size: .8rem;">Purpose*</label>
                                                        <!-- <textarea class="form-control form-control-user"  id="booking_purpose" name="booking_purpose"
                                                        placeholder="Purpose" rows="5" required></textarea> -->
                                                        <input type="text" class="form-control form-control-user" id="booking_purpose" name="booking_purpose"
                                                        placeholder="Enter Purpose" required>
                                                </div> 
                                            </div>  
                                            <div class="form-group row">
                                                <div class="col-sm-12"> 
                                                        <!-- <label>Agenda</label>
                                                        <textarea class="form-control form-control-user"  id="booking_agenda" name="booking_agenda"
                                                        placeholder="Enter Agenda" rows="5" required></textarea>  -->
                                                    <div class="booking_agenda_contents">
                                                        <label style="font-size: .8rem;">Agenda (Press Enter button to add new entry)</label>
                                                        <ul id="ul_booking_agenda">
                                                            <input type="text" id="booking_agenda_input" spellcheck="false"  style="width: 100%" maxlength ="50"> 
                                                        </ul>  
                                                    </div> 
                                                </div>
                                            </div>    
                                            <!-- <div class="form-group row">
                                                <div class="col-sm-12"> 
                                                        <label>IT Requirements</label>
                                                        <div id="it_requirements">
                                                            <input type="checkbox" class="checkbox_it_req" value="Speaker"/> <label for="Speaker" style="margin-right: 10px;">Speaker</label>
                                                            <input type="checkbox" class="checkbox_it_req" value="Laptop" /> <label for="Laptop" style="margin-right: 10px;">Laptop</label>
                                                            <input type="checkbox" class="checkbox_it_req" value="Projectors" /> <label for="Projectors" style="margin-right: 10px;">Projectors</label>
                                                            <label for="Others, please specify" value="Others" >Others, please specify:</label>
                                                            <input type="text" id="it_requirements_others" name="it_requirements_others" style="border: 0; outline: 0; background: transparent; border-bottom: 1px solid #d1d3e2; "/> 
                                                        </div>
                                                </div>
                                            </div>    -->
                                            
                                            <div class="form-group row">
                                                <div class="col-sm-12">  
                                                    <div class="it_requirements_contents">
                                                        <label style="font-size: .8rem;">IT Requirements (Press Enter button to add new entry)</label>
                                                        <ul id="ul_it_requirements">
                                                            <input type="text" id="it_requirements_input" spellcheck="false" > 
                                                        </ul>  
                                                    </div> 
                                                </div>
                                            </div>

                                            <hr>
                                            <button type="submit" class="btn btn-primary btn-sm" style="float:right;">Save</button>
                                            <input type="button" class="btn btn-secondary btn-sm" id="cancelbookingmodal" value="Cancel" style="float:right; margin-right:5px;"/>
                                            <div class="clearfix"></div> 
                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif




    
    @if($active == 'calendar' || $active == 'bookings')
        <!-- Add new Bookings Modal--> 
        <div class="modal fade" id="calendarinfomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <!-- <form method="POST" id="add_booking_form">
                    @csrf -->
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold" id="title">Calendar Title</h4>
                    </div>
                    <div class="modal-body">

                        <ul class="list-group">
                            <div class="row" id="tran">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- <h4 class="header-title mb-3">Booking Infomation</h4><br> -->
                
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <thead class="table-light">
                                                    </thead> 
                                                    <tbody>
                                                        <tr>
                                                            <th style="background-color: #F0F8FF;">Venue</th>
                                                            <th id="venue"></th> 
                                                            <th id="booking_number" style="display:none;"></th> 
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Booked By </td>
                                                            <td id="booked_by"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Start </td>
                                                            <td id="start"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">End</td>
                                                            <td id="end"></td>
                                                        </tr> 
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Participants</td>
                                                            <td id="view_participants"></td>
                                                        </tr> 
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Mode</td>
                                                            <td id="view_mode"></td>
                                                        </tr> 
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Type</td>
                                                            <td id="view_type"></td>
                                                        </tr> 
                                                        <tr id="internal_row">
                                                            <td style="background-color: #F0F8FF;">Internal Info</td>
                                                            <td id="view_internal_menu"></td>
                                                        </tr> 
                                                        <tr id="external_row1">
                                                            <td style="background-color: #F0F8FF;">External Engagement Number</ttdh>
                                                            <td id="view_external_clientnumber"></td>
                                                        </tr> 
                                                        <tr id="external_row1">
                                                            <td style="background-color: #F0F8FF;">External Client Name</ttdh>
                                                            <td id="view_external_clientname"></td>
                                                        </tr> 
                                                        <tr id="external_row2">
                                                            <td style="background-color: #F0F8FF;">External Client Type</td>
                                                            <td id="view_external_clienttype"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Purpose </td>
                                                            <td id="description"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Agenda</td>
                                                            <td id="view_agenda"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">IT Requirements</td>
                                                            <td id="view_it_req"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #F0F8FF;">Status</td>
                                                            <td id="booking_status"></td>
                                                            <td id="booking_view_id" style="display:none;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- end table-responsive -->
                
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-md-12"> 
                                    <div class="card-body">    

                                        <input type="button" class="btn btn-primary btn-sm" id="approve_booking_button" value="Approve Booking" style="display:none; float:right;"/> 
                                        <input type="button" class="btn btn-danger btn-sm" id="decline_booking_button" value="Decline Booking" style="display:none; float:right; margin-right: 3px;"/>
                                        <input type="button" class="btn btn-danger btn-sm" id="cancel_booking_button" value="Cancel Booking" style="display:none; float:right; margin-right: 3px;"/>  
                                        <input type="button" class="btn btn-primary btn-sm" id="rebook_booking_button" value="Rebook Booking" style="display:none; float:right; margin-right: 3px;"/> 
                                        <input type="button" class="btn btn-secondary btn-sm" id="cancelcalendarmodal" value="Close" style="float:right; margin-right: 3px;"/>
                                        <div class="clearfix"></div> 
                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
                <!-- </form> -->
            </div>
        </div>
    @endif

 
    <!-- Change password Modal--> 
    <div class="modal fade" id="changepassword_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form method="POST" id="changepassword_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Change Password</h4>
                    </div>
                    <div class="modal-body">

                        <ul class="list-group">
                            <div class="row" id="tran">
                                <div class="col-md-12"> 
                                    <div class="card-body">   
                                        
                                        <div class="form-group row">
                                            <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                <label style="font-size: .8rem;">Current Password*</label>
                                                <input type="password" class="form-control form-control-user" id="current_password" name="current_password"
                                                  required>  
                                            </div>
                                        </div> 
                                        
                                        <div class="form-group row">
                                            <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                <label style="font-size: .8rem;">New Password*</label>
                                                <input type="password" class="form-control form-control-user" id="new_password" name="new_password"
                                                required> 
                                            </div>
                                        </div> 
                                        
                                        <div class="form-group row">
                                            <div class="col-sm-12 mb-3 mb-sm-0"> 
                                                <label style="font-size: .8rem;">Confirm Password*</label>
                                                <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation"
                                                  required> 
                                            </div>
                                        </div> 

                                        <hr>
                                        <div class="text-center">
                                            <p class="small" style="color:red;" id="changepassword_errors"></p>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm"  style="float:right;">Save</button>
                                        <input type="button" class="btn btn-secondary btn-sm" id="cancelchangepasswordmodal" value="Cancel" style="float:right; margin-right: 3px;"/>
                                        <div class="clearfix"></div> 
                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div> 


     
    <div class="modal fade" id="part_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document"> 
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Sample</h4>
                    </div>
                    <div class="modal-body">

                        <ul class="list-group">
                            <div class="row" id="tran">
                                <div class="col-md-12"> 
                                    <div class="card-body">   
                                        
                                        <div class="form-group row">
                                            <div class="wrapper">
                                                <div class="title">
                                                    <img src="tag.svg" alt="icon">
                                                    <h2>Tags</h2>
                                                </div>
                                                <!-- <div class="contents">
                                                    <p>Press enter or add a comma after each tag</p>
                                                    <ul id="ul_pariticipants"><input type="text" id="participants_input" spellcheck="false"></ul>
                                                </div> -->
                                                <div class="details">
                                                    <p><span>10</span> tags are remaining</p>
                                                    <button id="removeall">Remove All</button>
                                                </div>
                                            </div> 
                                        </div> 
                                        
                                         

                                        <hr> 
                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div> 
        </div>
    </div> 

    <div class="modal" id="modal_process" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-body" >
                    <center>
                        <h5 style="color: blue" id="processing_load">PROCESSING</h5>
                        <img src="{{ asset('assets/img/loading2.gif') }}" alt="this slowpoke moves"  width="420" height="50px;" alt="404 image" /> 
                    </center>
                </div>
            </div>
        </div>
    </div>

    <!-- <a id="role" href="#" class="mr-2 d-none d-lg-inline text-gray-600 small" style="display: none;" >{{ auth()->user()->role }}</a> -->
                                    

    <!-- Bootstrap core JavaScript-->
    <script src=" {{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src=" {{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src=" {{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}" ></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}" ></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @yield('scripts')
    <script src="{{ asset('assets/js/codes/changepassword.js') }}"></script> 
    <script>
        
        var trademark_date = moment(); //Get the current date  
        $('#trademark').text('Meeting Room Reservation System ' + trademark_date.format("YYYY"));
    </script>
</body>

</html>
@extends('layouts.main', ['active' => 'user'])
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
        <h1 class="h3 mb-2 text-gray-800">Users Information</h1> 
        <br>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3"> 
                <h6 class="m-0 font-weight-bold text-primary">List of Users</h6>   
            </div>
            <div class="row" style="margin-top: 10px; margin-right: 10px;">
                <div class="col-md-12">
                    
                    <a href="#" class="btn btn-success btn-sm" style="float: right !important;" id="add_users" data-toggle="modal" data-target="#add_new_user" data-backdrop="static" data-keyboard="false" >
                        Add New User
                    </a>
                    <a href="#" class="btn btn-success btn-sm" style="float: right !important; margin-right: 10px;" id="bulk_upload" data-toggle="modal" data-target="#bulk_upload_modal" data-backdrop="static" data-keyboard="false" >
                        Bulk Upload 
                    </a>
                    
                    <div class="ml-2 float-right"> 
                        <select id="user_status" name="user_status" class="custom-select custom-select-sm form-control form-control-sm" style="width: 150px; margin-right: 10px;">
                            <option value="1" selected="selected">Active Users</option>
                            <option value="2">Pending Users</option> 
                          </select>
                    </div>
                </div>
            </div>
            <div class="card-body"> 
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr> 
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Work Email</th>
                                <th>Role</th> 
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr> 
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Work Email</th>
                                <th>Role</th> 
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <!-- <tr> 
                                <td>Elton John</td>
                                <td>Romero</td>
                                <td>ej@gmail.com</td>
                                <td>09111111111</td>
                                <td>Active</td>
                                <td>2022/04/25</td>
                                <th>
                                    <a href="#" class="btn btn-success btn-circle">
                                        <i class="fas fa-list"></i>
                                    </a> 
                                </th>
                            </tr>    -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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

 
        <script src="{{ asset('assets/js/codes/users.js') }}"></script> 

    @endsection
@endsection
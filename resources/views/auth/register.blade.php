<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Meeting Room Reservation System Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href=" {{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet"> 

    <style>
         
        
       .bg-register-image {
            background: url(" {{ asset('assets/img/login_logo.jpg') }}") !important;
            background-position: center !important;
            background-size: cover !important;
            /* width:200px !important;
            height:400px !important; */
        }
    </style>
 
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body --> 
                        
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>
                                    <form class="user" method="POST" action="{{ route('register') }}">
                                    @csrf
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <span style="font-size: .8rem;">First Name *</span>
                                                <input type="text" class="form-control form-control-user  @error('first_name') is-invalid @enderror" id="first_name"
                                                    value="{{ old('first_name') }}" autocomplete="first_name" name="first_name" autofocus>
                                                    @error('first_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                            </div>
                                            <div class="col-sm-6"> 
                                                <span style="font-size: .8rem;">Middle Name</span>
                                                <input type="text" class="form-control form-control-user  @error('middle_name') is-invalid @enderror" id="middle_name"
                                                    value="{{ old('middle_name') }}" autocomplete="middle_name" name ="middle_name" autofocus>
                                                    @error('middle_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <span style="font-size: .8rem;">Last Name *</span>
                                                <input type="text" class="form-control form-control-user  @error('last_name') is-invalid @enderror" id="last_name"
                                                    value="{{ old('last_name') }}" autocomplete="last_name" name="last_name" autofocus>
                                                    @error('last_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <span style="font-size: .8rem;">Phone Number</span>
                                                <input type="text" class="form-control form-control-user  @error('phone_number') is-invalid @enderror" id="phone_number"
                                                    value="{{ old('phone_number') }}" autocomplete="phone_number" name="phone_number" autofocus>
                                                    @error('phone_number')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span style="font-size: .8rem;">Email *</span>
                                            <input type="text" class="form-control form-control-user  @error('email') is-invalid @enderror" id="email"
                                                    value="{{ old('email') }}" autocomplete="email" name="email" autofocus>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror 
                                        </div>
                                        <!-- <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0"> 
                                                <span style="font-size: .8rem;">Password *</span>
                                                <input type="password" class="form-control form-control-user  @error('password') is-invalid @enderror" id="password"
                                                    value="{{ old('password') }}" autocomplete="password" name="password" autofocus>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror 
                                            </div>
                                            <div class="col-sm-6"> 
                                                <span style="font-size: .8rem;">Confirm Password *</span>
                                                <input type="password" class="form-control form-control-user  @error('password_confirmation') is-invalid @enderror" id="password_confirmation"
                                                    value="{{ old('password_confirmation') }}" autocomplete="password_confirmation"name="password_confirmation" autofocus> 
                                            </div>
                                        </div>  -->
                                        <button   class="btn btn-primary btn-user btn-block">
                                            Register Account
                                        </button>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="{{ route('login') }}">Already have an account? Login here</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
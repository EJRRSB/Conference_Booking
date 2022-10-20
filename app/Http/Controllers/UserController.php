<?php

namespace App\Http\Controllers;
use App\Models\User;

use DB; 
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Services\UserService;   
use App\Repositories\UserRepository;
use Validator;
use Illuminate\Validation\Rule;
use Response;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{ 
    public function __construct(UserRepository $repository)
    {
        $this->middleware('auth');
        $this->service = new UserService($repository);
    }
 
    public function index()
    {
        if(auth()->user()->role !== '1'){
            return redirect('/bookings');
        }
        return view('admin.users');
    }

     

     // AJAX REQUEST
    public function getAllUser(Request $request)
    {   
          
        return $this->service->getAllUser($request);
 
    }


    public function deleteUser(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'id' => ['required', 'int' ]
        ]);

        if ($validated->fails()) {
            echo json_encode(array(
				"statusCode" => 201,
				"message"    => 'Id is required'
			));
        }
        return $this->service->deleteUser($request); 
    }


    

    public function addUser(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'role'        => ['integer'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'], 
        ]);
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message" => $validated->errors()
			));  
        }

        return $this->service->addUser($request); 
    }

     
    public function getUser($id)
    {      
        return $this->service->getUser($id);
    }


    
    public function updateUser(Request $request)
    { 
        $validated = Validator::make($request->all(),[
            'id'          => ['required', 'int'],
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'role'        => ['integer'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request['id'] . '']
        ]);
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message" => $validated->errors()
			));  
        }

        return $this->service->updateUser($request); 
    }
  


    public function approveUser(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'id' => ['required', 'int' ]
        ]);

        if ($validated->fails()) {
            echo json_encode(array(
				"statusCode" => 201,
				"message" => 'Id is required'
			));
        }
        return $this->service->approveUser($request); 
    }


    
    public function downloadEmployeeBulkUploadTemplate()
    {  
        return Response::download(
            public_path(
            'bulk_upload/Employee_Bulk_Upload_Excel_Template.xlsx'),
            'Employee_Bulk_Upload_Excel_Template.xlsx',
            ['Content-Type' => 'text/xlsx']
        );
    }



    
    public function BulkUploadUser(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'file' => ['required'], 
        ]);
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message"    => $validated->errors()
			));  
        }

        return $this->service->BulkUploadUser($request); 
    }


    
    public function changePassUser(Request $request)
    { 
        $validated = Validator::make($request->all(),[
            'current_password'      => ['required', 'string', 'min:8'],
            'new_password'          => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8','same:new_password' ]
        ]); 
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message" => $validated->errors()
			));  
        }

        return $this->service->changePassUser($request); 
    }
}

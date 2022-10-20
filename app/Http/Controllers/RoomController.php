<?php

namespace App\Http\Controllers;
// use App\Models\User;

use DB; 
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Services\RoomService;   
use App\Repositories\RoomRepository;
use Validator;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{ 
    public function __construct(RoomRepository $repository)
    {
        $this->middleware('auth');
        $this->service = new RoomService($repository);
    }
 
    public function index()
    {
        if(auth()->user()->role !== '1'){
            return redirect('/bookings');
        }
        return view('admin.rooms');
    } 

    // AJAX REQUEST
    public function getAllRooms(Request $request)
    {    
        return $this->service->getAllRooms($request);

    }


    

    public function deleteRoom(Request $request)
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
        return $this->service->deleteRoom($request); 
    }


      
  
    public function addRoom(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'room_name'        => ['required', 'string', 'max:255', 'unique:rooms,name'],
            'room_color'       => ['required', 'string', 'max:255'],
            'room_description' => ['string', 'max:255'],  
        ]);
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message" => $validated->errors()
			));  
        }

        return $this->service->addRoom($request); 
    }


     
    public function getRoom($id)
    {       
        return $this->service->getRoom($id);
    }

    
    public function updateRoom(Request $request)
    {   
        $validated = Validator::make($request->all(),[
            'room_id'          => ['required', 'int'],
            'room_name'        => ['required', 'string', 'max:255', 'unique:rooms,name,' . $request['room_id'] . ''],
            'room_description' => ['string', 'max:255'],  
        ]);
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message" => $validated->errors()
			));  
        }

        return $this->service->updateRoom($request); 
    }
  
    
}

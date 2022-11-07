<?php

namespace App\Http\Controllers;
use App\Models\User;

use DB; 
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Services\BookingService;   
use App\Repositories\BookingRepository;
use Validator;
use Illuminate\Validation\Rule;
use App\Mail\User_email;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{ 
    public function __construct(BookingRepository $repository)
    {
        date_default_timezone_set('Asia/Manila');
        $this->middleware('auth');
        $this->service = new BookingService($repository);
    }
 
    public function index()
    {     
        // return view('email.booking_email'); 
        return view('admin.bookings');
    }

    // AJAX REQUEST
    public function getAllBookings(Request $request)
    {     
        return $this->service->getAllBookings($request); 
    }

     
    public function getAvailableRooms(Request $request)
    {     
        $validated = Validator::make($request->all(),[
            'start_time' => ['required' ],
            'end_time'   => ['required']
        ]);
        

        if (!$validated->fails()) {  
            return $this->service->getAvailableRooms($request); 
        }else{
            return json_encode(array(
				"statusCode" => 202,
				"message"    => $validated->errors()
			));  
        }
 
    }
 
    

    
    public function addBooking(Request $request)
    {   
        ini_set('max_execution_time', 300);        
        if(count($this->service->getArrayCount($request['participants_email'])) === 0 || count($this->service->getArrayCount($request['participants_id'])) === 0){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "Please input participants."
			));
        }           
        if($request['booking_start_time'] > $request['booking_end_time']){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "End Time must be greater than Start Time"
			));
        }   
        
        if($request['booking_option'] === 'Does Not Repeat'){
            if($request['booking_date'] === null){
                return json_encode(array(
                    "statusCode" => 201,
                    "message"    => "Date is required"
                )); 
            }
            
            if(auth()->user()->role === '1'){
                if($request['booking_date'] . ' ' .  $request['booking_start_time'] < date("Y-m-d H:i:s",strtotime('+1 minutes',strtotime(date("Y-m-d H:i:s"))))){ 
                    return json_encode(array(
                        "statusCode" => 201,
                        "message"    => "Error! Booking should be made 1 min before schedule."
                    )); 
                }
            }else{
                if($request['booking_date'] . ' ' .  $request['booking_start_time'] < date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime(date("Y-m-d H:i:s"))))){
                    return json_encode(array(
                        "statusCode" => 201,
                        "message"    => "Error! Booking should be made 30 mins before schedule."
                    )); 
                }
            }
        } 
  
        if($request['booking_type_menu'] === 'Internal'){
            $validated = Validator::make($request->all(),[ 
                'booking_start_time' => ['required'],  
                'booking_end_time'   => ['required'],  
                'booking_room'       => ['required'],   
                'booking_purpose'    => ['required'],  
                'participants_id'    => ['required'], 
                'participants_email' => ['required'], 
                'radio_mode'         => ['required'],
                'booking_type_menu'  => ['required'],
                'internal_menu'      => ['required'], 
            ]); 
            if ($validated->fails()) { 
                return json_encode(array(
                    "statusCode" => 202,
                    "message"    => $validated->errors()
                ));  
            }

        }else if($request['booking_type_menu'] === 'External'){
            $validated = Validator::make($request->all(),[ 
                'booking_start_time'          => ['required'],  
                'booking_end_time'            => ['required'],  
                'booking_room'                => ['required'],   
                'booking_purpose'             => ['required'],  
                'participants_id'             => ['required'], 
                'participants_email'          => ['required'], 
                'radio_mode'                  => ['required'],
                'booking_type_menu'           => ['required'], 
                'booking_engagement_number'   => ['required_if:radiobutton_client_type,Existing'],
                'booking_client_name'         => ['required'],
                'radiobutton_client_type'     => ['required'], 
            ]); 
            if ($validated->fails()) { 
                return json_encode(array(
                    "statusCode" => 202,
                    "message"    => $validated->errors()
                ));  
            }
        }
 

        return $this->service->addBooking($request); 
    }




    
    
    public function editBooking(Request $request)
    {    
        ini_set('max_execution_time', 300);  
        if(count($this->service->getArrayCount($request['it_requirements'])) === 0){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "Please select an IT requirements."
			));
        } 
        if(count($this->service->getArrayCount($request['agenda'])) === 0){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "Please Enter Agenda."
			));
        } 
        if(count($this->service->getArrayCount($request['participants_email'])) === 0 || count($this->service->getArrayCount($request['participants_id'])) === 0){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "Please input participants."
			));
        }           
         

        if($request['booking_type_menu'] === 'Internal'){

            $internal_validation = [ 
                'booking_id'         => ['required'],
                'booking_purpose'    => ['required'],  
                'participants_id'    => ['required'], 
                'participants_email' => ['required'], 
                'radio_mode'         => ['required'],
                'booking_type_menu'  => ['required'],
                'internal_menu'      => ['required'], 
            ];

            if($request['booking_status_input'] === 'Pending'  || $request['booking_status_input'] === 'Canceled'){  

                if($request['booking_start_time'] > $request['booking_end_time']){
                    return json_encode(array(
                        "statusCode" => 201,
                        "message"    => "End Time must be greater than Start Time"
                    ));
                }   
                if($request['booking_date'] . ' ' .  $request['booking_start_time'] < date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime(date("Y-m-d H:i:s"))))){
                    return json_encode(array(
                        "statusCode" => 201,
                        "message"    => "Error! Booking should be made 30 mins before schedule."
                    )); 
                }
                
                $internal_validation['booking_date']       = ['required']; 
                $internal_validation['booking_start_time'] = ['required']; 
                $internal_validation['booking_end_time']   = ['required']; 
                $internal_validation['booking_room']       = ['required']; 
            } 
            $validated = Validator::make($request->all(), $internal_validation); 

        }else if($request['booking_type_menu'] === 'External'){
            $external_validation = [ 
                'booking_id'                  => ['required'],
                'booking_purpose'             => ['required'],  
                'participants_id'             => ['required'], 
                'participants_email'          => ['required'], 
                'radio_mode'                  => ['required'],
                'booking_type_menu'           => ['required'], 
                'booking_engagement_number'   => ['required_if:radiobutton_client_type,Existing'],
                'booking_client_name'         => ['required'],
                'radiobutton_client_type'     => ['required'],
                // 'prospective_input'           => ['required_if:radiobutton_client_type,Prospective'],  
            ]; 
            
            if($request['booking_status_input'] === 'Pending' || $request['booking_status_input'] === 'Canceled'){  

                if($request['booking_start_time'] > $request['booking_end_time']){
                    return json_encode(array(
                        "statusCode" => 201,
                        "message"    => "End Time must be greater than Start Time"
                    ));
                }   
                if($request['booking_date'] . ' ' .  $request['booking_start_time'] < date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime(date("Y-m-d H:i:s"))))){
                    return json_encode(array(
                        "statusCode" => 201,
                        "message"    => "Error! Booking should be made 30 mins before schedule."
                    )); 
                }

                $external_validation['booking_date']       = ['required']; 
                $external_validation['booking_start_time'] = ['required']; 
                $external_validation['booking_end_time']   = ['required']; 
                $external_validation['booking_room']       = ['required']; 
            } 
            $validated = Validator::make($request->all(),$external_validation); 
        }
 
        if ($validated->fails()) { 
            return json_encode(array(
				"statusCode" => 202,
				"message"    => $validated->errors()
			));  
        }

        if($request['is_rebook'] === false){
            return $this->service->editBooking($request); 
        }else {
            return $this->service->rebookBooking($request);
        }
    }


 

    // public function approveBooking(Request $request)
    public function updateMultipleStatusBooking(Request $request)
    {
        ini_set('max_execution_time', 300);  
        $validated = Validator::make($request->all(),[ 
            'status' => ['required']
        ]);
        
        if ($validated->fails() || count($request['ids']) === 0) {
            return json_encode(array(
				"statusCode" => 201,
				"message"    => 'Please select row/s to apply an action.'
			));
        }
 
        $message = '';
        $success = 0; 
        foreach($request['ids'] as $id) {   
            $request['id'] = $id;  

            if($request['status'] === '1'){ 
                $result = json_decode($this->service->approveBooking($request), true);  
            }else  if($request['status'] === '3'){ 
                $result = json_decode($this->service->declineBooking($request), true);  
            }else  if($request['status'] === '4'){ 
                $result = json_decode($this->service->cancelBooking($request), true);  
            }

            $result['statusCode'] === '201' ? $message .= $result['message'] . '; ' : $success ++;

        } 
        
        if($success > 0 && $message === ''){
            return json_encode(array(
				"statusCode" => 200,
				"message"    => $this->getMessageStatus($request['status'], 1)
			));
        }else if($success > 0 && $message != ''){
            return json_encode(array(
				"statusCode" => 200,
				"message"    => $message . $this->getMessageStatus($request['status'], 2)
			));
        }else if($success === 0){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => $message  
			)); 
        } 
        
    }



    public function getMessageStatus($booking_status, $mesage_status){
        if($booking_status === '1'){ 

            if($mesage_status === 1){
                return 'All your selected bookings have been successfully approved. May you have productive meetings!';
            } if($mesage_status === 2){
                return ' but other selected bookings have been approved successfully!';
            } 

        }else  if($booking_status === '3'){ 

            if($mesage_status === 1){
                return 'All your selected bookings have been successfully declined.';
            } if($mesage_status === 2){
                return ' but other selected bookings have been declined successfully!';
            } 

        }else  if($booking_status === '4'){ 

            if($mesage_status === 1){
                return 'All your selected bookings have been successfully canceled.';
            } if($mesage_status === 2){
                return ' but other selected bookings have been canceled successfully!';
            } 
            
        }
    }
 
 
    
    public function updateStatusBooking(Request $request)
    {
        ini_set('max_execution_time', 300);  
        $validated = Validator::make($request->all(),[
            'id'     => ['required', 'int' ],
            'status' => ['required']
        ]);

        if ($validated->fails()) {
            return json_encode(array(
				"statusCode" => 201,
				"message"    => 'Id and Status is required'
			));
        }

        if($request['status'] === '1'){
            return $this->service->approveBooking($request); 
        }else  if($request['status'] === '3'){
            return $this->service->declineBooking($request); 
        }else  if($request['status'] === '4'){
            return $this->service->cancelBooking($request); 
        }
    }


  
    public function declineBooking(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'id' => ['required', 'int' ]
        ]);

        if ($validated->fails()) {
            return json_encode(array(
				"statusCode" => 201,
				"message"    => 'Id is required'
			));
        }
        return $this->service->declineBooking($request); 
    }

    
  
    public function cancelBooking(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'id' => ['required', 'int' ]
        ]);

        if ($validated->fails()) {
            return json_encode(array(
				"statusCode" => 201,
				"message"    => 'Id is required'
			));
        }
        return $this->service->cancelBooking($request); 
    }

    // AJAX REQUEST
    public function getParticipants(Request $request)
    {   
        
        return $this->service->getParticipants($request);

    }
  
    public function getBookingById($id)
    { 
         

        if ($id === '' || $id === null) {
            return json_encode(array(
				"statusCode" => 201,
				"message"    => 'Id is required'
			));
        }
        return $this->service->getBookingById($id); 
    }


    
}

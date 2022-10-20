<?php

namespace App\Services;

use App\Repositories\ReportRepository;

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
  
use App\Models\User;
// use DateTime;
 
class ReportService extends BaseService
{ 
    public function __construct()
    {
        $this->repository = new ReportRepository();
    }

    
    public function ListOfBookingsByStatus($request)
    {  
        
        if($request['status'] === null){
            return json_encode(array(
				"statusCode" => 201,
				"message" => "Status is required"
			));            
        }
        $result =  $this->repository->ListOfBookingsByStatus($request['status'], $request);  
        
        if($result){
            
            $data = array();  

            foreach($result as $record) {


                $data[] = array(   

                    "id"                     => $record->id, 
                    "room_id"                => $record->room_id,
                    "room_name"              => $record->room->name, 
                    "purpose"                => $record->purpose,
                    "start_time"             =>  date("Y-m-d h:i A", strtotime($record->start_time)),
                    "start"                  => $record->start_time,
                    "end_time"               => date("Y-m-d h:i A", strtotime($record->end_time)), 
                    "end"                    => $record->end_time,
                    "status"                 => $this->getStatus($record->status),
                    
                    "participants"           => $record->participants,

                    "mode"                   => $record->mode,
                    "type"                   => $record->type,
                    
                    "internal_option"        => $record->internal_option === null ? '' : $record->internal_option,
                    "internal_option_others" => $record->internal_option_others === null ? '' : $record->internal_option_others,

                    "client_number"          => $record->client_number === null ? '' : $record->client_number,
                    "client_name"            => $record->client_name === null ? '' : $record->client_name,
                    "client_type"            => $record->client_type === null ? '' : $record->client_type,
                    "client_type_others" => $record->client_type_others === null ? '' : $record->client_type_others,

                    "agenda"                 => str_replace(',', ' ; ', $record->agenda), 
                    "it_requirements"        => str_replace(',', ' ; ', $record->it_requirements), 
                    "it_requirements_others" => str_replace(',', ' ; ', $record->it_requirements_others === null ? '' : $record->it_requirements_others) ,  

                    "added_by"               => $record->user->first_name . ' ' . $record->user->last_name, 
                    "approver"               => $this->getApprover($record->status, $record),  
                    // "declined_by"            => $record->declined ? $record->declined->first_name . ' ' . $record->declined->last_name : '', 
                    "created_at"             =>  date_format($record->created_at,"Y-m-d h:i A"),    
                    
                );
            } 
            return $this->returnJson('200', $data); 
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }

    }
  
    public function getStatus($status){  
        if($status === 1){
            return 'Approved';
        }else if($status === 2){
            return 'Pending';
        }else if($status === 3){
            return 'Declined';
        }else if($status === 4){
            return 'Canceled';
        }
    }



    
    public function ListOfArchivedBookings($request)
    {  
         
        $result =  $this->repository->ListOfArchivedBookings($request);  
        
        if($result){
            
            $data = array();  

            foreach($result as $record) {


                $data[] = array(   

                    "id"                     => $record->id, 
                    "room_id"                => $record->room_id,
                    "room_name"              => $record->room->name, 
                    "purpose"                => $record->purpose,
                    "start_time"             =>  date("Y-m-d h:i A", strtotime($record->start_time)),
                    "start"                  => $record->start_time,
                    "end_time"               => date("Y-m-d h:i A", strtotime($record->end_time)), 
                    "end"                    => $record->end_time,
                    "status"                 => $this->getStatus($record->status),
                    
                    "participants"           => $record->participants,

                    "mode"                   => $record->mode,
                    "type"                   => $record->type,
                    
                    "internal_option"        => $record->internal_option === null ? '' : $record->internal_option,
                    "internal_option_others" => $record->internal_option_others === null ? '' : $record->internal_option_others,

                    "client_number"          => $record->client_number === null ? '' : $record->client_number,
                    "client_name"            => $record->client_name === null ? '' : $record->client_name,
                    "client_type"            => $record->client_type === null ? '' : $record->client_type,
                    "client_type_others"     => $record->client_type_others === null ? '' : $record->client_type_others,

                    "agenda"                 => str_replace(',', ' ; ', $record->agenda), 
                    "it_requirements"        => str_replace(',', ' ; ', $record->it_requirements), 
                    "it_requirements_others" => str_replace(',', ' ; ', $record->it_requirements_others === null ? '' : $record->it_requirements_others) ,  

                    "added_by"               => $record->user->first_name . ' ' . $record->user->last_name, 
                    "approver"               => $this->getApprover($record->status, $record),  
                    "created_at"             => date_format($record->created_at,"Y-m-d h:i A"),    
                    
                );
            } 
            return $this->returnJson('200', $data); 
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }

    }


    public function getApprover($status, $record){  
        if($status === 1){
            return $record->approved ? $record->approved->first_name . ' ' . $record->approved->last_name : '';
        }else if($status === 2){
            return '';
        }else if($status === 3){
            return $record->declined ? $record->declined->first_name . ' ' . $record->declined->last_name : '';
        }else if($status === 4){
            return $record->canceled ? $record->canceled->first_name . ' ' . $record->canceled->last_name : '';
        }
    }
    
    
    public function ListOfUsersByStatus($request)
    {  
        if(auth()->user()->role !== '1'){ 
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "You are not authorized"
			));   
        }
        if($request['status'] === null){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "Status is required"
			));            
        }
        $result =  $this->repository->ListOfUsersByStatus($request['status']);  
        
        if($result){
            
            $data = array();  

            foreach($result as $record) {
 
                $data[] = array( 
                    "first_name"    => $record->first_name,
                    "middle_name"   => $record->middle_name === null ? '' : $record->middle_name,
                    "last_name"     => $record->last_name,
                    "email"         => $record->email,
                    "role"          => $record->role === '1' ? 'Admin' : 'User',  
                    "created_at"    =>  date_format($record->created_at,"Y-m-d h:i A"),   
                );
            }
 
            return $this->returnJson('200', $data); 
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }

    }
    
    
    public function ListOfAllRooms()
    {   
        $result =  $this->repository->ListOfAllRooms();  
        
        if($result){
            
            $data = array();  

            foreach($result as $record) {
 
                $data[] = array(  
                    "name"        => $record->name,
                    "description" => $record->description,
                    "added_by"    => $record->user->first_name . ' ' . $record->user->last_name, 
                    "created_at"  =>  date_format($record->created_at,"Y-m-d h:i A"),     
                );
            }

            return $this->returnJson('200', $data); 
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }

    }

}

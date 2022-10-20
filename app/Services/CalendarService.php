<?php

namespace App\Services;

use App\Repositories\CalendarRepository;

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
  
use App\Models\User;
// use DateTime;
 
class CalendarService extends BaseService
{ 
    public function __construct()
    {
        $this->repository = new CalendarRepository();
    }
  
    

    public function getCalendarInfo($request)
    {   
        $result =  $this->repository->getCalendarInfo($request);  
        if($result){
            
            $events = array();  

            foreach($result as $record) { 
  
                $events[] = array( 
                    "id"                     => $record->id, 
                    "room_id"                => $record->room_id,
                    "room_name"              => $record->room->name, 
                    "booking_number"         => $record->booking_number ? $record->booking_number : '',
                    "purpose"                => $record->purpose,
                    "start_time"             => date("Y-m-d h:i A", strtotime($record->start_time)), 
                    "start"                  => $record->start_time,
                    "end_time"               => date("Y-m-d h:i A", strtotime($record->end_time)),
                    "end"                    => $record->end_time,
                    "status"                 => $this->getStatus($record->status),
                    
                    "participants"           => $record->participants,

                    "mode"                   => $record->mode,
                    "type"                   => $record->type,
                    
                    "internal_option" => $record->internal_option,
                    "internal_option_others" => $record->internal_option_others === null ? '' : $record->internal_option_others,

                    "client_number"          => $record->client_number,
                    "client_name"            => $record->client_name,
                    "client_type"            => $record->client_type,
                    "client_type_others"     => $record->client_type_others === null ? '' : $record->client_type_others,

                    "agenda" => $record->agenda, 
                    "it_requirements" => $record->it_requirements, 
                    "it_requirements_others" => $record->it_requirements_others === null ? '' : $record->it_requirements_others,  

                    "added_by"               => $record->user->first_name . ' ' . $record->user->last_name, 
                    "approved_by"            => $record->approved ? $record->approved->first_name . ' ' . $record->approved->last_name : '',  
                    "created_at"             =>  date_format($record->created_at,"Y-m-d h:i A"),   

                    "title"                  => $record->room->name .  ' [' . $record->user->first_name . ']',
                    "color"                  => $record->room->room_color, 
                );
            }
 
            return $this->returnJson('200',$events); 
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
        }
    }


    
    public function getAllCalendarRooms()
    {      
         
        $records = $this->repository->getAllCalendarRooms(); 

        $data_arr = array();  

        foreach($records as $record) {  
            $data_arr[] = array(
                "room_id"   => $record->id,  
                "room_name" => $record->name, 
            );
        }
            
        return json_encode(array(
            "statusCode" => 200,
            "data"       => $data_arr
        ));  
    }
}

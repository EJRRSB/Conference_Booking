<?php

namespace App\Repositories;
   
use Carbon\Carbon;
use DB;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Participants;
 

class CalendarRepository 
{ 
    public function __construct()
    {
        $this->model = new Booking();
    }
  

    public function getCalendarInfo($request){  
        
        // if($request['room_id'] === null){
        //     return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')    
        //         ->where('status', '1')
        //         ->whereRelation('room', 'deleted_at', '=', null)
        //         ->orderBy('start_time','asc') 
        //         ->get(); 
        // }else{
        //     return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')      
        //         ->where('room_id', $request['room_id'])
        //         ->where('status', '1')
        //         ->whereRelation('room', 'deleted_at', '=', null)
        //         ->orderBy('start_time','asc') 
        //         ->get(); 
        // }

        $query     = Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user'); 
        $query     = $query->where('status', '1'); 
        if($request['room_id'] !== null){
            $query = $query->where('room_id', $request['room_id']);  
        } 
        if($request['mybookings'] !== null){
            $query = $query->where('user_id', $request['mybookings']);  
        } 
        $query     = $query->whereRelation('room', 'deleted_at', '=', null);  
        $query     = $query->orderBy('start_time','asc') ;   
        return $query->get();   
        
     
    }

    

    public function getAllCalendarRooms(){ 
        return Room::select('rooms.*')->get(); 
     
    }
   
}

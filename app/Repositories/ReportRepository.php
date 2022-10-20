<?php

namespace App\Repositories;
   
use Carbon\Carbon;
use DB;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
 

class ReportRepository 
{ 
    public function __construct()
    {
        $this->model = new Room();
    }
 
    
    public function ListOfBookingsByStatus($status, $request)
    { 
        if(auth()->user()->role !== '1'){ 
            return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')
                ->where('status', $status)  
                ->whereBetween('start_time', array(date('Y-m-d') . ' 00:00:00', $request['book_date2']))
                // ->whereBetween('start_time', array($request['book_date1'], $request['book_date2']))
                ->whereRelation('user', 'id', auth()->user()->id)
                ->orderBy('start_time','asc') 
                ->get(); 
        }else{
            return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user') 
                ->where('status', $status)  
                ->whereBetween('start_time', array(date('Y-m-d') . ' 00:00:00', $request['book_date2']))
                // ->whereBetween('start_time', array($request['book_date1'], $request['book_date2']))
                ->orderBy('start_time','asc') 
                ->get(); 
        }
       
    }



    
    
    public function ListOfArchivedBookings($request)
    { 
        if(auth()->user()->role !== '1'){ 
            return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user') 
                ->where('start_time','<', date('Y-m-d'))
                ->whereRelation('user', 'id', auth()->user()->id)
                ->orderBy('start_time','asc') 
                ->get(); 
        }else{
            return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user') 
                ->where('start_time','<', date('Y-m-d'))
                ->orderBy('start_time','asc') 
                ->get(); 
        }
       
    }
 
    
    public function ListOfUsersByStatus($status)
    {  
        return User::select('users.*') 
                                 ->where('role' ,'!=','3') 
                                 ->where('status', $status)  
                                 ->get(); 
    }


    public function ListOfAllRooms(){
        return Room::select('rooms.*')->with('user')  
                             ->get(); 
    }
    
}

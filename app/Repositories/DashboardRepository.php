<?php

namespace App\Repositories;
   
use Carbon\Carbon;
use DB;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
 

class DashboardRepository 
{ 
    public function __construct()
    {
        $this->model = new Booking();
    }
 
   
    public function getCountsData($request){
      
        $users = User::select('id',DB::raw('count(*) as count'))->where('status','1')->where('role','!=', '3')->count();
        $users_pending = User::select('id',DB::raw('count(*) as count'))->where('status','2')->count();
        $rooms = Room::select('id',DB::raw('count(*) as count'))->count();
        // $bookings = Booking::select('id',DB::raw('count(*) as count'))->where('status','1')->where('start_time','like','%' . $request['book_date'] . '%')->orWhere('status','2')->where('start_time','like','%' . $request['book_date'] . '%') ->count();
        $bookings = Booking::select('id',DB::raw('count(*) as count'))
        ->where('status','1')->whereBetween('start_time', array($request['book_date1'], $request['book_date2']))
        ->orWhere('status','2')->whereBetween('start_time', array($request['book_date1'], $request['book_date2']))
        ->count();
        
        return $data = [
            "users" => $users,
            "users_pending" => $users_pending,
            "rooms" => $rooms,
            "bookings" => $bookings
        ]; 

    }
    
    public function getChartData($request){
 
        // $most_booked_room = Booking ::select('room_id',DB::raw('count(*) as count'))->where('start_time','like','%' . $request['book_date'] . '%')->with('room')->groupBy('room_id')->get();
        // $booking_info = Booking::select('status',DB::raw('count(*) as count'))->where('start_time','like','%' . $request['book_date'] . '%')->groupBy('status')->limit('5')->get();
        $most_booked_room = Booking ::select('room_id',DB::raw('count(*) as count'))->whereBetween('start_time', array($request['book_date1'], $request['book_date2']))->whereRelation('room', 'deleted_at', '=', null)->with('room')->groupBy('room_id')->get();
        $booking_info = Booking::select('status',DB::raw('count(*) as count'))->whereBetween('start_time', array($request['book_date1'], $request['book_date2']))->groupBy('status')->limit('5')->get();

        return $data = [
            "most_booked_room" => $most_booked_room,
            "booking_info" => $booking_info 
        ]; 

    }
     
}

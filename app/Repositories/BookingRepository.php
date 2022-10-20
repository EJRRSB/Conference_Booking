<?php

namespace App\Repositories;
   
use Carbon\Carbon;
use DB;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Participants;
 

class BookingRepository 
{ 
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
        $this->model = new Booking();
    }
 
   
    public function getBookingCount($status, $book_date, $book_date1, $book_date2){ 

        $query = Booking::select('bookings.*')->with('room'); 

        if($status != '5'){
            $query = $query->where('status', $status);
            $query = $query->whereBetween('start_time', array( date('Y-m-d'), $book_date2)); 
        }else{
            $query = $query->where('start_time','<', date('Y-m-d'));
        }

        $query     = $query->whereRelation('room', 'deleted_at', '=', null); 
        if(auth()->user()->role != '1'){  $query = $query->where('user_id', auth()->user()->id); }  
        return $query->count();   
        
    }

    public function getTotalCountWithSearch($searchValue, $status, $book_date, $book_date1, $book_date2){ 

        $query     = Booking::select('bookings.*')->with('room');
        $query     = $query->where('purpose','like','%' . $searchValue . '%'); 

        if($status != '5'){
            $query = $query->where('status', $status);
            $query = $query->whereBetween('start_time', array( date('Y-m-d'), $book_date2)); 
        }else{
            $query = $query->where('start_time','<', date('Y-m-d'));
        }

        $query     = $query->whereRelation('room', 'deleted_at', '=', null);  
        if(auth()->user()->role != '1'){  $query = $query->where('user_id', auth()->user()->id); }  
        return $query->count();  
         
    }

    public function getTotalRecordsWithSearch($searchValue, $sortColumnName, $columnSortOrder, $start, $rowpage, $status, $book_date, $book_date1, $book_date2){ 
                                 
        $query     = Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined');
        $query     = $query->where('purpose','like','%' . $searchValue . '%'); 

        if($status != '5'){
            $query = $query->where('status', $status);
            $query = $query->whereBetween('start_time', array( date('Y-m-d'), $book_date2)); 
        }else{
            $query = $query->where('start_time','<', date('Y-m-d'));
        }

        $query     = $query->whereRelation('room', 'deleted_at', '=', null);  
        if(auth()->user()->role != '1'){  $query = $query->where('user_id', auth()->user()->id); } 
        $query     = $query->orderBy('start_time','desc');
        $query     = $query->skip($start);
        $query     = $query->take($rowpage);
        return $query->get();  
 
    }
  
     
    public function getAvailableRooms($request)
    { 
        $start_time = $request['start_time'];
        $end_time   = $request['end_time']; 
        $booking_id = $request['booking_id']; 

        if($request['booking_option'] === 'Recurring'){
            return Room::select('rooms.*')  
                ->orderBy('name','asc') 
                ->get(); 
        }else{
            if($booking_id != null || $booking_id != ''){
                $arooms=DB::SELECT(" SELECT * FROM rooms WHERE deleted_at IS NULL AND id  NOT IN  (SELECT room_id FROM `bookings` WHERE '$start_time' >= start_time And '$start_time' < end_time AND deleted_at IS NULL AND id != '$booking_id' AND status != '3' AND status != '4'
                    OR '$end_time' <= end_time And '$end_time' > start_time AND deleted_at IS NULL AND id != '$booking_id' AND status != '3' AND status != '4'
                    OR '$start_time' <= start_time And '$end_time' >= end_time AND deleted_at IS NULL  AND id != '$booking_id' AND status != '3' AND status != '4')");
                    return $arooms;
            }else{
                $arooms=DB::SELECT(" SELECT * FROM rooms WHERE deleted_at IS NULL AND id  NOT IN  (SELECT room_id FROM `bookings` WHERE '$start_time' >= start_time And '$start_time' < end_time AND deleted_at IS NULL AND status != '3' AND status != '4'
                    OR '$end_time' <= end_time And '$end_time' > start_time AND deleted_at IS NULL AND status != '3' AND status != '4'
                    OR '$start_time' <= start_time And '$end_time' >= end_time AND deleted_at IS NULL AND status != '3' AND status != '4')");
                    return $arooms;
            }
        }
        
    }


    
     
    public function validationRoomAvailability($request)
    { 
        $start_time = $request['start_time']; 
        $end_time   = $request['end_time'];
        $room_id    = $request['room_id']; 

        $arooms=DB::SELECT(" SELECT room_id FROM `bookings` WHERE '$start_time' >= start_time And '$start_time' < end_time AND deleted_at IS NULL AND room_id = $room_id AND status != '3' AND status != '4'
        OR '$end_time' <= end_time And '$end_time' > start_time AND deleted_at IS NULL AND room_id = $room_id AND status != '3' AND status != '4'
        OR '$start_time' <= start_time And '$end_time' >= end_time AND deleted_at IS NULL AND room_id = $room_id AND status != '3' AND status != '4'");
        return $arooms;
    }

    
    
    public function addBooking($data)
    {   
        $booking_number = Booking::select('bookings.*')->where('booking_number','like', '%'. date('Y') . '%')->orderBy('id','desc') ->first(); 
        if(!empty($booking_number)){  
            $new_booking_number     = explode('-', preg_replace('/\s+/', '', $booking_number->booking_number) ); 
            $d                      = $new_booking_number[count($new_booking_number) - 1] + 1;
            $data['booking_number'] = date('Y') . '-'. sprintf('%04d',$d);
        }else{
            $data['booking_number'] = date('Y') . '-0001';
        }

        return $this->model->create($data);  
    }



    public function editBooking($data, $id)
    {
         
        $query = $this->model->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')->findOrFail($id);

        if($query->update($data)){
            return $query;
        }else{
            return null;
        }
    }

    
    public function approveBooking($request)
    { 
        $query = $this->model->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')->findOrFail($request['id']);

        if($query->update(['approved_by' => auth()->user()->id, 'status' => 1])){
            return $query;
        }else{
            return null;
        }
    }

    
    public function declineBooking($request)
    {
        $query = $this->model->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')->findOrFail($request['id']);

        if($query->update(['declined_by' => auth()->user()->id, 'status' => 3])){
            return $query;
        }else{
            return null;
        }
    }

    
    public function cancelBooking($request)
    {
        $query = $this->model->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')->findOrFail($request['id']);

        if($query->update(['canceled_by' => auth()->user()->id, 'status' => 4])){
            return $query;
        }else{
            return null;
        }
    }
     

    
    public function getParticipants($request){
        return User::select('users.*') 
                             ->where('status', '1')
                             ->where('role','!=', '3')
                            //  ->where('id', '!=', auth()->user()->id)
                             ->orderBy('first_name','asc') 
                             ->get(); 
    }


    public function addParticipants($data)
    { 
        return Participants::create($data);  
    }


    public function checkIfParticipantsExists($booking_id, $guest_email, $member_id){
        return Participants::where('guest', '1')
            ->where('booking_id', $booking_id)
            ->where('guest_email', $guest_email) 
            ->orWhere('guest', '0')
            ->where('booking_id', $booking_id)
            ->where('user_id', $member_id)
            ->get(); 
    }
    
    public function deleteParticipants($booking_id, $guest_emails, $member_ids)
    { 
        return Participants::where('guest', '1')
            ->where('booking_id', $booking_id)
            ->whereNotIn('guest_email', $guest_emails) 
            ->orWhere('guest', '0')
            ->where('booking_id', $booking_id)
            ->whereNotIn('user_id', $member_ids)
            ->delete(); 
    } 
     
    
    public function getBookingById($id){  
         
        return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')     
            ->where('id', $id)
            ->whereRelation('room', 'deleted_at', '=', null)
            ->orderBy('start_time','asc') 
            ->first();  
        
     
    }


    
    public function getAdminUsers(){
        return User::select('users.*') 
                             ->where('status', '1') 
                             ->where('role', '1') 
                             ->orderBy('first_name','asc') 
                             ->get(); 
    }


    
    
    public function getITEmail(){
        return User::select('email') 
                             ->where('status', '1') 
                             ->where('is_IT', '2')  
                             ->get(); 
    }
    

    public function getBookingParticipantsEmail($id){  
         
        return Booking::select('bookings.*')->with('user')->with('room')->with('participants')->with('participants.user')     
            ->where('id', $id)
            ->whereRelation('room', 'deleted_at', '=', null)
            ->orderBy('start_time','asc') 
            ->first();  
        
     
    }


    
    
    public function getPastPendingBooking(){   
        return Booking::select('bookings.*')->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')  
            ->where('start_time','>', date('Y-m-d') . ' 00:00:00')    
            ->where('status', '2')    
            ->whereRelation('room', 'deleted_at', '=', null)
            ->orderBy('start_time','asc') 
            ->get();   

    }

    
    
    public function declineBookingAutomatically($id)
    {
        $query = $this->model->with('user')->with('room')->with('approved')->with('declined')->with('participants')->with('participants.user')->findOrFail($id);

        if($query->update(['declined_by' => '47', 'status' => 3])){
            return $query;
        }else{
            return null;
        }
    }

}

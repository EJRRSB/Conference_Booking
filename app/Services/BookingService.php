<?php

namespace App\Services;

use App\Repositories\BookingRepository;

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Mail\Booking_email;
use Illuminate\Support\Facades\Mail;

  
use App\Models\User;
// use DateTime;
 
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Properties\TextProperty;

class BookingService extends BaseService
{ 
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
        $this->repository = new BookingRepository();
    }
 
    
     public function getAllBookings($request)
     {    

        $status = $request->get('status');
        $book_date = $request->get('book_date');
        $book_date1 = $request->get('book_date1');
        $book_date2 = $request->get('book_date2'); 
        // READ VALUES
        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowpage = $request->get('length');
        
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');


        $columnIndex = $columnIndex_arr[0]['column'];    
        $columnSortOrder = $order_arr[0]['dir']; 
        $searchValue = $search_arr['value'];  

        $sortColumns = array(
            0 => 'purpose', 
        ); 
        
    
        $sortColumnName = $sortColumns[$order_arr[0]['column']];
        // Total record count
        $totalRecords =  $this->repository->getBookingCount($status, $book_date, $book_date1, $book_date2);

        // Total record count with search
        $totalRecordswithFilter = $this->repository->getTotalCountWithSearch($searchValue,$status, $book_date, $book_date1, $book_date2);


        // Total records with search
        $records = $this->repository->getTotalRecordsWithSearch($searchValue, $sortColumnName, $columnSortOrder, $start, $rowpage, $status, $book_date, $book_date1, $book_date2);
            

        $data_arr = array();  

        foreach($records as $record) {


            $data_arr[] = array(
                "id"             => $record->id, 
                "room_id"        => $record->room_id,
                "room_name"      => $record->room->name,
                "room_color"     => $record->room->room_color,
                "purpose"        => $record->purpose,
                "booking_number" => $record->booking_number,
                "start_time"     => date("Y-m-d h:i A", strtotime($record->start_time)), 
                "end_time"       => date("Y-m-d h:i A", strtotime($record->end_time)), 
                "status"         => $this->getStatus($record->status),
                "added_by"       => $record->user->first_name . ' ' . $record->user->last_name, 
                "approved_by"    => $record->approved ? $record->approved->first_name . ' ' . $record->approved->last_name : '', 
                "declined_by"    => $record->declined ? $record->declined->first_name . ' ' . $record->declined->last_name : '', 
                "approver"       => $this->getApprover($record->status, $record),
                "created_at"     =>  date_format($record->created_at,"Y-m-d h:i A"),   
                "is_archived"     =>  $status === '5' ? '1' : '2',  
            );
        }
         
        return json_encode(
            array(
                "draw"            => intval($draw), 
                "recordsTotal"    => intval($totalRecords),
                "recordsFiltered" => intval($totalRecordswithFilter),
                "aaData"          => $data_arr 
            )
        );
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


    
    public function getAvailableRooms($request)
    {     
     
        if($request['start_time'] > $request['end_time']){ 
            return $this->returnJson('201', "End Time must be greater than Start Time"); 
        }

        if($request['booking_option'] === 'Does Not Repeat'){
            
            if($request['start_time'] < date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime(date("Y-m-d H:i:s"))))){ 
                return $this->returnJson('201', "Booking should be made 30 mins before schedule."); 
            }

        } 

        
       // Total records with search
        $records  = $this->repository->getAvailableRooms($request); 

        $data_arr = array();  

        foreach($records as $record) {  
            $data_arr[] = array(
                "room_id"   => $record->id,  
                "room_name" => $record->name, 
                "max_seats" => $record->max_seats, 
            );
        }
            
        return json_encode(array(
            "statusCode" => 200,
            "data"       => $data_arr
        ));
    }


    public function toMail(): MailMessage
    { 
        $calendar = Calendar::create()
            ->productIdentifier('Kutac.cz')
            ->event(function (Event $event) {
                $event->name("Email with iCal 101")
                    ->attendee("elton.romero@rsb-consulting.com")
                    ->startsAt(Carbon::parse("2021-12-15 08:00:00"))
                    ->endsAt(Carbon::parse("2021-12-19 17:00:00"))
                    ->fullDay()
                    ->address('Online - Google Meet');
            });
        $calendar->appendProperty(TextProperty::create('METHOD', 'REQUEST'));        
       
        return (new MailMessage()) 
            ->subject("Invitation")
            ->markdown('mail.invite.created')
            ->attachData($calendar->get(), 'invite.ics', [
                'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
        ]); 
    }

     
    
    public function addBooking($request)
    {    
        
        $data = array(
            'user_id'                => auth()->user()->id, 
            'room_id'                => $request['booking_room'],
            'purpose'                => $request['booking_purpose'], 
            'status'                 => auth()->user()->role === '1' ? '1' : '2',
            'approved_by'            => auth()->user()->role === '1' ? auth()->user()->id : null, 
            'mode'                   => $request['radio_mode'],
            'type'                   => $request['booking_type_menu'], 
            'internal_option_others' => $request['internal_menu_others'], 
            'agenda'                 => $request['agenda'],
            'it_requirements'        => $request['it_requirements'],  
        ); 


        if($request['booking_type_menu'] === 'Internal'){
            $data['internal_option'] = $request['internal_menu']; 

        }else if($request['booking_type_menu'] === 'External'){
            $data['client_name']     = $request['booking_client_name'];
            $data['client_type']     =  $request['radiobutton_client_type']; 
            $data['client_number']   = $request['booking_engagement_number']; 
        }
 
        

        if($request['booking_option'] === 'Recurring'){ 
            
            return $this->recurringBooking($request, $data);  

        }else{

            return $this->singleBooking($request, $data); 

        }

        
    }




    public function recurringBooking($request, $data){

        $error_message               = '';
        $recurring_dates             = $this->getArrayCount($request['recurring_dates']); 
        $recurring_booked_dates      = '';
        $recurring_unavailable_dates = '';
        $counter                     = 0;
        foreach($recurring_dates as $recurring_date) {  

            if($recurring_date . ' ' .  $request['booking_start_time'] < date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime(date("Y-m-d H:i:s"))))){  
                $recurring_unavailable_dates = $recurring_unavailable_dates === '' ?  $recurring_date : $recurring_unavailable_dates . ', ' . $recurring_date;     
            }
            
            $data['start_time'] =  $recurring_date . ' ' .  $request['booking_start_time'];
            $data['end_time']   =  $recurring_date . ' ' .  $request['booking_end_time'];

            $validation =  $this->repository->validationRoomAvailability($data);  
            if(!empty($validation)){ 
                $error_message               = $error_message . " This room is no longer available for this date and time". $recurring_date . " " .  $request['booking_start_time'] .". Please select another.";
                $recurring_unavailable_dates = $recurring_unavailable_dates === '' ?  $recurring_date : $recurring_unavailable_dates . ', ' . $recurring_date;              
         
            } else{  
                    
                $result                      = $this->repository->addBooking($data);    
                if($result){ 
                    $participants_insert     = $this->insertParticipants($request, $result->id);  
                }else{ 
                    $error_message           = $error_message . " Error booking in date ". $recurring_date . ". Please try again.";   
                }  
                
                $counter++;
                $recurring_booked_dates      = $recurring_booked_dates === '' ?  $recurring_date : $recurring_booked_dates . ', ' . $recurring_date;
                
            }
         

        } 

        if($recurring_booked_dates === '') { return $this->returnJson('201', 'This room is no longer available for this date and time. Please select another.'); } // if there no available rooms

        if($recurring_dates !== $counter){

            $this->getAddBookingEmailRecurringDates($request, $result->id, $recurring_booked_dates, $recurring_unavailable_dates); 
 
            return $this->returnJson('200',  $recurring_unavailable_dates === '' ? "Room successfully booked for the following dates: " . $recurring_booked_dates
                : "Room successfully booked for the following dates: " . $recurring_booked_dates .  " Unfortunately, the room is unavailable for ". $recurring_unavailable_dates ." as it is already booked by someone else.");  
        }else{
            return $this->returnJson('201', $error_message);  
        }

    }



    public function singleBooking($request, $data){

        $data['start_time'] =  $request['booking_date'] . ' ' .  $request['booking_start_time'];
        $data['end_time']   =  $request['booking_date'] . ' ' .  $request['booking_end_time'];

        $validation         =  $this->repository->validationRoomAvailability($data); 
        
        if(!empty($validation)){ 
            return $this->returnJson('201', "This room is no longer available for this time. Please select another.");   
        } 
       
       
        $result =  $this->repository->addBooking($data);    
        if($result){

            $participants_insert = $this->insertParticipants($request, $result->id); 

            $this->getAddBookingEmailUser($request, $result->id);
 
            return $this->returnJson('200', "Room successfully booked"); 
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }

    }

    
 
    public function getAddBookingEmailRecurringDates($request, $id, $recurring_booked_dates, $recurring_unavailable_dates){
        $result =  $this->repository->getBookingById($id);
        $IT_email = [];

        if(count($this->getArrayCount($request['it_requirements'])) > 0  && $request['it_requirements'] != null ){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements

        if(auth()->user()->role === '1'){ 

            //EMAIL TO ADMIN   
            $result['booking_option']               = 'Recurring';
            $result['body']                         = 'Your booking for ' . $result->room->name . ' has been approved with the following dates: ';
            $result['subject']                      = 'Meeting Room Booking No. ' . $result->booking_number . ': Approved';
            $result['intro']                        = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ','; 
            $result['date']                         = null;
            $result['recurring_booked_dates']       = explode(',', preg_replace('/\s+/', '', $recurring_booked_dates));
            $result['recurring_unavailable_dates']  = explode(',', preg_replace('/\s+/', '', $recurring_unavailable_dates));
            $result['agenda']                       = explode(',', preg_replace('/\s+/', ' ', $result->agenda));
            $result['it_requirements']              = explode(',', preg_replace('/\s+/', ' ', $result->it_requirements));
            Mail::to(auth()->user()->email)->cc($this->getArrayCount(str_replace(auth()->user()->email . ',', '', $request['participants_email'])))->bcc($IT_email)->send(new Booking_email($result));

        }else{

            //EMAIL TO USER  
            $result['booking_option']               = 'Recurring';
            $result['body']                         = 'Thank you for booking ' . $result->room->name . ' with the following dates and time: ';
            $result['subject']                      = 'Meeting Room Booking No. ' . $result->booking_number . ': Pending for Approval';
            $result['intro']                        = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ','; 
            $result['date']                         = null;
            $result['recurring_booked_dates']       = explode(',', preg_replace('/\s+/', '', $recurring_booked_dates));
            $result['recurring_unavailable_dates']  = explode(',', preg_replace('/\s+/', '', $recurring_unavailable_dates));
            $result['agenda']                       = explode(',', preg_replace('/\s+/', ' ', $result->agenda));
            $result['it_requirements']              = explode(',', preg_replace('/\s+/', ' ', $result->it_requirements));
            // Mail::to(auth()->user()->email)->cc($this->getArrayCount(str_replace(auth()->user()->email . ',', '', $request['participants_email'])))->bcc($IT_email)->send(new Booking_email($result));
            Mail::to(auth()->user()->email)->bcc($IT_email)->send(new Booking_email($result));



            $records =  $this->repository->getAdminUsers();   
            foreach($records as $record) { 
                // EMAIL TO ADMIN 

                $result['body']         = auth()->user()->first_name . ' ' .  auth()->user()->last_name  .' has booked room ' . $result->room->name . ' with the following details: ';
                $result['intro']        = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ',';
                $result['link']         = 'You may approve or reject the booking by clicking here: ' . route('bookings');
                Mail::to($record->email)->send(new Booking_email($result)); 
           }


        }

        
    }



    public function getAddBookingEmailUser($request, $id){

        $result   =  $this->repository->getBookingById($id);   
        $IT_email = [];

        if(count($this->getArrayCount($request['it_requirements'])) > 0 && $request['it_requirements'] != null ){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements
         
        if(auth()->user()->role === '1'){
            //EMAIL TO ADMIN  
            $result['booking_option']      = 'Does Not Repeat';
            $result['subject']             = 'Meeting Room Booking No. ' . $result->booking_number . ': Approved';
            $result['intro']               = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ',';
            $result['date']                = date("Y-m-d", strtotime($result->start_time));
            $result['body']                = 'Your booking for ' . $result->room->name . ' has been approved! You may use the room for the following details: ';
            $result['agenda']              = explode(',', preg_replace('/\s+/', ' ', $result->agenda));
            $result['it_requirements']     = explode(',', preg_replace('/\s+/', ' ', $result->it_requirements));
            Mail::to(auth()->user()->email)->cc($this->getArrayCount(str_replace(auth()->user()->email . ',', '', $request['participants_email'])))->bcc($IT_email)->send(new Booking_email($result));
         
            
        }else{

            // EMAIL TO USER  
            $result['booking_option']      = 'Does Not Repeat';
            $result['subject']             = 'Meeting Room Booking No. ' . $result->booking_number . ': Pending for Approval';
            $result['intro']               = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ',';
            $result['body']                = 'Thank you for booking ' . $result->room->name . ' Your booking is now pending for approval. ';
            $result['date']                = date("Y-m-d", strtotime($result->start_time));
            $result['agenda']              = explode(',', preg_replace('/\s+/', ' ', $result->agenda));
            $result['it_requirements']     = explode(',', preg_replace('/\s+/', ' ', $result->it_requirements));
            // Mail::to(auth()->user()->email)->cc($this->getArrayCount(str_replace(auth()->user()->email . ',', '', $request['participants_email'])))->bcc($IT_email)->send(new Booking_email($result));
            Mail::to(auth()->user()->email)->bcc($IT_email)->send(new Booking_email($result));
             
 
            $records =  $this->repository->getAdminUsers();   
            foreach($records as $record) { 
                // EMAIL TO ADMIN 
                 
                $result['body']         =  auth()->user()->first_name . ' ' .  auth()->user()->last_name  .' has booked room ' . $result->room->name . ' with the following details: ';
                $result['link']         =  'You may approve or reject the booking by clicking here: ' . route('bookings');
                $result['intro']        = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ',';
                Mail::to($record->email)->send(new Booking_email($result)); 
           }
 
        }

        
    }




    public function editBooking($request)
    {  
        
        $data = array(
            'modified_by'            => auth()->user()->id, 
            'purpose'                => $request['booking_purpose'], 
            'approved_by'            => auth()->user()->role === '1' ? auth()->user()->id : null,
            'mode'                   => $request['radio_mode'],
            'type'                   => $request['booking_type_menu'], 
            'internal_option_others' => $request['internal_menu_others'], 
            'agenda'                 => $request['agenda'],
            'it_requirements'        => $request['it_requirements'],  
        ); 


        if($request['booking_type_menu'] === 'Internal'){ // add data internal or external option
            $data['internal_option'] = $request['internal_menu']; 

        }else if($request['booking_type_menu'] === 'External'){
            $data['client_name']     = $request['booking_client_name'];
            $data['client_type']     = $request['radiobutton_client_type']; 
            $data['client_number']   = $request['booking_engagement_number']; 
        }   
   
        
        $old_data               = $this->repository->getBookingById($request['booking_id']);   
        if($old_data->status === 2){
            $data['start_time'] = $request['booking_date'] . ' ' .  $request['booking_start_time'];
            $data['end_time']   = $request['booking_date'] . ' ' .  $request['booking_end_time']; 
            $data['room_id']    = $request['booking_room']; 
        } 
        $result                 = $this->repository->editBooking($data, $request['booking_id']);       

        if($result){
            
            $participants_edit  = $this->editParticipants($request, $request['booking_id']);  

            $IT_email           = []; 

            if(count($this->getArrayCount($request['it_requirements'])) > 0){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements
  
            
            $old_data['booking_option']   = 'Does Not Repeat';
            $old_data['subject']          = 'Meeting Room Booking No. ' . $old_data->booking_number . ': Reservation';
            $old_data['intro']            = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ',';
            $old_data['body']             = 'This is to inform you of the changes you have made in your booking for ' .  $old_data->room->name . '. You may participate in the meeting using the following details:';
            $old_data['date']             = date("Y-m-d", strtotime($old_data->start_time));
            $old_data['changes']          = $this->getEditBookingChanges($old_data, $result);
            $old_data['agenda']           = explode(',', preg_replace('/\s+/', ' ', $old_data->agenda));
            $old_data['it_requirements']  = explode(',', preg_replace('/\s+/', ' ', $old_data->it_requirements));
            $old_data['action']           = 'Edit';
  
            Mail::to(auth()->user()->email)->cc($this->getArrayCount($request['participants_email']))->bcc($IT_email)->send(new Booking_email($old_data));


            return $this->returnJson('200', "Booking information updated");   
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");   
        } 
        
    }





    public function rebookBooking($request)
    {  
        
        $data = array(
            'modified_by'            => auth()->user()->id, 
            'purpose'                => $request['booking_purpose'], 
            'approved_by'            => auth()->user()->role === '1' ? auth()->user()->id : null,
            'mode'                   => $request['radio_mode'],
            'type'                   => $request['booking_type_menu'], 
            'internal_option_others' => $request['internal_menu_others'], 
            'agenda'                 => $request['agenda'],
            'it_requirements'        => $request['it_requirements'], 
            'status'                 => '2'
        ); 
        
        $data['start_time'] = $request['booking_date'] . ' ' .  $request['booking_start_time'];
        $data['end_time']   = $request['booking_date'] . ' ' .  $request['booking_end_time']; 
        $data['room_id']    = $request['booking_room']; 


        if($request['booking_type_menu'] === 'Internal'){ // add data internal or external option
            $data['internal_option'] = $request['internal_menu']; 

        }else if($request['booking_type_menu'] === 'External'){
            $data['client_name']     = $request['booking_client_name'];
            $data['client_type']     = $request['radiobutton_client_type']; 
            $data['client_number']   = $request['booking_engagement_number']; 
        }   
   
        
        $old_data               = $this->repository->getBookingById($request['booking_id']);   

        if($old_data->status === 3){
            return $this->returnJson('201', "Booking [" . $old_data->booking_number . "]  has already been declined by " . $old_data->declined->first_name . ' ' . $old_data->declined->last_name); 
        }  else if($old_data->status === 1){
            return $this->returnJson('201', "Booking [" . $old_data->booking_number . "]  has already been approved by " . $old_data->approved->first_name . ' ' . $old_data->approved->last_name); 
        }  else if($old_data->status === 2){
            return $this->returnJson('201', "Booking [" . $old_data->booking_number . "]  is already pending"); 
        } 

 
        
        $result                 = $this->repository->editBooking($data, $request['booking_id']);       

        if($result){
            
            $participants_edit  = $this->editParticipants($request, $request['booking_id']);  

            $IT_email           = []; 

            if(count($this->getArrayCount($request['it_requirements'])) > 0){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements
  
            
            $old_data['booking_option']   = 'Does Not Repeat';
            $old_data['subject']          = 'Meeting Room Booking No. ' . $old_data->booking_number . ': Reservation';
            $old_data['intro']            = 'Dear ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name . ',';
            $old_data['body']             = 'You have successfully rebooked Booking No. ' . $old_data->booking_number . '. The following changes have been made:';
            $old_data['date']             = date("Y-m-d", strtotime($old_data->start_time));
            $old_data['changes']          = $this->getEditBookingChanges($old_data, $result);
            $old_data['agenda']           = explode(',', preg_replace('/\s+/', ' ', $old_data->agenda));
            $old_data['it_requirements']  = explode(',', preg_replace('/\s+/', ' ', $old_data->it_requirements));
            $old_data['action']           = 'Edit';
  
            Mail::to(auth()->user()->email)->cc($this->getArrayCount($request['participants_email']))->bcc($IT_email)->send(new Booking_email($old_data));


            return $this->returnJson('200', "Booking has been rebooked");   
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");   
        } 
        
    }




    public function getEditBookingChanges($old_data, $new_data){

        $changes          = []; 
        $new_participants = $this->repository->getBookingById($new_data->id);  

        date("Y-m-d", strtotime($old_data->start_time)) === date("Y-m-d", strtotime($new_data->start_time)) ? $changes['date']            = 'No changes'                                          : $changes['date']             = date("Y-m-d", strtotime($new_data->start_time));
        date("h:i A", strtotime($old_data->start_time)) === date("h:i A", strtotime($new_data->start_time)) ? $changes['start_time']      = 'No changes'                                          : $changes['start_time']       = date("h:i A", strtotime($new_data->start_time));
        date("h:i A", strtotime($old_data->end_time))   === date("h:i A", strtotime($new_data->end_time))   ? $changes['end_time']        = 'No changes'                                          : $changes['end_time']         = date("h:i A", strtotime($new_data->end_time));
        $old_data->room_id                              === $new_participants->room_id                      ? $changes['room_name']       = 'No changes'                                          : $changes['room_name']        = $new_participants->room->name;
        $old_data->mode                                 === $new_data->mode                                 ? $changes['mode']            = 'No changes'                                          : $changes['mode']             = $new_data->mode;
        $old_data->type                                 === $new_data->type                                 ? $changes['type']            = 'No changes'                                          : $changes['type']             = $new_data->type;
        $old_data->internal_option                      === $new_data->internal_option                      ? $changes['internal_option'] = 'No changes'                                          : $changes['internal_option']  = $new_data->internal_option;
        $old_data->client_name                          === $new_data->client_name                          ? $changes['client_name']     = 'No changes'                                          : $changes['client_name']      = $new_data->client_name;
        $old_data->client_type                          === $new_data->client_type                          ? $changes['client_type']     = 'No changes'                                          : $changes['client_type']      = $new_data->client_type;
        $old_data->client_number                        === $new_data->client_number                        ? $changes['client_number']   = 'No changes'                                          : $changes['client_number']    = $new_data->client_number;
        $old_data->purpose                              === $new_data->purpose                              ? $changes['purpose']         = 'No changes'                                          : $changes['purpose']          = $new_data->purpose;
        $old_data->agenda                               === $new_data->agenda                               ? $changes['agenda']          = explode(',', preg_replace('/\s+/', ' ', 'No changes')) : $changes['agenda']           = explode(',', preg_replace('/\s+/', ' ', $new_data->agenda));
        $old_data->it_requirements                      === $new_data->it_requirements                      ? $changes['it_requirements'] = explode(',', preg_replace('/\s+/', ' ', 'No changes')) : $changes['it_requirements']  = explode(',', preg_replace('/\s+/', ' ', $new_data->it_requirements));
 
        
        $old_participants_data        = [];
        $new_participants_data        = [];

        if(count($old_data->participants) !== count($new_participants->participants)){
            $changes['participants']  = $new_participants->participants;

        }else{  

            $counter = 0;
            foreach($old_data->participants as $participant) {    

                $participant->user                                     ? array_push($old_participants_data, $participant->user->id)                                  : array_push($old_participants_data, $participant->guest_email); 
                $new_participants->participants[$counter]['user']      ? array_push($new_participants_data, $new_participants->participants[$counter]['user']['id']) : array_push($new_participants_data, $new_participants->participants[$counter]['guest_email']); 
                $counter ++; 

            }  
            $counter = 0;
            array_diff($old_participants_data, $new_participants_data) ? $changes['participants'] = $new_participants->participants                                  : $changes['participants'] = null; 
             
        }
  
        return $changes;
    }


      

    public function getArrayCount($data){
        return explode(',', preg_replace('/\s+/', '', $data) ); 
    }


    
 
    public function insertParticipants($request, $booking_id){

        
        $participants_ids    = $this->getArrayCount($request['participants_id']); 
        $participants_emails = $this->getArrayCount($request['participants_email']);
        // array_push($participants_ids, auth()->user()->id); 

        $counter = 0;
        foreach($participants_ids as $participants_id) {    

            $participants_id != '0' ?   $data = array(
                    'user_id'     =>  $participants_id,
                    'booking_id'  => $booking_id,  
                ) : $data = array(
                    'guest'       =>  '1',
                    'guest_email' => $participants_emails[$counter],
                    'booking_id'  => $booking_id,  
                );    
            

            $result =  $this->repository->addParticipants($data);  
            $counter ++;
        } 

        return true;
    }



    
    public function editParticipants($request, $booking_id){
 
        $participants_ids    = $this->getArrayCount($request['participants_id']); 
        $participants_emails = $this->getArrayCount($request['participants_email']);
         
        $counter = 0;
        $member_id = [];
        $guest_email = [];
 
        foreach($participants_ids as $participants_id) {    
 
           if($participants_id != '0'){ // check if participant is a guest or a member
                $data = array(
                    'user_id'     =>  $participants_id,
                    'booking_id'  => $booking_id,  
                );
                if(!in_array($participants_id, $member_id)){array_push($member_id, $participants_id);}
                
           }else{ 
                $data = array(
                    'guest'       =>  '1',
                    'guest_email' => $participants_emails[$counter],
                    'booking_id'  => $booking_id,  
                );    
                array_push($guest_email, $participants_emails[$counter]); 
           } 

            $result =  $this->repository->checkIfParticipantsExists($booking_id, $participants_emails[$counter], $participants_id);  //check if participant already exists     
        
            if(count($result) === 0){ $add_participants = $this->repository->addParticipants($data); } // add participant if does not exists 

            $counter ++;
        }   

        array_push($member_id, auth()->user()->id);  
        $query = $this->repository->deleteParticipants($booking_id, $guest_email, $member_id); // delete participants who are not included in the updated participant list
         
        return true;
    }



    
    
    public function approveBooking($request)
    {  
        $status =  $this->repository->getBookingById($request['id']);   

        if($status->status === 1){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "] has already been approved by " . $status->approved->first_name . ' ' . $status->approved->last_name); 
        } else if($status->status === 3){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "] has already been declined by " . $status->declined->first_name . ' ' . $status->declined->last_name); 
        }  else if($status->status === 4){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "] is already canceled"); 
        } 

         
        $result =  $this->repository->approveBooking($request);  
        
        if($result){
 
            
            $participants = []; 
            foreach($result->participants as $participant) {    
                if($participant->guest === 0 ){
                    if(trim($participant->user->email) != trim($result->user->email)){ 
                        array_push($participants, $participant->user->email);
                    }
                }else{
                    array_push($participants, $participant->guest_email);
                } 
            }   
            
            $IT_email                      = [];  
            
            if(count($this->getArrayCount($status['it_requirements'])) > 0 && $status['it_requirements'] != null ){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements
            $result['booking_option']      = 'Does Not Repeat';
            $result['subject']             = 'Meeting Room Booking No. ' . $result->booking_number . ': Approved';
            $result['intro']               = 'Dear ' . $result->user->first_name . ' ' .  $result->user->last_name . ',';
            $result['date']                = date("Y-m-d", strtotime($result->start_time));
            $result['body']                = 'Your booking for ' . $result->room->name . ' has been approved! You may use the room for the following details: ';
            $result['agenda']              = explode(',', preg_replace('/\s+/', ' ', $result->agenda));
            $result['it_requirements']     = explode(',', preg_replace('/\s+/', ' ', $result->it_requirements));

            Mail::to($result->user->email)->cc($participants)->bcc($IT_email)->send(new Booking_email($result)); 


             
            return $this->returnJson('200', "Booking has been approved");   
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }

    
    

    public function declineBooking($request)
    { 
        
        $status =  $this->repository->getBookingById($request['id']); 
        if($status->status === 1){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "]  has already been approved by " . $status->approved->first_name . ' ' . $status->approved->last_name); 
        } else if($status->status === 3){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "]  has already been declined by " . $status->declined->first_name . ' ' . $status->declined->last_name); 
        }  else if($status->status === 4){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "]  is already canceled"); 
        } 

        $result =  $this->repository->declineBooking($request); 
       
        if($result){

              
            $IT_email                      = [];  

            if(count($this->getArrayCount($status['it_requirements'])) > 0 && $status['it_requirements'] != null ){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements
            $result['subject']             = 'Meeting Room Booking No. ' . $result->booking_number . ': Declined';
            $result['intro']               = 'Dear ' . $result->user->first_name . ' ' .  $result->user->last_name . ',';
            $result['body']                = 'Apologies, but your booking was declined. You may book other rooms, if there are any available.';
            $result['action']              = 'Decline';
            
            $participants = []; 
            foreach($result->participants as $participant) {    
                if($participant->guest === 0 ){
                    if(trim($participant->user->email) != trim($result->user->email)){ 
                        array_push($participants, $participant->user->email);
                    }
                }else{
                    array_push($participants, $participant->guest_email);
                } 
            }  
  
            Mail::to($result->user->email)->cc($participants)->bcc($IT_email)->send(new Booking_email($result)); 

 
            return $this->returnJson('200', "Booking has been declined");  
        }else{
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }




    
    public function cancelBooking($request)
    { 
        
        $status =  $this->repository->getBookingById($request['id']); 
        
        if($status->status === 3){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "]  has already been declined by " . $status->declined->first_name . ' ' . $status->declined->last_name); 
        }  else if($status->status === 4){
            return $this->returnJson('201', "Booking [" . $status->booking_number . "]  is already canceled"); 
        } 

        $result =  $this->repository->cancelBooking($request); 
       
        if($result){
 
            $IT_email                      = []; 
            if(count($this->getArrayCount($status['it_requirements'])) > 0 && $status['it_requirements'] != null ){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements
 
            $result['subject']             = 'Meeting Room Booking No. ' . $result->booking_number . ': Canceled';
            $result['intro']               = 'Dear ' . $result->user->first_name . ' ' .  $result->user->last_name . ',';
            $result['body']                = 'Apologies, but your booking for '  . $result->room->name . ' has been cancelled by ' . auth()->user()->first_name . ' ' .  auth()->user()->last_name  . '. For more information, you may contact the Management.';
            $result['action']              = 'Cancel';
            
            $participants = []; 
            foreach($result->participants as $participant) {    
                if($participant->guest === 0 ){
                    if(trim($participant->user->email) != trim($result->user->email)){ 
                        array_push($participants, $participant->user->email);
                    }
                }else{
                    array_push($participants, $participant->guest_email);
                } 
            }  
 
            Mail::to($result->user->email)->cc($participants)->bcc($IT_email)->send(new Booking_email($result)); 
 

 
            return $this->returnJson('200', "Booking has been canceled");  
        }else{
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }



    
    
    public function getParticipants($request)
    {  
        $records =  $this->repository->getParticipants($request); 
        $data_arr = array(); 

        foreach($records as $record) {


           $data_arr[] = array(
               "id"          => $record->id,
               "first_name"  => $record->first_name,
               "middle_name" => $record->middle_name,
               "last_name"   => $record->last_name,
               "email"       => $record->email,
               "role"        => $record->role === '1' ? 'Admin' : 'User', 
               "created_at"  =>  date_format($record->created_at,"Y-m-d H:i:s"),  
               "status"      => $record->status,  
           );
       }
         
        return $this->returnJson('200', $data_arr);  
    }



    
    public function getBookingById($id)
    {   
        $result =  $this->repository->getBookingById($id); 
        
        if($result){
            
            $events = array();  
 
  
            $events = array( 
                "id"                     => $result->id, 
                "room_id"                => $result->room_id,
                "room_name"              => $result->room->name, 
                "booking_number"         => $result->booking_number ? $result->booking_number : '',
                "purpose"                => $result->purpose,
                "start_time"             => date("Y-m-d h:i A", strtotime($result->start_time)),
                "start"                  => $result->start_time,
                "end_time"               => date("Y-m-d h:i A", strtotime($result->end_time)),
                "end"                    => $result->end_time,
                "status"                 => $this->getStatus($result->status),
                         
                "participants"           => $result->participants,
                     
                "mode"                   => $result->mode,
                "type"                   => $result->type,
                
                "internal_option"        => $result->internal_option,
                "internal_option_others" => $result->internal_option_others === null ? '' : $result->internal_option_others,

                "client_number"          => $result->client_number,
                "client_name"            => $result->client_name,
                "client_type"            => $result->client_type,
                "client_type_others"     => $result->client_type_others === null ? '' : $result->client_type_others,

                "agenda"                 => $result->agenda, 
                "it_requirements"        => $result->it_requirements, 
                "it_requirements_others" => $result->it_requirements_others === null ? '' : $result->it_requirements_others,  

                "added_by"               => $result->user->first_name . ' ' . $result->user->last_name, 
                "approved_by"            => $result->approved ? $result->approved->first_name . ' ' . $result->approved->last_name : '',  
                "created_at"             =>  date_format($result->created_at,"Y-m-d h:i A") ,   

                "title"                  => $result->room->name .  ' [' . $result->user->first_name . ']',
                "color"                  => $result->room->room_color, 
            ); 
 
            return $this->returnJson('200', $events); 
        }else{
            return $this->returnJson('201', "An error occured. Please try again.");  
        }

    }




    public function getPastPendingBooking()
    {
        
        $results =  $this->repository->getPastPendingBooking();    
        foreach($results as $record) {

            $query =  $this->repository->declineBookingAutomatically($record->id); 
             
            // $details = [
            //     'subject' => 'Meeting Room Reservation System',  
            //     'title'   => 'Dear  ' . $record->user->first_name . ' ' .  $record->user->last_name . ',',  
            //     'body'    => 'Apologies, but your booking was declined. You may book other rooms, if there are any available.',  
            // ]; 
             
            
            // $participants = []; 
            // foreach($record->participants as $participant) {  
            //     $participant->guest === 0 ?  array_push($participants, $participant->user->email) : array_push($participants, $participant->guest_email);
            // }  
  
            // Mail::to($record->user->email)->cc($participants)->send(new Booking_email($details));

            $IT_email                      = []; 
            if(count($this->getArrayCount($request['it_requirements']))> 0){ $IT_email = $this->repository->getITEmail()->toArray(); } // get IT Email if there is it requirements

            $query['subject']             = 'Meeting Room Booking No. ' . $query->booking_number . ': Declined';
            $query['intro']               = 'Dear ' . $query->user->first_name . ' ' .  $query->user->last_name . ',';
            $query['body']                = 'Apologies, but your booking was declined. You may book other rooms, if there are any available.';
            $query['action']              = 'Decline';
            
            $participants = []; 
            foreach($query->participants as $participant) {  
                $participant->guest === 0 ?  array_push($participants, $participant->user->email) : array_push($participants, $participant->guest_email);
            }  
  
            Mail::to($query->user->email)->cc($participants)->bcc($IT_email)->send(new Booking_email($query)); 


        }
     
        dd('Success!');
    }


}

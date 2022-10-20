<?php

namespace App\Constants;
 
class BookingsConstants
{ 
    
    const booking_status = [
        '1' => 'approved',
        '2' => 'pending' ,
        '3' => 'declined',
        '4' => 'canceled'
    ];

    
    const booking_option = [
        '0' => 'Recurring',
        '1' => 'Does Not Repeat' 
    ]; 
    
    const booking_type = [
        '0' => 'Internal',
        '1' => 'External' 
    ];
    
    const is_archived = [
        '1' => 'Yes',
        '2' => 'No' 
    ];
}

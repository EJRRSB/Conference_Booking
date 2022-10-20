<?php

namespace App\Constants;
 
class UserConstants
{ 
    
    const user_status = [
        '1' => 'approved',
        '2' => 'pending',
        '3' => 'declined'
    ];
     
    const user_roles = [
        '1' => 'admin',
        '2' => 'user',
        '3' => 'system'
    ];
     
    const is_IT = [
        '1' => 'No',
        '2' => 'Yes' 
    ];
}

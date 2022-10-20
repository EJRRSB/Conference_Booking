<?php

namespace App\Services;
 

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
  
use App\Models\User;
// use DateTime;
 
class BaseService 
{ 
    public function __construct()
    {
        
    }
 

    public function returnJson($statusCode, $message){
        return json_encode(array(
            "statusCode" => $statusCode,
            "message"    => $message
        ));
    }
     

}

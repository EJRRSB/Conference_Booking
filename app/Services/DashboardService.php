<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
  
use App\Models\User;
// use DateTime;
 
class DashboardService extends BaseService
{ 
    public function __construct()
    {
        $this->repository = new DashboardRepository();
    }
 

    
    public function getCountsData($request)
    {  
        $result =  $this->repository->getCountsData($request); 
       
        if($result){ 
            echo json_encode(array(
				"statusCode" => 200,
				"data"       => $result
			)); 
        }else{
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }
     
    
    public function getChartData($request)
    { 
        $result =  $this->repository->getChartData($request); 
       
        if($result){
            echo json_encode(array(
				"statusCode" => 200,
				"data"       => $result
			));
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }
}

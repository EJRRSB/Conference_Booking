<?php

namespace App\Http\Controllers;
use App\Models\User;

use DB; 
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Services\DashboardService;   
use App\Repositories\DashboardRepository;
use Validator;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{   
    public function __construct(DashboardRepository $repository)
    {
        $this->middleware('auth');
        $this->service = new DashboardService($repository);
    }
  

 
    public function getCountsData(Request $request)
    {     
        return $this->service->getCountsData($request); 
    }

 
    public function getChartData(Request $request)
    {      
        return $this->service->getChartData($request); 
    }

 

    
}

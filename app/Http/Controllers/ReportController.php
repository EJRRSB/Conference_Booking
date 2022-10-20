<?php

namespace App\Http\Controllers;
// use App\Models\User;

use DB; 
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Services\ReportService;   
use App\Repositories\ReportRepository;
use Validator;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{ 
    public function __construct(ReportRepository $repository)
    {
        $this->middleware('auth');
        $this->service = new ReportService($repository);
    }
 
    public function index()
    {
        // if(auth()->user()->role !== '1'){
        //     return redirect('/bookings');
        // }
        return view('admin.reports');
    } 
 
     

    
    // AJAX REQUEST
    public function ListOfBookingsByStatus(Request $request)
    {    
        return $this->service->ListOfBookingsByStatus($request); 
    }
  
    
    // AJAX REQUEST
    public function ListOfArchivedBookings(Request $request)
    {    
        return $this->service->ListOfArchivedBookings($request); 
    }


    
    // AJAX REQUEST
    public function ListOfUsersByStatus(Request $request)
    {    
        return $this->service->ListOfUsersByStatus($request); 
    }

    // AJAX REQUEST
    public function ListOfAllRooms(Request $request)
    {    
        return $this->service->ListOfAllRooms(); 
    }
    
}

<?php

namespace App\Http\Controllers;
use App\Models\User;

use DB; 
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Services\CalendarService;   
use App\Repositories\CalendarRepository;
use Validator;
use Illuminate\Validation\Rule;

class CalendarController extends Controller
{ 
    public function __construct(CalendarRepository $repository)
    {
        $this->middleware('auth');
        $this->service = new CalendarService($repository);
    }
  

 
    public function index()
    {
        return view('admin.calendar');
    }
  

    public function getCalendarInfo(Request $request)
    {      
        return $this->service->getCalendarInfo($request);
    }
 

    public function getAllCalendarRooms(Request $request)
    {       
        return $this->service->getAllCalendarRooms();         
 
    }
    
}

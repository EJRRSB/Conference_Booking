<?php

namespace App\Services;

use App\Repositories\RoomRepository;

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
  
use App\Models\User;
// use DateTime;
 
class RoomService extends BaseService
{ 
    public function __construct()
    {
        $this->repository = new RoomRepository();
    }
 
    
     public function getAllRooms($request)
     {    

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
            0 => 'name', 
         ); 
         
        
         $sortColumnName = $sortColumns[$order_arr[0]['column']];
         // Total record count
         $totalRecords =  $this->repository->getRoomCount();
  
         // Total record count with search
         $totalRecordswithFilter = $this->repository->getTotalCountWithSearch($searchValue);
  
 
         // Total records with search
         $records = $this->repository->getTotalRecordsWithSearch($searchValue, $sortColumnName, $columnSortOrder, $start, $rowpage);
             
 
         $data_arr = array(); 

         foreach($records as $record) {
 

            $data_arr[] = array(
                "id"          => $record->id,
                "name"        => $record->name,
                "description" => $record->description,
                "room_color"  => $record->room_color,
                "added_by"    => $record->user->first_name . ' ' . $record->user->last_name, 
                "created_at"  =>  date_format($record->created_at,"Y-m-d h:i A"),   
            );
        }
         
        return json_encode(
            array(
                "draw" => intval($draw), 
                "recordsTotal"    => intval($totalRecords),
                "recordsFiltered" => intval($totalRecordswithFilter),
                "aaData" => $data_arr 
            )
        );
    }
 



    
    
    public function deleteRoom($request)
    { 
        $result =  $this->repository->deleteRoom($request); 
       
        if($result){ 
            return $this->returnJson('200', "Room has been deleted");  
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }

 

    
    public function addRoom($request)
    {  
        $data = array(
            'name'        => $request['room_name'],
            'description' => $request['room_description'],
            'room_color'  => $request['room_color'],
            'added_by'    => auth()->user()->id, 
        );
        $result =  $this->repository->addRoom($data); 
       
        if($result){ 
            return $this->returnJson('200', "Room has been added");
        }else{
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }


    
    public function getRoom($id)
    {  
        return json_encode(array(
            "statusCode" => 200,
            "data" => $this->repository->getRoom($id)
        ));
        
    }

    
    

    
    public function updateRoom($request)
    {   
        $data = array(
            'name'        => $request['room_name'],
            'description' => $request['room_description'],
            'modified_by' => auth()->user()->id, 
        );
        $result =  $this->repository->updateRoom($data, $request['room_id']); 
       
        if($result){ 
            return $this->returnJson('200', "Room has been updated");
        }else{
            
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }
}

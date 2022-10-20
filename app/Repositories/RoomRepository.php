<?php

namespace App\Repositories;
   
use Carbon\Carbon;
use DB;
use App\Models\User;
use App\Models\Room;
 

class RoomRepository 
{ 
    public function __construct()
    {
        $this->model = new Room();
    }
 
   
    public function getRoomCount(){
        return Room::select('count(*) as allcount')->count(); 
    }

    public function getTotalCountWithSearch($searchValue){
        return Room::select('count(*) as allcount')->where('name','like','%' . $searchValue . '%')->count();
    }

    public function getTotalRecordsWithSearch($searchValue, $sortColumnName, $columnSortOrder, $start, $rowpage){
        return Room::select('rooms.*')->with('user') 
                             ->where('name','like','%' . $searchValue . '%')
                             ->orderBy($sortColumnName,$columnSortOrder)
                             ->skip($start)
                             ->take($rowpage)
                             ->get(); 
    }
  
    
    
    public function deleteRoom($request)
    {
        $this->model->findOrFail($request['id'])->update(['deleted_by' => auth()->user()->id]); 
        return $this->model->findOrFail($request['id'])->delete();  
    }

    public function addRoom($data)
    { 
        return $this->model->insert($data);  
    }
     
    
    public function getRoom($id)
    {
        return $this->model->select('*')->where('id', $id)->first();
    }
    
    
    public function updateRoom($data, $id)
    {    
        $result = $this->model->findOrFail($id);
        return $result->update($data); 
    }
}

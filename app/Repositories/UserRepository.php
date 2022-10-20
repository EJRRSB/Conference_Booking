<?php

namespace App\Repositories;
   
use Carbon\Carbon;
use DB;
use App\Models\User;
 

class UserRepository 
{ 
    public function __construct()
    {
        $this->model = new User();
    }
 
   
    public function getUserCount($status){
        return User::select('count(*) as allcount')->where('role','!=', '3')->where('status', $status)->count(); 
    }

    public function getTotalCountWithSearch($searchValue, $status){
        return User::select('count(*) as allcount')->where('first_name','like','%' . $searchValue . '%')->where('role','!=', '3')->where('status', $status)->count();
    }

    public function getTotalRecordsWithSearch($searchValue, $sortColumnName, $columnSortOrder, $start, $rowpage, $status){
        return User::select('users.*')
                             ->where('first_name','like','%' . $searchValue . '%')
                             ->where('status', $status)
                             ->where('role','!=', '3')
                             ->orderBy($sortColumnName,$columnSortOrder)
                             ->skip($start)
                             ->take($rowpage)
                             ->get(); 
    }
 



    
    
    public function deleteUser($request)
    {
        $query = $this->model->findOrFail($request['id']);
        
        if($query->delete()){
            return $query;
        }else{
            return null;
        }
        
    }

    
    public function addUser($data)
    { 
        return $this->model->insert($data);  
    }
    
    public function getUser($id)
    {
        return $this->model->select('*')->where('id', $id)->first();
    }

    
    
    public function updateUser($data, $id)
    {   
        $result = $this->model->findOrFail($id);
        if($result->update($data)){
            return $result;
        }else{
            return null;
        }
    }


    public function checkEmployeeExist($data)
    {  
        return $this->model->where([ 
            'first_name'  => $data['first_name'], 
            'middle_name' => $data['middle_name'],
            'last_name'   => $data['last_name']])->first();
         
    }

    public function validateWorkEmailAddress($data)
    {
        return $this->model->where([
            'email' => $data['email']])
            ->first();
    }
 
}

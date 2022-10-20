<?php

namespace App\Services;

use App\Repositories\UserRepository;

// use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
  
use App\Models\User;
// use DateTime;
use Validator; 
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception as PhpSpreadsheetError;

use Illuminate\Support\Str;

use App\Mail\User_email;
use Illuminate\Support\Facades\Mail;


class UserService extends BaseService
{ 
    const TOTAL_FILE_UPLOAD_COLUMN = 6;
 

    const FILE_HEADER = [ 
        'first name', 
        'middle name', 
        'last name', 
        'email address', 
        'user type', 
        'phone number'
    ];

    public function __construct()
    {
        $this->repository = new UserRepository();
    }
 
     // AJAX REQUEST
    public function getAllUser($request)
    {    

        $status = $request->get('status');
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
            0 => 'first_name', 
         ); 
         
        
         $sortColumnName = $sortColumns[$order_arr[0]['column']];
         // Total record count
         $totalRecords =  $this->repository->getUserCount($status);
  
         // Total record count with search
         $totalRecordswithFilter = $this->repository->getTotalCountWithSearch($searchValue, $status);
  
 
         // Total records with search
         $records = $this->repository->getTotalRecordsWithSearch($searchValue, $sortColumnName, $columnSortOrder, $start, $rowpage, $status);
                              
 
         $data_arr = array(); 

         foreach($records as $record) {
 

            $data_arr[] = array(
                "id"          => $record->id,
                "first_name"  => $record->first_name,
                "middle_name" => $record->middle_name,
                "last_name"   => $record->last_name,
                "email"       => $record->email,
                "role"        => $record->role === '1' ? 'Admin' : 'User', 
                "created_at"  =>  date_format($record->created_at,"Y-m-d h:i A"),  
                "status"      => $record->status,  
            );
        }
         
        return json_encode(
            array(
                "draw"            => intval($draw), 
                "recordsTotal"    => intval($totalRecords),
                "recordsFiltered" => intval($totalRecordswithFilter),
                "aaData"          => $data_arr 
            )
        );
    }
 

    
    public function deleteUser($request)
    { 
        $result =  $this->repository->deleteUser($request); 
       
        if($result){
            
            if($result->status === 2){
                $details = [
                    'subject' => 'Meeting Room Reservation System - Approved User Registration ',  
                    'title'   => 'Dear  ' . $result->first_name . ' ' .  $result->last_name . ',',  
                    'body'    => 'Apologies, but your registration has been declined. Kindly contact us for further information. Thank you.',  
                ]; 
                
                Mail::to($result->email)->send(new User_email($details));
            }
 
            return $this->returnJson('200', "User has been deleted");  
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }


    
    public function addUser($request)
    {  
        $password = Str::random(10);
        $data = array(
            'first_name'   => $request['first_name'],
            'middle_name'  => $request['middle_name'],
            'last_name'    => $request['last_name'],
            'role'         => $request['role'],
            'status'       => '1',
            'phone_number' => $request['phone_number'],
            'email'        => $request['email'],
            'password'     => Hash::make($password), 
        );
        $result =  $this->repository->addUser($data); 
       
        if($result){ 
            $details = [
                'subject'     => 'Meeting Room Reservation System - User Registration for Approval',  
                'title'       => 'Dear  ' . $request['first_name'] . ' ' .  $request['last_name'] . ',',  
                'body'        => 'You have successfully registered to the Meeting Room Reservation System. Your registration is now pending for approval.',  
                'credentials' => 'Here are your credentials: ',  
                'email'       => 'Email: ' . $request['email'],  
                'password'    => 'Password: ' . $password
            ]; 
            
            Mail::to($request['email'])->send(new User_email($details));

            return $this->returnJson('200', "User has been added");
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }


    
    public function getUser($id)
    {  
        return json_encode(array(
            "statusCode" => 200,
            "data" => $this->repository->getUser($id)
        ));
        
    }

    

    
    public function updateUser($request)
    {  
        $data = array(
            'first_name'   => $request['first_name'],
            'middle_name'  => $request['middle_name'],
            'last_name'    => $request['last_name'],
            'role'         => $request['role'],
            'phone_number' => $request['phone_number'],
            'email'        => $request['email'],
            'password'     => Hash::make('12345678'), 
        );
        $result =  $this->repository->updateUser($data, $request['id']); 
       
        if($result){ 
            return $this->returnJson('200', "User has been updated");
        }else{
            return $this->returnJson('201', "An error occured. Please try again.");  
        }
    }

    
    public function approveUser($request)
    { 
        
        $password = Str::random(10);
        $data = array(
            'status'      => '1',
            'approved_by' => auth()->user()->id, 
            'password'    => Hash::make($password), 
        );
 
        $result =  $this->repository->updateUser($data, $request['id']); 
       
        if($result){

            $details = [
                'subject'     => 'Meeting Room Reservation System - Approved User Registration',  
                'title'       => 'Dear  ' . $result->first_name . ' ' .  $result->last_name . ',',  
                'body'        => 'Your registration has been approved. You can now login in Meeting Room Reservation System.',  
                'credentials' => 'Here are your credentials: ',  
                'email'       => 'Email: ' . $result->email,  
                'password'    => 'Password: ' . $password                
            ]; 

             
            
            Mail::to($result->email)->send(new User_email($details));
 
            return $this->returnJson('200', "User has been approved");
        }else{            
            return $this->returnJson('201', "An error occured. Please try again.");
        }
    }



    
    public function BulkUploadUser($request)
    {  
        $fileData = $request->file('file');  
        if($this->checkfileExtension($fileData->extension()) === false){
            return json_encode(array(
				"statusCode" => 201,
				"message"    => "File extension must be xlsx"
			));
        }

        $reader = new ReaderXlsx();
        $spreadsheet = $reader->load($fileData);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();  
        
        if($this->checkHeader(array_filter($sheetData[0])) === false){ 
            return $this->returnJson('201', "Invalid upload file Header.");
        }

        $recordList = $this->getRowRecords($sheetData);  
        $validation_errors = $this->validateRecords($recordList);
 
        if ($validation_errors['total_row_with_error'] > 0) { 
            return $this->returnJson('202', $validation_errors['error_list']);
        }  

        $counter = 1;
        foreach ($recordList as $employee) { 


            $data = array(
                'first_name'   => $employee['first_name'],
                'middle_name'  => $employee['middle_name'],
                'last_name'    => $employee['last_name'],
                'role'         =>  $this->checkRole($employee['role']),
                'status'       =>  '1',
                'phone_number' =>  $employee['phone_number'],
                'email'        => $employee['email'],
                'password'     => Hash::make('12345678'), 
            ); 
            
            $result = $this->repository->addUser($data);

            if (is_array($result) === true) {   
                return $this->returnJson('201', 'Failed to store employee at row ' . $counter);
            } 
            $counter++;
        } 
        return $this->returnJson('200', '(' . $validation_errors['total_row'] . ') users has been added');
    }

    private function checkRole($role)
    {
        return strtolower($role) === 'admin' ? '1' : '2';
    }

    private function checkfileExtension($fileExtension)
    {
        return in_array(strtolower($fileExtension), ['xlsx']) === true ? true : false;
    }

    private function checkHeader($header)
    {
        $formatHeader = array_map('strtolower', $header);

        return count($formatHeader) === self::TOTAL_FILE_UPLOAD_COLUMN || 
            empty(array_diff($formatHeader, self::FILE_HEADER)) === true ? true : false; 
    }

    private function getRowRecords($sheetData)
    {
        unset($sheetData[0]);
        $users_list = [];

        if (empty($sheetData) === false) {
            foreach ($sheetData as $data) {
                if ($this->containsOnlyNull($data) === true) {
                    continue;
                }

                $row = [];
     
                $row['first_name']   = $data[0];
                $row['middle_name']  = $data[1];
                $row['last_name']    = $data[2];
                $row['email']        = $data[3];
                $row['role']         = $data[4];
                $row['phone_number'] = $data[5]; 
    
                array_push($users_list, $row);
            }
        }

        return $users_list;
    }

    function containsOnlyNull($record)
    {
        return empty(array_filter($record, function ($a) { return $a !== null;}));
    }


    private function validateRecords($recordList)
    {
        $newRecordList = [];
        $compareList = [];
        $rowWithError = 0;
        $rowNumber = 2;

        foreach($recordList as $record) {
            $validated = Validator::make($record, $this->setValidationRules());
            $record['row_no'] = $rowNumber;
            $record['error'] = [];

            if ($validated->fails()) {

                $record['error'] = array_merge($record['error'], $validated->messages()->get('*'));   
            } 

            if (in_array($record, $compareList) === true) {
                $record['error'] = array_merge($record['error'], ['duplicate' => ['This is duplicate.']]); 
            } else {
                array_push($compareList, $record);
            }
            
            $result = $this->checkInputs($record);
            $record['error'] = array_merge($record['error'], $result); 
            
            if (empty($record['error']) === false) {
                $record['error'] = array_merge($record['error'], ['row_number' => $rowNumber ]); 
                $rowWithError++;
            }

            array_push($newRecordList, $record['error']); 
            $rowNumber++;
        }
        
        return [
            'total_row'            => count($recordList),
            'total_row_with_error' => $rowWithError,
            'error_list'           => $newRecordList
        ];
    }

    private function setValidationRules()
    {
        return [ 
            'first_name'            => ['required', 'min:1', 'max:50'],
            'middle_name'           => ['nullable', 'min:1', 'max:50'],
            'last_name'             => ['required', 'min:1', 'max:50'],
            // 'role'               => ['role', 'min:1', 'max:10'],
            'email'                 => ['required', 'min:7', 'max:254', 'email:rfc'], 
            'phone_number'          => ['nullable','regex:/^([0-9\s\-\+\(\)]*)$/','min:7','max:20' ],
        ]; 
    }


    private function checkInputs($data)
    {
        $inputError = [];   

        if ($this->repository->checkEmployeeExist($data) !== null) {
            $inputError['duplicate'] = ['Employee already exist.'];
        }

        if (empty($data['email']) === false && $this->repository->validateWorkEmailAddress($data) !== null) {
            $inputError['exist'] = ['Email address is already taken.'];
        } 
 
        return $inputError;
    }


    
    
    public function changePassUser($request)
    {  
        $data = array(
            'password' => Hash::make($request['new_password']), 
        ); 
  
        if(Hash::check($request['current_password'], auth()->user()->password) === false){ 
            return $this->returnJson('202', ['password' => ['The current password is wrong ']]);
        }

        $result =  $this->repository->updateUser($data, auth()->user()->id); 
       
        if($result){ 
            return $this->returnJson('200', "Password has been updated");
        }else{ 
            return $this->returnJson('201', "An error occured. Please try again.");
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class mainModel extends Model
{
    public static $currentDate;
    public static $currentTime;
    protected static function booted()
    {
        parent::booted();
        date_default_timezone_set('Asia/Kolkata');
        static::$currentDate = date('Y-m-d');
        static::$currentTime = date('H:i:s');
    }

    public function Authentication_bk($data)
    {
        $username = $data['username'];
        $passwords = $data['passwords'];
        $enpasswords = Crypt::encrypt($passwords);

        $user = DB::table('tbl_users')->where(['flag' => 'Show', 'email' => $username,'role_id'=>'1'])->get()->count();
        $aaryofDetails = [];
        if ($user != 0) {
            $getDetails = DB::table('tbl_users')->where(['flag' => 'Show', 'email' => $username])->get()->first();
            foreach ($getDetails as $key => $value) {
                $aaryofDetails[$key] = $value;
            }
           
        } else {
            $User = DB::table('tbl_users')->where(['flag' => 'Show', 'email' => $username, 'password' => $enpasswords,'role_id'=>'2'])->get()->count();            
            if ($User != 0) {
                $getallDetails = DB::table('tbl_users')->where(['flag' => 'Show', 'email' => $username, 'password' => $enpasswords])->get()->first();
                foreach ($getallDetails as $key => $value) {
                    $aaryofDetails[$key] = $value;
                }                               
            }
        }
        return $aaryofDetails;
    }

    public function Authentication($data)
    {
        $username = $data['username'];
        $passwords = $data['passwords'];                    
        $aaryofDetails = [];
        $fetchconditions = ['flag' => 'Show','email' => $username];
        $edituserdetails = $this->getFirstRecord($fetchconditions, 'tbl_users');          
        if(!empty($edituserdetails)){
            $storedPassword = Crypt::decrypt($edituserdetails->password);   
            if ($storedPassword == $passwords) {                
                if ($edituserdetails->role_id == '1') { 
                    foreach ($edituserdetails as $key => $value) {
                        $aaryofDetails[$key] = $value;
                    }
                } elseif ($edituserdetails->role_id == '2') {                    
                    foreach ($edituserdetails as $key => $value) {
                        $aaryofDetails[$key] = $value;
                    }
                }
            }
        }         
        return $aaryofDetails;
    }

    public function insertRecords($data, $tablename)
    {
        $insert = DB::table($tablename)->insertGetId($data);
        return $insert;
    }

    public function getRecordCount($conditions, $tablename)
    {
        $counts = DB::table($tablename)->where($conditions)->get()->count();
        return $counts;
    }

    public function updateRecords($conditions, $tablename,$data)
    {
        $message="";
        $query = DB::table($tablename)->where($conditions)->update($data);       
        if ($query != '') {
            $message = 'Done';
        } else {
            $message = 'Error';
        }
        return $message;
    }  

    public function getAllRecords($conditions, $tablename)
    {
        $getRecord = DB::table($tablename)->where($conditions)->get();
        return $getRecord;
    }  

    public function getFirstRecord($conditions, $tablename)
    {
        $getRecord = DB::table($tablename)->where($conditions)->get()->first();
        return $getRecord;
    }  

    public function getRecordsByColumn($data,$conditions, $tablename)
    {
        $getRecord = DB::table($tablename)->select($data)->where($conditions)->get();
        return $getRecord;
    }    


}

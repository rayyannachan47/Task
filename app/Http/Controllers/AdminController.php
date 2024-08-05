<?php

namespace App\Http\Controllers;

use App\Models\mainModel;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public $currentDate;
    public $currentTime;
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->currentDate = date('Y-m-d');
        $this->currentTime = date('H:i:s');
    }

    protected function getValidationRules($isEdit = false, $userId = null)
    {
        if ($isEdit) {
            return [
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/'
                ],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('tbl_users', 'email')->ignore($userId, 'user_id'),
                ],
                'password' => [
                    'required',
                    'string',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/'
                ],
                'contact' => [
                    'required',
                    'string',
                    'max:10',
                    'regex:/^\d{10}$/'
                ],
                'dob' => [
                    'required',
                    'date',
                    'before_or_equal:today'
                ],
                'address' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z0-9\s,.-]+$/'
                ],
                'photo' => [
                    'nullable',
                    'file',
                    'mimes:jpeg,png,jpg',
                    'max:2048'
                ],
            ];
        } else {
            return [
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/'
                ],
                'email' => [
                    'required',
                    'email',
                    'unique:tbl_users,email',
                ],
                'password' => [
                    'required',
                    'string',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/'
                ],
                'contact' => [
                    'required',
                    'string',
                    'max:10',
                    'regex:/^\d{10}$/'
                ],
                'dob' => [
                    'required',
                    'date',
                    'before_or_equal:today'
                ],
                'address' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z0-9\s,.-]+$/'
                ],
                'photo' => [
                    'required',
                    'file',
                    'mimes:jpeg,png,jpg',
                    'max:2048'
                ],
            ];
        }
    }

    public function CheckLogin(Request $request, mainModel $model)
    {
        $username = htmlentities(trim($request['email']));
        $e_password = htmlentities(trim($request['password']));

        $ciphertextLength = 24;
        $saltLength = 512;
        $ivLength = 32;

        $salt = substr($e_password, 0, $saltLength);
        $ciphertext = substr($e_password, $saltLength, $ciphertextLength);
        $iv = substr($e_password, $saltLength + $ciphertextLength, $ivLength);

        $password = array(
            "ciphertext" => $ciphertext,
            "salt" => $salt,
            "iv" => $iv
        );

        $decrypt_password = $this->cryptoJsAesDecryptApi(json_encode(($password)));
        $data['username'] = $username;
        $data['passwords'] = $decrypt_password;
        $details = $model->Authentication($data);

        $count = count($details);
        $message = '';
        if ($count === 0) {
            $message = 'NotFound';
            return $message;
        } else {
            $user_id = $details['user_id'];
            $role_Id = $details['role_id'];
            $email = $details['email'];
            $username = $details['name'];
            if ($role_Id == 1) {
                $request->session()->put('email', $email);
                $request->session()->put('roleId', $role_Id);
                $request->session()->put('AdminEmail', $email);
                $request->session()->put('username', $username);
                $retVal = $role_Id . '_,';
            } elseif ($role_Id == 2) {
                $conditions = ['user_id' => $user_id];
                $checklogin = $model->getRecordCount($conditions, 'mst_login_aduit_reports');
                if ($checklogin == 0) {
                    $encryptUserId = Crypt::encrypt($user_id);
                    $message = 'First';
                } else {
                    $encryptUserId = Crypt::encrypt($user_id);
                    $request->session()->put('email', $email);
                    $request->session()->put('username', $username);
                    $request->session()->put('roleId', $role_Id);
                    $request->session()->put('userid', $user_id);
                    $request->session()->put('UserEmail', $email);
                    $timestamp = date("Y-m-d H:i:s");
                    $newdata['user_id'] = $user_id;
                    $newdata['login_date'] = $this->currentDate;
                    $newdata['login_time'] = $this->currentTime;
                    $newdata['created_at'] = $timestamp;
                    $newdata['status'] = 'Login Sucessfully';
                    $newdata['flag'] = 'Show';
                    $datasubmited = $model->insertRecords($newdata, 'mst_login_aduit_reports');
                    $message = 'Get';
                }
                $retVal = $role_Id . '_,' . $message . '_,' . $encryptUserId;
            }
        }
        return $retVal;
    }

    public function login()
    {
        if (session()->has('AdminEmail')) {
            return redirect('/Admin/Dashboard');
        } else if (session()->has('UserEmail')) {
            return redirect('/User/Dashboard');
        }
        return view("index");
    }

    public static function cryptoJsAesDecryptApi($value)
    {
        $screetkey = 'base64:Sy20F3d1yj4jxhukOeFhA2RAZQnjkvY+C9gDdg9pUyI=';
        $jsondata = json_decode($value, true);
        try {
            $salt = hex2bin($jsondata["salt"]);
            $iv  = hex2bin($jsondata["iv"]);
        } catch (\Exception $e) {
            return null;
        }
        $ciphertext = base64_decode($jsondata["ciphertext"]);
        $iterations = 999;
        $key = hash_pbkdf2("sha512", $screetkey, $salt, $iterations, 64);
        $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);
        return json_decode($decrypted, true);
    }

    public function dashboard()
    {
        return view('Admin.dashboard');
    }

    public function userData(mainModel $model)
    {
        $fetchconditions = ['flag' => 'Show', 'role_id' => '2'];
        $getDetails = $model->getAllRecords($fetchconditions, 'tbl_users');

        return DataTables::of($getDetails)
            ->addIndexColumn()
            ->addColumn('images', function ($query) {
                $imageName = $query->image;
                $email = $query->email;
                $imageUrl = asset('public/images/' . $email . '/' . $imageName);

                // return "<img src='{$imageUrl}' alt='Image' style='width: 50px; height: 50px;' />";
                return "<a href='#' data-toggle='modal' data-target='#imageModal' data-image-url='{$imageUrl}'><img src='{$imageUrl}' alt='Image' style='width: 50px; height: 50px;' /></a>";
            })
            ->addColumn('action', function ($query) {
                $id = Crypt::encrypt($query->user_id);
                return '<div class="d_flex"><a href="' . route('Admin/edit_user', ['userId' => Crypt::encrypt($query->user_id)]) . '" id="userform"><i class="fa fa-pencil editicon fa-lg" aria-hidden="true"></i></a><a href="#" onclick="deleteUser(\'' . $id . '\', event)"><i class="fa fa-trash deleteicon m__left_20 fa-lg" aria-hidden="true"></i></a></div>';
            })
            ->rawColumns(['action', 'images'])
            ->make(true);
    }

    public function addUser()
    {
        return view('Admin.create_user');
    }

    public function add_user_code(Request $request, mainModel $model)
    {
        $dynamicRules = [];

        foreach ($this->getValidationRules(false) as $field => $rules) {
            if ($request->has($field)) {
                $dynamicRules[$field] = $rules;
            }
        }

        $validator = Validator::make($request->all(), $dynamicRules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $validatedData = $validator->validated();

        $email = $validatedData['email'];

        $fileName = "";

        $publicPath = 'images/' . $email;

        if (!Storage::disk('public')->exists(public_path('images/' . $email))) {
            Storage::makeDirectory(public_path('images/' . $email));
        }

        $files = $request->allFiles();

        foreach ($files as $file) {
            if ($file->isValid()) {
                $fileName = $file->getClientOriginalName();
                $file->move(public_path($publicPath), $fileName);
            }
        }

        $dob = (new \DateTime($validatedData['dob']))->format('d-m-Y');

        $data = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Crypt::encrypt($validatedData['password']),
            'contact' => $validatedData['contact'],
            'address' => $validatedData['address'],
            'image' => $fileName,
            'dob' => $dob,
            'role_id' => 2,
            'created_date' => $this->currentDate,
            'created_time' => $this->currentTime,
        ];

        $newUserId = $model->insertRecords($data, 'tbl_users');

        if ($newUserId > 0) {
            return response()->json(['message' => 'Done']);
        } else {
            return response()->json(['message' => 'Failed to create user']);
        }
    }

    public function delete_user($id, mainModel $model)
    {
        try {
            $id = Crypt::decrypt($id);
            $selectData = ['email', 'image'];
            $fetchconditions = ['flag' => 'Show', 'user_id' => $id];
            $getDetails = $model->getRecordsByColumn($selectData, $fetchconditions, 'tbl_users');
            $counts = $getDetails->count();

            if ($counts == 0) {
                return response()->json(['message' => 'User not found']);
            }
            $email = "";
            $image = "";

            if (isset($getDetails[0])) {
                $email = $getDetails[0]->email;
                $image = $getDetails[0]->image;
            }

            $filepath = public_path('images/' . $email . '/' . $image);

            if (file_exists($filepath)) {
                unlink($filepath);
            }

            $folderpath = public_path('images/' . $email);

            if (file_exists($folderpath)) {
                rmdir($folderpath);
            }
            $data['flag'] = "Delete";
            $data['updated_date'] = $this->currentDate;
            $data['updated_time'] = $this->currentTime;
            $conditions = ['flag' => 'Show', 'user_id' => $id];
            $response = $model->updateRecords($conditions, 'tbl_users', $data);
            if ($response == "Done") {
                return response()->json(['message' => 'Done']);
            } else {
                return response()->json(['message' => 'An unexpected error occurred']);
            }
        } catch (DecryptException $e) {
            return response()->json(['message' => 'Invalid data provided']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found']);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred']);
        }
    }

    public function edit_user($userIds, mainModel $model)
    {
        try {
            $userId = Crypt::decrypt($userIds);
            $fetchconditions = ['flag' => 'Show', 'role_id' => '2', 'user_id' => $userId];
            $edituserdetails = $model->getFirstRecord($fetchconditions, 'tbl_users');
            if (empty($edituserdetails)) {
                Session::flash('error', 'User not found');
                return redirect()->back();
            }
            $password = Crypt::decrypt($edituserdetails->password);
            $user_id = Crypt::encrypt($edituserdetails->user_id);
            $imageName = $edituserdetails->image;
            $email = $edituserdetails->email;
            $imageUrl = asset('public/images/' . $email . '/' . $imageName);
            return view('Admin.edit_user', compact('edituserdetails', 'password', 'user_id', 'imageUrl'));
        } catch (DecryptException $e) {
            Session::flash('error', 'Invalid data provided');
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            Session::flash('error', 'User not found');
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', 'An unexpected error occurred');
            return redirect()->back();
        }
    }

    public function edit_user_code(Request $request, mainModel $model)
    {
        try {
            $user_id = Crypt::decrypt($request->id);
            $selectData = ['image', 'email'];
            $fetchconditions = ['flag' => 'Show', 'user_id' => $user_id];
            $getDetails = $model->getRecordsByColumn($selectData, $fetchconditions, 'tbl_users');
            $counts = $getDetails->count();

            $fileName = "";
            $oldEmail = "";

            if ($counts == 0) {
                return response()->json(['message' => 'User not found']);
            }

            if (isset($getDetails[0])) {
                $fileName = $getDetails[0]->image;
                $oldEmail = $getDetails[0]->email;
            }

            $fileStatus = true;

            $fileFields = ['photo'];

            $dynamicRules = [];
            foreach ($this->getValidationRules(true, $user_id) as $field => $rules) {
                if (in_array($field, $fileFields)) {
                    if ($request->hasFile($field)) {
                        $dynamicRules[$field] = $rules;
                    } else {
                        $fileStatus = false;
                    }
                } else {
                    if ($request->has($field)) {
                        $dynamicRules[$field] = $rules;
                    }
                }
            }

            $validator = Validator::make($request->all(), $dynamicRules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }

            $validatedData = $validator->validated();

            if ($fileStatus) {
                $email = $validatedData['email'];
                $publicPath = 'images/' . $email;

                $filepath = public_path('images/' . $email . '/' . $fileName);

                if (file_exists($filepath)) {
                    unlink($filepath);
                }

                if (!Storage::disk('public')->exists(public_path('images/' . $email))) {
                    Storage::makeDirectory(public_path('images/' . $email));
                }
                $files = $request->allFiles();

                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $fileName = $file->getClientOriginalName();
                        $file->move(public_path($publicPath), $fileName);
                    }
                }
            }

            $dob = (new \DateTime($validatedData['dob']))->format('d-m-Y');

            $data = [
                'name' => $validatedData['name'],
                'email' => $oldEmail,
                'password' => Crypt::encrypt($validatedData['password']),
                'contact' => $validatedData['contact'],
                'address' => $validatedData['address'],
                'image' => $fileName,
                'dob' => $dob,
                'role_id' => 2,
                'updated_date' => $this->currentDate,
                'updated_time' => $this->currentTime,
            ];

            $response = $model->updateRecords($fetchconditions, 'tbl_users', $data);
            return response()->json(['message' => $response]);
        } catch (DecryptException $e) {
            return response()->json(['message' => 'Invalid data provided']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred']);
        }
    }

    public function Logout(Request $request, mainModel $model)
    {
        $timestamp = date("Y-m-d H:i:s");        
        $roleId = Session::get('roleId');
        if($roleId == "2"){
            $user_id = Session::get('userid');        
            $newdata['user_id'] = $user_id;
            $newdata['logout_date'] = $this->currentDate;
            $newdata['logout_time'] = $this->currentTime;
            $newdata['created_at'] = $timestamp;
            $newdata['status'] = 'Logout Sucessfully';
            $newdata['flag'] = 'Show';
            $datasubmited = $model->insertRecords($newdata, 'mst_login_aduit_reports');
        }       
        $request->session()->flush();
        return redirect('/');
    }

    public function changePassword($userIds)
    {
        return view('User.change_password', compact('userIds'));
    }

    public function checkpassword(Request $request, mainModel $model)
    {
        try {

            $id = Crypt::decrypt($request->id);
            $selectData = ['user_id'];
            $fetchconditions = ['flag' => 'Show', 'user_id' => $id];
            $getDetails = $model->getRecordsByColumn($selectData, $fetchconditions, 'tbl_users');
            $counts = $getDetails->count();

            if ($counts == 0) {
                return response()->json(['message' => 'User not found']);
            }

            $validator = Validator::make($request->all(), [
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
                ],
                'confirmPassword' => 'required|string|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }

            $validatedData = $validator->validated();

            $updatedata = [
                'password' => Crypt::encrypt($validatedData['password']),
                'updated_date' => $this->currentDate,
                'updated_time' => $this->currentTime,
            ];

            $response = $model->updateRecords($fetchconditions, 'tbl_users', $updatedata);
            if ($response == "Done") {
                $timestamp = date("Y-m-d H:i:s");
                $data['user_id'] = $id;
                $data['login_date'] = $this->currentDate;
                $data['login_time'] = $this->currentTime;
                $data['created_at'] = $timestamp;
                $data['status'] = 'Login Sucessfully';
                $data['flag'] = 'Show';
                $datasubmited = $model->insertRecords($data, 'mst_login_aduit_reports');
                if ($datasubmited > 0) {
                    return response()->json(['message' => 'Done']);
                } else {
                    return response()->json(['message' => 'Something went wrong']);
                }
            } else {
                return response()->json(['message' => 'Something went wrong']);
            }
        } catch (DecryptException $e) {
            return response()->json(['message' => 'Invalid data provided']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found']);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred ']);
        }
    }

    public function userDashboard(mainModel $model)
    {
        try {
            $user_id = Session::get('userid');
            $selectData = ['name', 'email', 'password', 'dob', 'contact', 'address', 'image'];
            $fetchconditions = ['flag' => 'Show', 'user_id' => $user_id, 'role_id' => '2'];
            $getDetails = $model->getRecordsByColumn($selectData, $fetchconditions, 'tbl_users');
            $counts = $getDetails->count();

            if ($counts == 0) {
                Session::flash('error', 'User not found');
                return redirect()->back();
            }

            foreach ($getDetails as $details) {
                $details->password = Crypt::decrypt($details->password);
            }

            return view('User.dashboard', compact('getDetails'));
        } catch (DecryptException $e) {
            Session::flash('error', 'Invalid data provided');
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            Session::flash('error', 'User not found');
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', 'An unexpected error occurred');
            return redirect()->back();
        }
    }

    public function checkSession(Request $request)
    {
        $isLoggedIn = $request->session()->has('AdminEmail');
        return response()->json(['isLoggedIn' => $isLoggedIn]);
    }
}

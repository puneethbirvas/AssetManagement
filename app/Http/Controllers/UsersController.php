<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Exception;
use Illuminate\Database\QueryException;


class UsersController extends Controller
{ 
    public function store(Request $request)
    {
        try{
            $users = new users;
            $users->employee_id= $request->employee_id;
            $users->employee_name= $request->employee_name;
            $users->department= $request->department;
            $users->designation= $request->designation;
            $users->mobile_number= $request->mobile_number;
            $users->email= $request->email;
            $users->user_name= $request->user_name;
            $users->password= $request->password;

            $users->save();
            $response = [
                "message" => "User Added Sucessfully!",
                "status" => 200
            ];
            $status = 200;  

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 406;            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406;             
        }

        return response($response,$status);
    }


    public function update(Request $request,$id)
    {
        try{
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }

            $users->employee_id = $request->employee_id;
            $users->employee_name = $request->employee_name;
            $users->department= $request->department;
            $users->designation= $request->designation;
            $users->mobile_number= $request->mobile_number;
            $users->email= $request->email;
            $users->user_name= $request->user_name;
            $users->password= $request->password;

            $users->save();
            $response = [       
               "message" => $users->user_name. ' user Updated Successfully', 
               "status" => 200
            ];
            $status = 200;  

            }catch(Exception $e){
               $response = [
                   "message"=>$e->getMessage(),
                   "status" => 406
               ];            
               $status = 200;
            }catch(QueryException $e){
               $response = [
                   "error" => $e->errorInfo,
                   "status" => 406
               ];
               $status = 406; 
            }

            return response($response,$status);
    } 


    public function destroy($id)
    { 
        try{
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }else{
                $users->delete();
                $response = [          
                    "message" => $users->user_name. " user Deleted Sucessfully!",
                    "status" => 200
                ];
                $status = 200;     
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }

        return response($response,$status);
    } 

 
    public function loginUser(Request $request)
    {   
      $users = Users::where(['email' => $request->email])->first();
       
        if(!$users){    
            $response = [
                'error' => 'Entered email has not been registered. Please enter the registered email id',
                "status" => 401
            ];
            $status=401;
        }else{
            if($users['blocked']){
                $response = [
                    'message'=>"User is blocked, please contact admin",
                    ];
                $status = 403; 
                       
            }
            elseif(!$users = Users::where(['password' => $request->password])->first()){         
                    $response = [
                        'error' => 'Entered password is invalid',
                        "status" => 401
                    ];
                    $status=401;
            }else{
                $users = Users::where('email', $request['email'])->firstOrFail();
                $users = Users::where('password', $request['password'])->firstOrFail();
                $token = $users->createToken('auth_token')->plainTextToken;
                $response = [
                    'userDetails' =>[ 
                        'username' => $users->user_name,
                        'email'=>$users->email,
                    ],
                    'access_token' => $token, 
                    ];
                $status = 200;        
            }
        }

        return response($response, $status);
    }

    public function logout(Request $request) 
    {
        try{
            auth()->user()->tokens()->delete();
            $response = [          
                "message" =>  " user Logout Sucessfully!",
                "status" => 200
            ];
            $status = 200;     
            

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }

        return response($response,$status);
    }

    public function block($id)
    {
       try{
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }else{
                $users->update(['blocked' => true]);
                $response = [
                    'message'=>"User is blocked Sucessfully!",
                    "status" => 200
                    ];
                $status = 200;        
            }            
        }catch(Exception $e){
                $response = [
                   "message"=>$e->getMessage(),
                   "status" => 406
                ];            
            $status = 406;
        }catch(QueryException $e){
                $response = [
                    "error" => $e->errorInfo,
                    "status" => 406
                ];
            $status = 406; 
        }

        return response($response,$status);
    }  
    
    public function showData()
    {
      try{    
            $users = Users::all();
            if(!$users){
             throw new Exception("user not found");
            }
            $response=[
             "message" => "Users List",
             "data" => $users
            ];
            $status = 200; 
            
        }catch(Exception $e){
               $response = [
                 "message"=>$e->getMessage(),
                 "status" => 406
                ];                    
            $status = 406;
        }catch(QueryException $e){
                $response = [
                  "error" => $e->errorInfo,
                  "status" => 406
                ];  
            $status = 406; 
        }
        return response($response,$status); 
    }
}
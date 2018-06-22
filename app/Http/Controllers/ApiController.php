<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use File;
use DB;
use Carbon\Carbon;
// use GuzzleHttp\Client;
use App\Models\Client;
// include_once "smsc_api.php";
use App\Models\Goal;
use App\Models\Meal;
use App\Models\Task;

class ApiController extends Controller
{
    public function Auth(Request $request){
        $rules = [
            'phone' => 'required',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('phone', $request['phone'])->first();
            $code = rand(1000,9999);
            if ($user == null) {
            	$client = new Client();
            	$client->phone = $request['phone'];
            	$client->token = md5($request['phone'].'tarelka');
                $client->code = $code;
            	$client->save();
            	//send_sms($request['phone'], "Tarelka. Ваш пароль:".$code);

                $result['statusCode']= 200;
                $result['message']= 'Success!';
                $result['result']= $client;
            }
            else{
            	//send_sms($request['phone'], "Tarelka. Ваш пароль:".$code);
                $result['statusCode'] = 200;
                $result['message'] = 'User has been registered';
                $result['result'] = $user;
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function CheckCode(Request $request){
        $rules = [
            'code' => 'required',
            'phone' => 'required',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }
        else {
            $user = Client::where('phone',$request['phone'])
                ->where('code',$request['code'])
                ->orderBy('id','DESC')
                ->first();
            if ($user!=null){               
                $result['statusCode'] = 200;
                $result['message'] = "success";
                $result['result'] = $user;
            }
            else{
                $result['statusCode'] = 404;
                $result['message'] = "not found";
                $result['result'] = [];
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function Tasks(){
        $tasks = Task::all();

        if  (count($tasks) != 0){
            $result['statusCode'] = 200;
            $result['message'] = 'success';

            foreach ($tasks as $task) {
                $temp['id'] = $task->id;
                $temp['title'] = $task->title;
                $temp['updated_at'] = $task->updated_at;
                $temp['created_at'] = $task->created_at;

                $result['result'][] = $temp;
            }
        }
        else{
            $result['statusCode'] = 404;
            $result['message'] = 'Tasks not found';
            $result['result'] = [];
        }
        return response()->json($result, $result['statusCode']);
    }
    public function Goals(){
        $goals = Goal::all();

        if  (count($goals) != 0){
            $result['statusCode'] = 200;
            $result['message'] = 'success';

            foreach ($goals as $goal) {
                $temp['id'] = $goal->id;
                $temp['title'] = $goal->title;
                $temp['image'] = asset($goal->image);
                $temp['updated_at'] = $goal->updated_at;
                $temp['created_at'] = $goal->created_at;

                $result['result'][] = $temp;
            }
        }
        else{
            $result['statusCode'] = 404;
            $result['message'] = 'Goals not found';
            $result['result'] = [];
        }
        return response()->json($result, $result['statusCode']);
    }
    public function Meals(){
        $meals = Meal::all();

        if  (count($meals) != 0){
            $result['statusCode'] = 200;
            $result['message'] = 'success';

            foreach ($meals as $meal) {
                $temp['id'] = $meal->id;
                $temp['title'] = $meal->title;
                $temp['image'] = asset($meal->image);
                $temp['updated_at'] = $meal->updated_at;
                $temp['created_at'] = $meal->created_at;

                $result['result'][] = $temp;
            }
        }
        else{
            $result['statusCode'] = 404;
            $result['message'] = 'Meals not found';
            $result['result'] = [];
        }
        return response()->json($result, $result['statusCode']);
    }
    public function Profile(Request $request){
    	$rules = [
            'token' => 'required|exists:clients,token',
            'image' => 'file|mimes:jpeg,png,jpg|max:2048',
            'first_name' => 'required',
            'last_name' => 'required',
            'weight' => 'required|integer',
            'age' => 'required|integer',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            if ($user != null) {
            	if (isset($request['image'])) {
            		$this->deletefile($user->avatar);
            		$user->avatar = $this->uploadfile($request['image']);
            	}
        		$user->first_name = $request['first_name'];
        		$user->last_name = $request['last_name'];
        		$user->weight = $request['weight'];
        		$user->age = $request['age'];
            	$user->save();

                $result['statusCode']= 200;
                $result['message']= 'Success!';
                $result['result']= $this->GetUser($user->id);
            }
            else{
                $result['statusCode'] = 404;
                $result['message'] = 'User not found';
                $result['result'] = $this->GetUser($user->id);
            }
        }
        return response()->json($result, $result['statusCode']);
    }

    public function GetUser($id){
    	$user = Client::find($id);
    	if ($user!=null) {
    		$item['id'] = $user->id;
    		$item['token'] = $user->token;
            $item['phone'] = $user->phone;
    		// $item['code'] = $user->code;
    		$item['status'] = $user->status;
    		$item['avatar'] = asset($user->avatar);
    		$item['first_name'] = $user->first_name;
    		$item['last_name'] = $user->last_name;
    		$item['weight'] = $user->weight.' кг';
    		$item['age'] = $user->age.' лет';
    		$item['created_at'] = Carbon::parse($user->created_at)->format('d M, Y');
    		$item['updated_at'] = Carbon::parse($user->updated_at)->format('d M, Y');
    		return $item;
    	}else{
    		return null;
    	}
    }

    public function uploadfile($file,$dir = 'uploads'){
        $file_type = File::extension($file->getClientOriginalName());
        $file_name = time().str_random(5).'.'.$file_type;
        $file->move($dir, $file_name);
        return $dir.'/'.$file_name;
    }
    public function deletefile($path){
        if (File::exists($path)) {
            File::delete($path);
            return true;
        }
        else{
            return false;
        }
    }
    protected function validator($errors,$rules) {
        return Validator::make($errors,$rules);
    }
}
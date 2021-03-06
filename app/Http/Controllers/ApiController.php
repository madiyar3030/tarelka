<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use File;
use DB;
use Carbon\Carbon;
use GuzzleHttp\Client as Clientt;
use App\Models\Client;
use App\Models\Goal;
use App\Models\Meal;
use App\Models\Task;
use App\Models\Goalclient;
use App\Models\Mealclient;
use App\Models\Chat;
use DateTime;
use App\Models\Progress;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Quizclient;
use App\Models\Admin;
use App\Models\Schedule;

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
            if ($user == null) {
            	$client = new Client();
            	$client->phone = $request['phone'];
            	$client->token = md5($request['phone'].'tarelka');
                $client->last_action = Carbon::now()->subDays(7)->format('Y-m-d');
            	$client->save();
            	//send_sms($request['phone'], "Tarelka. Ваш пароль:".$code);

                $result['statusCode']= 200;
                $result['message']= 'Success!';
                $result['result']= $this->GetUser($client->id);
            }
            else{
            	//send_sms($request['phone'], "Tarelka. Ваш пароль:".$code);
                $result['statusCode'] = 201;
                $result['message'] = 'User has been registered';
                $result['result'] = $this->GetUser($user->id);
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function Info(){
        $info = Admin::find(1);
        if ($info == null) {
            $result['statusCode'] = 404;
            $result['message'] = 'Not found';
            $result['result'] = [];
        }else{
            $result['statusCode'] = 200;
            $result['message'] = 'Success!';
            $result['result']['whatsapp'] = $info->whatsapp;
            $result['result']['telegram'] = $info->telegram;
            $result['result']['facebook'] = $info->facebook;
            $result['result']['viber'] = $info->viber;
            $result['result']['vk'] = $info->vk;
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
    public function SendGoal(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'goal_id' => 'required|exists:goals,id',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            $goal = Goal::find($request['goal_id']);
            $goalclient = Goalclient::where('goal_id',$request['goal_id'])->where('client_id', $user->id)->get();
            if (($user != null)&&($goal != null)) {
                if (count($goalclient)==0) {
                    $goals = Goalclient::where('client_id', $user->id)->get();
                    foreach ($goals as $key) {
                        $key->delete();
                    }
                    $goal_c = new Goalclient();
                    $goal_c->client_id = $user->id;
                    $goal_c->goal_id = $goal->id;
                    $goal_c->save();

                    $result['statusCode'] = 200;
                    $result['message'] = 'Success!';
                    $result['result'] = $this->GetUser($user->id);
                }else{
                    $result['statusCode'] = 201;
                    $result['message'] = 'This goal is choosen';
                    $result['result'] = $this->GetUser($user->id);
                }
            }
            else{
                $result['statusCode'] = 404;
                $result['message'] = 'User or goal not found';
                $result['result'] = null;
            }
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
    public function SendMeal(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'meals' => 'required|array|exists:meals,id',
            'meals.*.id' => 'distinct',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            if (($user != null)) {
                foreach ($request['meals'] as $meal) {
                    $mealclient = Mealclient::where('client_id', $user->id)->where('meal_id',$meal)->first();
                    if (($mealclient==null)) {
                        $meal_c = new Mealclient();
                        $meal_c->client_id = $user->id;
                        $meal_c->meal_id = $meal;
                        $meal_c->save();

                        $result['statusCode']= 200;
                        $result['message']= 'Success!';
                        $result['result']= $this->GetUser($user->id);
                    }else{
                        $result['statusCode']= 201;
                        $result['message']= 'This meal is chosen';
                        $result['result']= $this->GetUser($user->id);
                    }

                }
            }
            else{
                $result['statusCode'] = 404;
                $result['message'] = 'User or meal not found';
                $result['result'] = null;
            }
        }
        return response()->json($result, $result['statusCode']);
    }

    public function Tasks(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            $schedules = Schedule::all();
            if (count($schedules)!=0) {
                $result['statusCode'] = 200;
                $result['message'] = 'success';
                foreach ($schedules as $schedule) {
                    $result['result']['tasks'][] = $this->GetTask($schedule->task_id, $schedule->step, $user);
                }
                
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'Quizzes not found';
                $result['result'] = null;                
            }
        }
        return response()->json($result, $result['statusCode']);
    }

    public function Profile(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'image' => 'file|mimes:jpeg,png,jpg|max:2048',
            'weight' => 'integer',
            'age' => 'integer',
            'height' => 'integer',
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
                if (isset($request['fio'])) {
                    $user->fio = $request['fio'];
                }
                if (isset($request['weight'])) {
                    $user->weight = $request['weight'];
                }
                if (isset($request['age'])) {
                    $user->age = $request['age'];
                }
                if (isset($request['height'])) {
                    $user->height = $request['height'];
                }
                $user->save();

                $result['statusCode']= 200;
                $result['message']= 'Success!';
                $result['result']= $this->GetUser($user->id);
            }
            else{
                $result['statusCode'] = 404;
                $result['message'] = 'User not found';
                $result['result'] = null;
            }
        }
        return response()->json($result, $result['statusCode']);
    }

    public function Chat(Request $request){
        $rules = [
            'page' => 'required',
            'token' => 'required|exists:clients,token',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $count = DB::table('chats')
                ->where('to_u', $request['token'])
                ->orWhere('from_u', $request['token'])
                ->where('deleted', 0)
                ->count();
            $limit = 15;
            $offset = $limit * $request['page'];
            $pages = (int)ceil($count/$limit) - 1;
            $chats = Chat::where('to_u', $request['token'])
                ->orWhere('from_u', $request['token'])
                ->where('deleted', 0)
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->offset($offset)
                ->get();
            if (count($chats) != 0) { 
                $result['result']['count_pages'] = $pages;
                $result['result']['count_data'] = $count;
                $result['result']['offset'] = $offset;
                $result['result']['limit'] = $limit;
                $result['result']['current_page'] = (int)$request['page'];
                $next_page = null;
                $prev_page = null;
                if ($request->page < $pages){
                    $next_page = url("/api/chat?token=$request->token&page=".($request->page + 1));
                }
                if ($request->page > 0){
                    $prev_page = url("/api/chat?token=$request->token&page=".($request->page - 1));
                }
                $result['result']['next_page'] = $next_page;
                $result['result']['prev_page'] = $prev_page;               
                foreach ($chats as $chat) {
                    $result['result']['chats'][] = $this->GetChat($chat->id);
                }
                $result['statusCode']= 200;
                $result['message']= 'Success!';
            }
            else{
                $result['statusCode'] = 404;
                $result['message'] = 'Chats not found';
                $result['result'] = null;
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function SendMessage(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'message' => 'string',
            'images' => 'array',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            if (count($request['images'])<6) {
                $chat = new Chat();
                $chat->from_u = $request['token'];
                $chat->to_u = 'admin';
                $chat->message = $request['message'];    
                if (isset($request['images'][0])) {
                    $chat->image_1 = $this->uploadfile($request['images'][0]);
                }    
                if (isset($request['images'][1])) {
                    $chat->image_2 = $this->uploadfile($request['images'][1]);
                }
                if (isset($request['images'][2])) {
                    $chat->image_3 = $this->uploadfile($request['images'][2]);
                }
                if (isset($request['images'][3])) {
                    $chat->image_4 = $this->uploadfile($request['images'][3]);
                }
                if (isset($request['images'][4])) {
                    $chat->image_5 = $this->uploadfile($request['images'][4]);
                }         
                $chat->save();
                $result['result'] = $this->GetChat($chat->id);
                $result['statusCode']= 200;
                $result['message']= 'Message sent!';
            }else{
                $result['result'] = [];
                $result['statusCode']= 400;
                $result['message']= 'max image 5';
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function HideMessage(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'message_id' => 'required|exists:chats,id',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $chat = Chat::find($request['message_id']);
            if ($chat!=null) {
                $chat->deleted = 1;
                $chat->save();
                $result['result'] = [];
                $result['statusCode']= 200;
                $result['message']= 'Message is hidden!';
            }else{
                $result['result'] = [];
                $result['statusCode']= 404;
                $result['message']= 'Message not found';
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function DeleteMessage(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'message_id' => 'required|exists:chats,id',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $chat = Chat::find($request['message_id']);
            if ($chat!=null) {
                $this->deletefile($chat->image_1);
                $this->deletefile($chat->image_1);
                $this->deletefile($chat->image_3);
                $this->deletefile($chat->image_4);
                $this->deletefile($chat->image_5);
                $chat->delete();
                $result['result'] = [];
                $result['statusCode']= 200;
                $result['message']= 'Message deleted!';
            }else{
                $result['result'] = [];
                $result['statusCode']= 404;
                $result['message']= 'Message not found';
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function GetProgress(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            $progresses = Progress::where('client_id', $user->id)->get();
            if (count($progresses)!=0) {
                $perc = [];
                $points = DB::select('SELECT SUM(correct_answers) as correct, SUM(max_point) AS max, quiz_date as date FROM progresses WHERE client_id = '.$user->id.' GROUP BY quiz_date ORDER BY quiz_date ASC');
                if (count($points)!=0) {
                    for ($i=0; $i < count($points); $i++) { 
                        $perc[$i]['correct'] = $points[$i]->{'correct'};
                        $perc[$i]['max'] = $points[$i]->{'max'};
                        $perc[$i]['percentage'] = (intval($points[$i]->{'correct'})/(intval($points[$i]->{'max'})))*100;
                        $perc[$i]['date'] = $points[$i]->{'date'};
                    }  
                    $result['statusCode'] = 200;   
                    $result['message'] = 'Success!';
                    $result['result']['perc'] = $perc; 
                }else{
                    $result['statusCode'] = 404;   
                    $result['message'] = 'Not found!';
                    $result['result']['perc'] = null;                     
                }
                // $maxpoints = intval(DB::select('SELECT SUM(max_point) AS max FROM progresses WHERE client_id = '.$user->id.' AND quiz_date = "'.$request['date'].'"')[0]->{'max'});
                // $corrects = intval(DB::select('SELECT SUM(correct_answers) AS correct FROM progresses WHERE client_id = '.$user->id.' AND quiz_date = "'.$request['date'].'"')[0]->{'correct'});
                // if (($maxpoints == 0)&&($corrects == 0)) {
                //     $result['result']['perc'] = 0;   
                //     $result['statusCode'] = 200;    
                // }else{
                //     $perc = intval(($corrects/$maxpoints)*100);   
                //     $result['result']['perc'] = $perc;   
                //     $result['statusCode'] = 200;   
                // }       
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'Progress of '.$user->fio.' not found';
                $result['result'] = 'null';                
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function GetProgressz(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'date' => 'required|date|date_format:Y-m-d',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            $progresses = Progress::where('client_id', $user->id)->get();
            if (count($progresses)!=0) {
                $maxpoints = intval(DB::select('SELECT SUM(max_point) AS max FROM progresses WHERE client_id = '.$user->id.' AND quiz_date = "'.$request['date'].'"')[0]->{'max'});
                $corrects = intval(DB::select('SELECT SUM(correct_answers) AS correct FROM progresses WHERE client_id = '.$user->id.' AND quiz_date = "'.$request['date'].'"')[0]->{'correct'});
                if (($maxpoints == 0)&&($corrects == 0)) {
                    $result['result']['perc'] = 0;   
                    $result['statusCode'] = 200;    
                }else{
                    $perc = intval(($corrects/$maxpoints)*100);   
                    $result['result']['perc'] = $perc;   
                    $result['statusCode'] = 200;   
                }       
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'Progress of '.$user->first_name.' '.$user->last_name.' not found';
                $result['result'] = null;                
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function ListQuiz(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            $schedules = Schedule::all();
            if (count($schedules)!=0) {
                $result['statusCode'] = 200;
                $result['message'] = 'success';
                foreach ($schedules as $schedule) {
                    $result['result']['quizzes'][] = $this->GetQuiz($schedule->quiz_id, $schedule->step, $user);
                }                
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'Quizzes not found';
                $result['result'] = null;                
            }
        }
        return response()->json($result, $result['statusCode']);
    }

    public function ListQuestion(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'quiz_id' => 'required|exists:quizzes,id',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            $questions = Question::where('quiz_id', $request['quiz_id'])->get();
            if (count($questions)!=0) {
                $result['statusCode'] = 200;
                $result['message'] = 'success';
                foreach ($questions as $question) {
                    $temp['id'] = $question->id; 
                    $temp['question'] = $question->question;
                    $temp['answer_a'] = $question->answer_a;
                    $temp['answer_b'] = $question->answer_b;
                    $temp['answer_c'] = $question->answer_c;
                    $temp['answer_d'] = $question->answer_d;
                    $temp['answer_e'] = $question->answer_e;
                    $temp['right_answer'] = $question->right_answer;

                    $result['result'][] = $temp;
                }        
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'Questions not found';
                $result['result'] = null;                
            }
        }
        return response()->json($result, $result['statusCode']);
    }
    public function SendResult(Request $request){
        $rules = [
            'token' => 'required|exists:clients,token',
            'quiz_id' => 'required|exists:quizzes,id',
            'max_answer' => 'required',
            'correct_answer' => 'required',
            'quiz_date' => 'required', 
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }else {
            $user = Client::where('token', $request['token'])->first();
            if ($user!=null) {
                $progress = new Progress();
                $progress->quiz_id = $request['quiz_id'];
                $progress->client_id = $user->id;
                $progress->max_point = $request['max_answer'];
                $progress->correct_answers = $request['correct_answer'];
                $progress->quiz_date = $request['quiz_date'];
                $quiz = new Quizclient();
                $quiz->quiz_id = $request['quiz_id'];
                $quiz->client_id = $user->id;
                $progress->save();
                $quiz->save();
                $user->step = $user->step+1;
                $user->last_action = $request['quiz_date'];
                $user->save();

                $result['statusCode'] = 200;
                $result['message'] = 'success';   
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'User not found';
                $result['result'] = null;                
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
            if(isset($user->status)){
        		$item['status'] = $user->status;
            }else{
                $item['status'] = '';
            }
            if(isset($user->avatar)){
                $item['avatar'] = asset($user->avatar);
            }else{
                $item['avatar'] = '';
            }
            if(isset($user->fio)){
                $item['fio'] = $user->fio;
            }else{
                $item['fio'] = '';
            }
            if(isset($user->weight)){
                $item['weight'] = $user->weight;
            }else{
                $item['weight'] = 0;
            }
            if(isset($user->age)){
                $item['age'] = $user->age;
            }else{
                $item['age'] = 0;
            }
            if(isset($user->height)){
                $item['height'] = $user->height;
            }else{
                $item['height'] = 0;
            }
            $goals = Goalclient::where('client_id', $user->id)->get(); 
            foreach ($goals as $goal) {
                $item['goals'][] = Goal::find($goal->goal_id)->title;
            }
            $meals = Mealclient::where('client_id', $user->id)->get(); 
            foreach ($meals as $meal) {
                $item['meals'][] = Meal::find($meal->meal_id)->title;
            }
    		$item['created_at'] = Carbon::parse($user->created_at)->format('d M, Y');
    		$item['updated_at'] = Carbon::parse($user->updated_at)->format('d M, Y');
    		return $item;
    	}else{
    		return null;
    	}
    }
    public function GetChat($chat_id){
        $chat = Chat::find($chat_id);
        if ($chat!=null) {
            $item['id'] = $chat->id;
            $item['from'] = $chat->from_u;
            $item['to'] = $chat->to_u;
            $item['message'] = $chat->message;
            if ($chat->image_1) {
                $item['image'][0] = asset($chat->image_1);
            }
            if ($chat->image_2) {
                $item['image'][1] = asset($chat->image_2);
            }
            if ($chat->image_3) {
                $item['image'][2] = asset($chat->image_3);
            }
            if ($chat->image_4) {
                $item['image'][3] = asset($chat->image_4);
            }
            if ($chat->image_5) {
                $item['image'][4] = asset($chat->image_5);
            }
            $item['readed'] = $chat->readed;
            $item['deleted'] = $chat->deleted;
            $item['created_at'] = Carbon::parse($chat->created_at)->format('Y-m-d H:i:s');
            // $item['sended_date'] = $chat->sended_date;
            // $item['sended_time'] = $chat->sended_time;
            return $item;
        }else{
            return null;
        }
    }
    public function GetTask($task_id,$step,$user){
        $task = Task::find($task_id);
        if ($task!=null) {
            $item['id'] = $task->id;
            $item['title'] = $task->title;
            $item['image'] = $task->image;
            $item['text'] = $task->text;
            if ($user->step >= $step) {
                $item['access'] = 1;
            }else{
                $item['access'] = 0;
            }
        }else{
            $item = null;
        }
        return $item;
    }
    public function GetQuiz($quiz_id,$step,$user){
        $quiz = Quiz::find($quiz_id);
        if ($quiz!=null) {
            $item['id'] = $quiz->id;
            $item['title'] = $quiz->title;
            $first = $user->step >= $step;
            $img = Chat::where('from_u', $user->token)
                                            ->whereNotNull('image_1')
                                            ->orderBy('created_at', 'DESC')
                                            ->first();
            if (isset($img)) {
                $dateimg = $img->sended_date;
                $date = Carbon::parse($dateimg);
                $second = $date->diffInDays(Carbon::now())<8;
            }else{
                $second = false;
            }            
            $third = Carbon::parse($user->last_action)->diffInDays(Carbon::now())>6;
            if (($first)&&($second)&&($third)) {
                $item['access'] = 1;
            }else{
                $item['access'] = 0;
            }
            if ((Quizclient::where('quiz_id', $quiz->id)->where('client_id', $user->id)->first()!=null)) {
                $item['status'] = 1;
            }else{
                $item['status'] = 0;
            }
            $item['step'] = $first;
            $item['img_in7days'] = $second;
            $item['7days'] = $third;
            $item['last_action_day'] = Carbon::parse($user->last_action)->format('Y-m-d');
        }else{
            $item = null;
        }
        return $item;
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

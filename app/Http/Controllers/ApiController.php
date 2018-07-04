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
use App\Models\Post;
use App\Models\Goalclient;
use App\Models\Mealclient;
use App\Models\Chat;
use DateTime;
use App\Models\Progress;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Quizclient;

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
                    $goal_c = new Goalclient();
                    $goal_c->client_id = $user->id;
                    $goal_c->goal_id = $goal->id;
                    $goal_c->save();

                    $result['statusCode']= 200;
                    $result['message']= 'Success!';
                    $result['result']= $this->GetUser($user->id);
                }else{
                    $result['statusCode']= 201;
                    $result['message']= 'This goal is choosen';
                    $result['result']= $this->GetUser($user->id);
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

    public function Tasks(){
        $tasks = Task::all();

        if  (count($tasks) != 0){
            $result['statusCode'] = 200;
            $result['message'] = 'success';

            foreach ($tasks as $task) {
                $temp['id'] = $task->id;
                $temp['title'] = $task->title;
                $temp['text'] = $task->text;
                if (isset($task->image)) {
                    $temp['image'] = asset($task->image);
                }
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
    // public function Post($id){
    //     $post = Post::find($id);

    //     if  (count($post) != 0){
    //         $result['statusCode'] = 200;
    //         $result['message'] = 'success';
    //         $result['result'] = $post;
    //     }
    //     else{
    //         $result['statusCode'] = 404;
    //         $result['message'] = 'Post not found';
    //         $result['result'] = [];
    //     }
    //     return response()->json($result, $result['statusCode']);
    // }

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
                ->orderBy('sended_time', 'DESC')
                ->orderBy('sended_date', 'DESC')
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
                    $chat->readed = 1;
                    $chat->save();
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
            'sended_date' => 'required|date|date_format:Y-m-d',
            'sended_time' => 'required|date_format:H:i:s',
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
                $chat->sended_date = $request['sended_date']; 
                $chat->sended_time = $request['sended_time']; 
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
            $quizzes = Quiz::all();
            if (count($quizzes)!=0) {
                $result['statusCode'] = 200;
                $result['message'] = 'success';
                foreach ($quizzes as $quiz) {
                    $temp['id'] = $quiz->id;
                    if (Quizclient::where('quiz_id', $quiz->id)->where('client_id', $user->id)->first()!=null) {
                        $temp['status'] = 1;
                    }else{
                        $temp['status'] = 0;
                    } 
                    $temp['task_id'] = $quiz->task_id;
                    $temp['title'] = $quiz->title;
                    $temp['start_time'] = $quiz->start_time;
                    $temp['end_time'] = $quiz->end_time;

                    $result['result'][] = $temp;
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
                $item['weight'] = $user->weight.' кг';
            }else{
                $item['weight'] = '';
            }
            if(isset($user->age)){
                $item['age'] = $user->age.' лет';
            }else{
                $item['age'] = '';
            }
            if(isset($user->height)){
                $item['height'] = $user->height.' см';
            }else{
                $item['height'] = '';
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
            $item['sended_date'] = $chat->sended_date;
            $item['sended_time'] = $chat->sended_time;
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
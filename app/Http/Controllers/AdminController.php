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

class AdminController extends Controller
{
       
    public function Auth(Request $request){
        if (($request['username'] == 'tarelka') && ($request['password'] == 'qazplm123')) {
            session()->put('vK68TF23TfYKYDBZSCC9', 1);
            session()->save();
            return redirect()->route('Index')->with('success-auth', 'Well done! You have successfully signed in');
        } else {
            return redirect()->route('Login')->with('message', 'Неправильный пароль или логин!!!');
        }
    }
    public function Index(){
    	date_default_timezone_set('Asia/Almaty');
        $dbchats = Chat::where('to_u', 'admin')
            ->groupBy('from_u')
            ->orderBy('created_at', 'DESC')
            ->orderBy('readed', 'DESC')
            ->get();
        $chats = [];
        foreach ($dbchats as $item) {
            $chats[] = $this->GetConver($item->id);
        }
    	return view('index', compact(['chats']));
    }    
    public function Chat($id){
        $client = Client::find($id);
        $chats = Chat::where('from_u', $client->token)
                     ->orWhere('to_u', $client->token)                     
                     ->orderBy('created_at', 'ASC')
                     ->get();
        foreach ($chats as $chat) {
            $chat->readed = 1;
            $chat->save();
        }
        return view('action.chat', compact(['client', 'chats']));
    }
    public function SendMessage(Request $request){
        $chat = new Chat();
        $chat->from_u = 'admin';
        $chat->to_u = $request['client_token'];
        $chat->message = $request['message'];
        $chat->save();
        return back();
    }
    public function logout(){
        session()->forget('vK68TF23TfYKYDBZSCC9');
        session()->save();
        return redirect()->route('Login');
    }
    public function Clients(){
        $clients = Client::all();
        return view('action.clients', compact(['clients']));
    }
    public function DeleteClient($id){
        $client = Client::find($id);
        if ($client!=null) {
            $goalclients = Goalclient::where('client_id', $client->id)->get();
            if (count($goalclients)!=0) {
                foreach ($goalclients as $goalclient) {
                    $goalclient->delete();
                }
            }
            $mealclients = Mealclient::where('client_id', $client->id)->get();
            if (count($mealclients)!=0) {
                foreach ($mealclients as $mealclient) {
                    $mealclient->delete();
                }
            }
            $progresses = Progress::where('client_id', $client->id)->get();
            if (count($progresses)!=0) {
                foreach ($progresses as $progress) {
                    $progress->delete();
                }
            }
            $quizclients = Quizclient::where('client_id', $client->id)->get();
            if (count($quizclients)!=0) {
                foreach ($quizclients as $quizclient) {
                    $quizclient->delete();
                }
            }
            $this->deletefile($client->avatar);
            $client->delete();
            return back();
        }else{
            return  'nothing to delete';
        }
    }
    public function Upgrade($id){
        $client = Client::find($id);
        if ($client!=null) {
            $client->status = 'pro';
            $client->save();
            return back();
        }else{
            return 'nothing to upgrade';
        }
    }
    public function Downgrade($id){
        $client = Client::find($id);
        if ($client!=null) {
            $client->status = null;
            $client->save();
            return back();
        }else{
            return 'nothing to upgrade';
        }
    }
    public function Goals(){
        $goals = Goal::all();
        return view('action.goal', compact(['goals']));
    }
    public function AddGoal(Request $request){
        $goal = new Goal();
        $goal->title = $request['title'];
        $goal->image = $this->uploadfile($request['image']);
        $goal->save(); 
        return back()->with('success_add', 'Успешно добавлено');
    }
    public function DeleteGoal($id){
        $goal = Goal::find($id);
        if ($goal!=null) {
            $this->deletefile($goal->image);
            $goal->delete();
            return back()->with('success_delete', 'Успешно удалено');
        }else{
            return 'nothing to delete';
        }
    }
    public function EditGoal($id){
        $goal = Goal::find($id);
        if ($goal!=null) {
            return view('action.edit_goal', compact(['goal']));
        }else{
            return 'nothing to edit';
        }
    }
    public function SaveGoal(Request $request){
        $goal = Goal::find($request['id']);
        if (isset($request['title'])) {
            $goal->title = $request['title'];
        }
        if (isset($request['image'])) {
            $this->deletefile($goal->image);
            $goal->image = $this->uploadfile($request['image']);
        }
        $goal->save(); 
        return back()->with('success_save', 'Успешно сохранено');
    }
    public function Meals(){
        $meals = Meal::all();
        return view('action.meal', compact(['meals']));
    }
    public function AddMeal(Request $request){
        $meal = new Meal();
        $meal->title = $request['title'];
        $meal->image = $this->uploadfile($request['image']);
        $meal->save(); 
        return back()->with('success_add', 'Успешно добавлено');
    }
    public function DeleteMeal($id){
        $meal = Meal::find($id);
        if ($meal!=null) {
            $this->deletefile($meal->image);
            $meal->delete();
            return back()->with('success_delete', 'Успешно удалено');
        }else{
            return 'nothing to delete';
        }
    }
    public function EditMeal($id){
        $meal = Meal::find($id);
        if ($meal!=null) {
            return view('action.edit_meal', compact(['meal']));
        }else{
            return 'nothing to edit';
        }
    }
    public function SaveMeal(Request $request){
        $meal = Meal::find($request['id']);
        if (isset($request['title'])) {
            $meal->title = $request['title'];
        }
        if (isset($request['image'])) {
            $this->deletefile($meal->image);
            $meal->image = $this->uploadfile($request['image']);
        }
        $meal->save(); 
        return back()->with('success_save', 'Успешно сохранено');
    }    
    public function Quiz(){
        $quizzes = Quiz::orderBy('created_at', 'DESC')->get();
        return view('action.quiz', compact(['quizzes']));
    }
    public function AddQuiz(Request $request){
        $quiz = new Quiz();
        $quiz->task_id = 1;
        $quiz->title = $request['title'];
        $quiz->start_time = $request['start_time'];
        $quiz->end_time = $request['end_time'];
        $quiz->save();
        return back()->with('success_action', 'Успешно добавлено');
    }
    public function DeleteQuiz($id){
        $quiz = Quiz::find($id);
        if ($quiz!=null) {
            $quiz->delete();
            $questions = Question::where('quiz_id', $id)->get();
            if (count($questions)!=0) {
                foreach ($questions as $question) {
                    $question->delete();
                }
            }
            return back()->with('success_delete', 'Успешно удалено');
        }else{
            return 'nothing to delete';
        }
    }
    public function EditQuiz($id){
        $quiz = Quiz::find($id);
        if ($quiz!=null) {
            return view('action.edit_quiz', compact(['quiz']));
        }else{
            return 'nothing to edit';
        }
    }
    public function SaveQuiz(Request $request){
        $quiz = Quiz::find($request['id']);
        if (isset($request['title'])) {
            $quiz->title = $request['title'];
        }
        if (isset($request['start_time'])) {
            $quiz->start_time = $request['start_time'];
        }
        if (isset($request['end_time'])) {
            $quiz->end_time = $request['end_time'];
        }
        $quiz->save(); 
        return back()->with('success_save', 'Успешно сохранено');
    } 
    public function Question($id){
        $quiz_id = $id;
        $questions = Question::where('quiz_id', $id)->get();
        return view('action.question', compact(['questions', 'quiz_id']));
    }
    public function AddQuestion(Request $request){
        $question = new Question();
        $question->question = $request['question'];
        $question->quiz_id = $request['quiz_id'];
        $question->answer_a = $request['answer_a'];
        $question->answer_b = $request['answer_b'];
        $question->answer_c = $request['answer_c'];
        $question->answer_d = $request['answer_d'];
        $question->answer_e = $request['answer_e'];
        $question->right_answer = $request['right_answer'];
        $question->save();
        return back();
    }
    public function DeleteQuestion($id){
        $question = Question::find($id);
        if ($question!=null) {
            $question->delete();
            return back()->with('success_delete', 'Успешно удалено');
        }else{
            return 'nothing to delete';
        }
    }
    public function EditQuestion($id){
        $question = Question::find($id);
        if ($question!=null) {
            return view('action.edit_question', compact(['question']));
        }else{
            return 'nothing to edit';
        }
    }    
    public function SaveQuestion(Request $request){
        $question = Question::find($request['id']);
        if (isset($request['quiz_id'])) {
            $question->quiz_id = $request['quiz_id'];
        }
        if (isset($request['question'])) {
            $question->question = $request['question'];
        }
        if (isset($request['right_answer'])) {
            $question->right_answer = $request['right_answer'];
        }
        if (isset($request['answer_a'])) {
            $question->answer_a = $request['answer_a'];
        }
        if (isset($request['answer_b'])) {
            $question->answer_b = $request['answer_b'];
        }
        if (isset($request['answer_c'])) {
            $question->answer_c = $request['answer_c'];
        }
        if (isset($request['answer_d'])) {
            $question->answer_d = $request['answer_d'];
        }
        if (isset($request['answer_e'])) {
            $question->answer_e = $request['answer_e'];
        }
        $question->save(); 
        return back()->with('success_save', 'Успешно сохранено');
    } 

    public function Tasks(){
        $tasks = Task::orderBy('created_at', 'DESC')->get();
        return view('action.task', compact(['tasks']));
    }
    public function AddTask(Request $request){
        $task = new Task();
        $task->title = $request['title'];
        $task->text = $request['text'];
        $task->image = $this->uploadfile($request['image']);
        $task->save();
        return back()->with('success_action', 'Успешно добавлено');
    }
    public function DeleteTask($id){
        $task = Task::find($id);
        if ($task!=null) {
            $this->deletefile($task->image);
            $task->delete();
            return back()->with('success_delete', 'Успешно удалено');
        }else{
            return 'nothing to delete';
        }
    }
    public function EditTask($id){
        $task = Task::find($id);
        if ($task!=null) {
            return view('action.edit_task', compact(['task']));
        }else{
            return 'nothing to edit';
        }
    }
    public function SaveTask(Request $request){
        $task = Task::find($request['id']);
        if (isset($request['title'])) {
            $task->title = $request['title'];
        }
        if (isset($request['image'])) {
            $this->deletefile($task->image);
            $task->image = $this->uploadfile($request['image']);
        }
        if (isset($request['text'])) {
            $task->text = $request['text'];
        }
        $task->save(); 
        return back()->with('success_save', 'Успешно сохранено');
    } 

    public function GetConver($chat_id){
        $chat = Chat::find($chat_id);
        if ($chat->from_u == 'admin') {
            $item['from'] = 'admin'; 
            $item['to'] = Client::where('token', $chat->to_u)->first()->fio;
        }else{
            $item['to'] = 'admin'; 
            $item['client_id'] = Client::where('token', $chat->from_u)->first()->id;
            $item['from'] = Client::where('token', $chat->from_u)->first()->fio;
            $item['phone'] = Client::where('token', $chat->from_u)->first()->phone;
            $item['avatar'] = Client::where('token', $chat->from_u)->first()->avatar;
            $item['last_message']['message'] = Chat::where('from_u', $chat->from_u)
                                        ->orderBy('created_at','DESC')
                                        ->first()
                                        ->message;
            $item['readed'] = Chat::where('from_u', $chat->from_u)
                                        ->orderBy('created_at','DESC')
                                        ->first()
                                        ->readed;
        }
        return $item;
    }

    public static function Rate($id){
        $client = Client::find($id);
        if ($client!=null) {
            $scoreq = Quizclient::where('client_id', $client->id)->count();
            $scoreimg = [];
            $img = 0;
            for ($i=0; $i < 5; $i++) { 
                $scoreimg[$i] = Chat::where('from_u', $client->token)->whereNotNull('image_'.($i+1))->count();
            }
            for ($i=0; $i < 5; $i++) { 
                $img = $img + $scoreimg[$i];
            }

            return (10*$scoreq)+$img;
        }
        return null;
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

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
    	return view('index');
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
    }public function EditGoal($id){
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
    }public function EditMeal($id){
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
        return view('action.quiz');
    }
    public function Tasks(){
        return view('action.task');
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

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
}

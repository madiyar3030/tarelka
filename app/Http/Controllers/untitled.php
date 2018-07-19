<?php
    public function Register(Request $request){
	    $rules = [
	        'phone' => 'required',
	        'name' => 'required',
	    ];
	    $validator = $this->validator($request->all(),$rules);
	    if($validator->fails()) {
	        $result['statusCode']= 400;
	        $result['message']= $validator->errors();
	        $result['result']= [];
	    }
	    else {
	        $user = User::where('phone',$request['phone'])->first();
	        if (!$user){
	            $check = new CheckCode();
	            $check->phone = $request['phone'];
	            $check->name = $request['name'];
	            $check->code = rand ( 1000 ,9999);
	            $check->save();

	            $sms =new Sms();
	            $sms->send("+7$user->phone", "Код подтверждения  $user->code", 'Kassa24', 5);

	            $result['statusCode'] = 200;
	            $result['message'] = "success sms send";
	            $result['result'] = [];
	        }
	        else{
	            $result['statusCode'] = 400;
	            $result['message'] = "exist";
	            $result['result'] = [];
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
	        $check = CheckCode::where('phone',$request['phone'])
	            ->where('code',$request['code'])
	            ->orderBy('id','DESC')
	            ->first();

	        if ($check){
	            $user  = User::where('phone',$check->phone)->first();
	           if (!$user){
	               $user = new User();
	               $user->phone = $check->phone;
	               $user->name =  $check->name;
	               $user->token =  str_random(30);
	               $user->save();

	               $result['statusCode'] = 200;
	               $result['message'] = "success";
	               $result['result'] = $user;
	           }
	           else{
	               $result['statusCode'] = 200;
	               $result['message'] = "success";
	               $result['result'] = $user;
	           }
	        }
	        else{

	            $result['statusCode'] = 404;
	            $result['message'] = "not found";
	            $result['result'] = [];

	        }
		}
	    return response()->json($result, $result['statusCode']);
	}
    public function Auth(Request $request){
        $rules = [
            'token' => 'required|exists:users,token',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }
        else {
            $result['statusCode'] = 200;
            $result['message'] = "success";
            $result['result'] = User::where('token',$request['token'])->first();
        }
        return response()->json($result, $result['statusCode']);
    }
    public function Login(Request $request){
        $rules = [
            'phone' => 'required|exists:users,phone',
        ];
        $validator = $this->validator($request->all(),$rules);
        if($validator->fails()) {
            $result['statusCode']= 400;
            $result['message']= $validator->errors();
            $result['result']= [];
        }
        else {
            $user = User::where('phone',$request['phone'])->first();
            $user->code = rand ( 1000 ,9999);
            $user->save();

            $sms =new Sms();
            $sms->send("+7$user->phone", "Код подтверждения  $user->code", 'Kassa24', 5);

            $result['statusCode'] = 200;
            $result['message'] = "success,sms send!";
            $result['result'] = [];
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
    public function Taskss(){
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
                $temp['updated_at'] = Carbon::parse($task->updated_at)->format('Y-m-d H:i:s');
                $temp['created_at'] = Carbon::parse($task->created_at)->format('Y-m-d H:i:s');

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
    public function ListQuizz(Request $request){
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
                $scoreimg = [];     
                $dateimg = Chat::where('from_u', $user->token)
                                            ->whereNotNull('image_1')
                                            ->orderBy('created_at', 'DESC')
                                            ->first();
                if (isset($dateimg)) {
                    $scoreimg['img_date'] = $dateimg->sended_date;
                    $date = Carbon::parse($scoreimg['img_date']);
                    $diff = $date->diffInDays(Carbon::now());
                }else{
                    $diff = 8;
                }         
                for ($i=0; $i < 5; $i++) {                     
                    $scoreimg[$i] = Chat::where('from_u', $user->token)->whereNotNull('image_'.($i+1))->count();
                }
                if ($diff>7) {
                    $result['statusCode'] = 201;
                    $result['message'] = 'Download image';
                    $result['result'] = null;
                }else{
                    foreach ($quizzes as $quiz) {
                        $temp['id'] = $quiz->id;
                        if ((Quizclient::where('quiz_id', $quiz->id)->where('client_id', $user->id)->first()!=null)) {
                            $temp['status'] = 1;
                        }else{
                            $temp['status'] = 0;
                        }
                        $temp['title'] = $quiz->title;

                        $result['result'][] = $temp;
                    }   
                }     
            }else{
                $result['statusCode'] = 404;
                $result['message'] = 'Quizzes not found';
                $result['result'] = null;                
            }
        }
        return response()->json($result, $result['statusCode']);
    }
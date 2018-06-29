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
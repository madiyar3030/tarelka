@extends('app')
@section('active_index', 'active')
@section('overflow', 'chat')
@section('title', 'Чат')
@section('content')
    <section class="content chat">
        <div class="container-fluid">
            <div class="block-header">
                <a href="{{route('Index')}}" class="btn btn-default waves-effect">Назад</a>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card card-chat">
                        <div class="header bg-red">
                            <a href="">
                                <h2>
                                   {{$client->fio}}<small>{{$client->phone}}</small>
                                </h2> 
                            </a>
                            <img src="{{asset($client->avatar)}}" width="40" height="40" style="border-radius: 50%; display: block; margin-bottom: 5px;" class="m-b-5">
                        </div>
                        <div class="body">
                            <div class="chat-body" id="chatbody">
                                @foreach($chats as $chat)
                                    @if($chat->to_u == 'admin')
                                    <div class="row to">
                                        <div class="col-md6">
                                            <div class="col-md-10">
                                                <p>{{$chat->message}}</p>
                                                @if(isset($chat->image_1))
                                                <a href="{{asset($chat->image_1)}}" target="_blank">
                                                    <img src="{{asset($chat->image_1)}}" width="100" alt="">
                                                </a>
                                                @endif
                                                @if(isset($chat->image_2))
                                                <a href="{{asset($chat->image_2)}}" target="_blank">
                                                    <img src="{{asset($chat->image_2)}}" width="100" alt="">
                                                </a>
                                                @endif
                                                @if(isset($chat->image_3))
                                                <a href="{{asset($chat->image_3)}}" target="_blank">
                                                    <img src="{{asset($chat->image_3)}}" width="100" alt="">
                                                </a>
                                                @endif
                                                @if(isset($chat->image_4))
                                                <a href="{{asset($chat->image_4)}}" target="_blank">
                                                    <img src="{{asset($chat->image_4)}}" width="100" alt="">
                                                </a>
                                                @endif
                                                @if(isset($chat->image_5))
                                                <a href="{{asset($chat->image_5)}}" target="_blank">
                                                    <img src="{{asset($chat->image_5)}}" width="100" alt="">
                                                </a>
                                                @endif                               
                                            </div>
                                            <div class="col-md-2">
                                                <span>{{substr($chat->sended_time, 0, 5)}}</span>
                                                    {{$chat->sended_date}}
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        <div class="row from">
                                            <div class="col-md6">
                                                <div class="col-md-10">
                                                    {{$chat->message}}
                                                </div>
                                                <div class="col-md-2">
                                                    {{substr($chat->sended_time, 0, 5)}}
                                                    {{$chat->sended_date}}
                                                </div>
                                            </div>
                                        </div>                                    
                                    @endif
                                @endforeach
                            </div>                        
                            <div class="input-group chat-input">
                                <div class="form-line">
                                    <input type="text" class="form-control date" placeholder="Message">
                                </div>
                                <span class="input-group-addon">
                                    <button class="btn btn-default waves-effect" type="submit"><i class="material-icons">send</i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-danger">
                <h4>There are no messages</h4>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script>
    var objDiv = document.getElementById("chatbody");
objDiv.scrollTop = objDiv.scrollHeight;
</script>
@endsection
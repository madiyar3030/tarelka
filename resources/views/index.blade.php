@extends('app')
@section('active_index', 'active')
@section('title', 'Главная страница')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Чат</h2>
            </div>
            @if(count($chats)!=0)
                <div class="row clearfix">
                    @foreach($chats as $chat)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="header bg-red">
                                    <a href="{{route('Chat',$chat['client_id'])}}">
                                        <h2>
                                            {{$chat['from']}} <small>{{$chat['phone']}}</small>
                                        </h2> 
                                    </a>
                                    <ul class="header-dropdown m-r--5">
                                        <li class="dropdown">
                                            <img src="{{asset($chat['avatar'])}}" width="40" height="40" style="border-radius: 50%">
                                        </li>
                                    </ul>                               
                                </div>
                                <div class="body">
                                    @if(isset($chat['last_message']['message']))
                                        {{$chat['last_message']['message']}}
                                    @else
                                        Новое изображение
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
            <div class="alert alert-danger">
                <h4>There are no messages</h4>
            </div>
            @endif
        </div>
    </section>
@endsection
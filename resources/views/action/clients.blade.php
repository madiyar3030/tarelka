@extends('app')
@section('active_clients', 'active')
@section('title', 'Пользователи')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Пользователи</h2>
            </div>
            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ФИО</th>
                                        <th>Телефон</th>
                                        <th>Статус</th>
                                        <th>Фото</th>
                                        <th>Рост</th>
                                        <th>Вес</th>
                                        <th>Возраст</th>
                                        <th>Рейтинг</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1?>
                                    @foreach($clients as $client)
                                        <tr>
                                            <th scope="row">{{$i++}}</th>
                                            <td>{{$client->fio}}</td>
                                            <td>{{$client->phone}}</td>
                                            <td>{{$client->status}}</td>
                                            <td>
                                                <img src="{{asset($client->avatar)}}" alt="" width="150">
                                            </td>
                                            <td>
                                                @if(isset($client->height))
                                                    {{$client->height}} см
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($client->weight))
                                                    {{$client->weight}} кг
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($client->age))
                                                    {{$client->age}} лет
                                                @endif
                                            </td>
                                            <td>{{Helpers::Rate($client->id)}}</td>
                                            <td>
                                                @if($client->status == 'pro')
                                                <a href="{{route('Downgrade',$client->id)}}" class="btn btn-primary waves-effect">Понизить</a>
                                                @else
                                                <a href="{{route('Upgrade',$client->id)}}" class="btn btn-success waves-effect">Повысить</a>
                                                @endif
                                                <a href="{{route('DeleteClient',$client->id)}}" class="btn btn-danger waves-effect">Удалить</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Hover Rows -->
        </div>
    </section>
@endsection
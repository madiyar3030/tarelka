@extends('app')
@section('active_schedule', 'active')
@section('title', 'Расписание')
@section('content')	
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Расписание</h2>
                <a href="{{route('Schedule')}}" class="btn btn-default waves-effect m-t-10">Назад</a>
            </div>
            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">                    	
	                	<div class="body">
	                		@foreach ($errors->all() as $error)
		                        <div class="message">
		                            <li>{{ $error }}</li>
		                        </div>
		                    @endforeach
		                    @if(session()->has('success_save'))
		                    	<div class="alert alert-success">
		                    		{{session()->get('success_save')}}
		                    	</div>
		                    @else
			                    <form action="{{route('SaveSchedule')}}" method="post">
			                    	{{csrf_field()}}
			                    	<input type="text" name="id" value="{{$schedule->id}}" hidden>
			                    	<div class="col-sm-12 m-t-20">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="step" value="{{$schedule->step}}" required>
			                                    <label class="form-label">Шаг</label>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="col-sm-12">
			                        	<div class="form-group">
			                        		<label for="">Задание</label>
			                                <div class="form-line">
						                    	<select class="form-control" name="task_id" required>
						                    		<option value="">Выберите</option>
						                            @foreach(\App\Models\Task::all() as $task)
														<option {{($task->id == $schedule->task_id) ? 'selected': ''}} value="{{$task->id}}">{{$task->title}}</option>
						                            @endforeach
						                        </select>
						                    </div>
				                        </div>
			                        </div>
			                        <div class="col-sm-12">
			                        	<div class="form-group">
			                        		<label for="">Экзамен</label>
			                                <div class="form-line">
						                    	<select class="form-control" name="quiz_id" required>
						                    		<option value="">Выберите</option>
						                            @foreach(\App\Models\Quiz::all() as $quiz)
														<option {{($quiz->id == $schedule->quiz_id) ? 'selected': ''}} value="{{$quiz->id}}">{{$quiz->title}}</option>
						                            @endforeach
						                        </select>
						                    </div>
				                        </div>
			                        </div>
			                    	<button type="submit" class="btn btn-primary waves-effect">Сохранить</button>
		                    	</form>
	                    	@endif
		                </div>
                    </div>
                </div>
            </div>
            <!-- #END# Hover Rows -->
        </div>
    </section>
@endsection
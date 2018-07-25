@extends('app')
@section('active_schedule', 'active')
@section('title', 'Расписание')
@section('content')
	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Расписание</h2>
                <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#defaultModal">Добавить</button>
            </div>
            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    	@if(count($schedules)!=0)
	                        <div class="body table-responsive">
	                            <table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>#</th>
	                                        <th>Шаг</th>
	                                        <th>Задание</th>
	                                        <th>Экзамен</th>
	                                        <th>Действие</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php $i = 1?>
	                                    @foreach($schedules as $schedule)
	                                        <tr>
	                                            <th scope="row">{{$i++}}</th>
	                                            <td>{{$schedule->step}}</td>
	                                            <td>{{\App\Models\Task::find($schedule->task_id)->title}}</td>
	                                            <td>{{\App\Models\Quiz::find($schedule->quiz_id)->title}}</td>
	                                            <td>
	                                            	<a href="{{route('EditSchedule', $schedule->id)}}" class="btn btn-primary waves-effect">Редактировать</a>		
	                                            	<a href="{{route('DeleteSchedule', $schedule->id)}}" class="btn btn-danger waves-effect">Удалить</a>
	                                            </td>
	                                        </tr>
	                                    @endforeach
	                                </tbody>
	                            </table>
	                        </div>
                        @else
                        	<div class="alert alert-danger">
                        		There are no schedule. If you want to add <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#defaultModal">Click here</button>
                        	</div>
                        @endif
                    </div>
                </div>
            </div>                            
            <!-- #END# Hover Rows -->
        </div>
    </section>
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Добавить расписание</h4>
                </div>
                <form action="{{route('AddSchedule')}}" method="post" id="myForm" enctype="multipart/form-data">
                	<div class="modal-body">
                		@foreach ($errors->all() as $error)
	                        <div class="message">
	                            <li>{{ $error }}</li>
	                        </div>
	                    @endforeach
                    	{{csrf_field()}}
                    	<div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="number" class="form-control" name="step" required>
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
			                            	<option value="{{$task->id}}">{{$task->title}}</option>
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
			                            	<option value="{{$quiz->id}}">{{$quiz->title}}</option>
			                            @endforeach
			                        </select>
			                    </div>
	                        </div>
                        </div>
	                </div>
	                <div class="modal-footer">
	                    <button type="submit" class="btn btn-link waves-effect">Добавить</button>
	                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Отмена</button>
	                </div>
                </form>                
            </div>
        </div>
    </div>
@endsection
@section('js')

<script src="{{asset('public/plugins/autosize/autosize.js')}}"></script>
<script src="{{asset('public/plugins/momentjs/moment.js')}}"></script>
<script src="{{asset('public/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
<script src="{{asset('public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>    
<script>
	$(function () {
    //Textare auto growth
    autosize($('textarea.auto-growth'));

    //Datetimepicker plugin
    // $('.datetimepicker').bootstrapMaterialDatePicker({
    //     format: 'YYYY-MM-DD HH:mm:ss',
    //     clearButton: true,
    //     weekStart: 1
    // });
});
</script>
<script src="{{asset('public/plugins/jquery-validation/jquery.validate.js')}}"></script>
	<script type="text/javascript">
		$(function () {
		    $('#myForm').validate({
		        rules: {
		            'start_time': {
		                required: true
		            },
		            'title': {
		                required: true
		            },
		            'end_time': {
		                required: true
		            }
		        },
		        highlight: function (input) {
		            $(input).parents('.form-line').addClass('error');
		        },
		        unhighlight: function (input) {
		            $(input).parents('.form-line').removeClass('error');
		        },
		        errorPlacement: function (error, element) {
		            $(element).parents('.form-group').append(error);
		        }
			});
		    //==================================================================================================
		});
	</script>
@endsection
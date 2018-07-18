@extends('app')
@section('active_quiz', 'active')
@section('title', 'Экзамены')
@section('content')
	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Экзамены</h2>
                <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#defaultModal">Добавить</button>
            </div>
            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    	@if(count($quizzes)!=0)
	                        <div class="body table-responsive">
	                            <table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>#</th>
	                                        <th>Заголовок</th>
	                                        <th>Начало</th>
	                                        <th>Конец</th>
	                                        <th>Действие</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php $i = 1?>
	                                    @foreach($quizzes as $quiz)
	                                        <tr>
	                                            <th scope="row">{{$i++}}</th>
	                                            <td>{{$quiz->title}}</td>
	                                            <td>{{$quiz->start_time}}</td>
	                                            <td>{{$quiz->end_time}}</td>
	                                            <td>
	                                            	<a href="{{route('EditQuiz', $quiz->id)}}" class="btn btn-primary waves-effect">Редактировать</a>		
	                                            	<a href="{{route('Question', $quiz->id)}}" class="btn btn-success waves-effect">Вопросы</a>	
	                                            	<a href="{{route('DeleteQuiz', $quiz->id)}}" class="btn btn-danger waves-effect">Удалить</a>
	                                            </td>
	                                        </tr>
	                                    @endforeach
	                                </tbody>
	                            </table>
	                        </div>
                        @else
                        	<div class="alert alert-danger">
                        		There are no meals. If you want to add <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#defaultModal">Click here</button>
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
                    <h4 class="modal-title" id="defaultModalLabel">Добавить экзамен</h4>
                </div>
                <form action="{{route('AddQuiz')}}" method="post" id="myForm" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control" name="title">
                                    <label class="form-label">Заголовок</label>
                                </div>
                            </div>
                        </div>
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="start_time" class="datetimepicker form-control" placeholder="Начало">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="end_time" class="datetimepicker form-control" placeholder="Конец">
                                    </div>
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
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        clearButton: true,
        weekStart: 1
    });
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
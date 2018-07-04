@extends('app')
@section('active_quiz', 'active')
@section('title', 'Вопрос')
@section('content')	
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Вопрос</h2>
                <a href="{{route('Question', $question->quiz_id)}}" class="btn btn-default waves-effect m-t-10">Назад</a>
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
			                    <form action="{{route('SaveQuestion')}}" method="post" id="AddGoal">
			                    	{{csrf_field()}}
			                    	<input type="text" name="id" value="{{$question->id}}" hidden>
			                    	<div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="question" value="{{$question->question}}">
			                                    <label class="form-label">Вопрос</label>
			                                </div>
			                            </div>
			                        </div>			  
			                        <div class="col-sm-12">
			                            <select class="form-control show-tick" name="right_answer">
			                                <option value="{{$question->right_answer}}">{{$question->right_answer}}</option>
			                                <option value="A">A</option>
			                                <option value="B">B</option>
			                                <option value="C">C</option>
			                                <option value="D">D</option>
			                                <option value="E">E</option>
			                            </select>
			                        </div>
			                        <div class="col-sm-12 m-t-10">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="answer_a" value="{{$question->answer_a}}">
			                                    <label class="form-label">Вариант(A)</label>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="answer_b" value="{{$question->answer_b}}">
			                                    <label class="form-label">Вариант(B)</label>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="answer_c" value="{{$question->answer_c}}">
			                                    <label class="form-label">Вариант(C)</label>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="answer_d" value="{{$question->answer_d}}">
			                                    <label class="form-label">Вариант(D)</label>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="answer_e" value="{{$question->answer_e}}">
			                                    <label class="form-label">Вариант(E)</label>
			                                </div>
			                            </div>
			                        </div>
			                    	<button type="submit" class="btn btn-primary waves-effect">Сохранить	</button>
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
<script src="../../plugins/jquery-validation/jquery.validate.js"></script>
<!-- <script type="text/javascript">
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
</script> -->
@endsection
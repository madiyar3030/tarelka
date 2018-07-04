@extends('app')
@section('active_quiz', 'active')
@section('title', 'Вопросы')
@section('content')
	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Вопросы</h2>
                <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#defaultModal">Добавить</button>
                <a href="{{route('Quiz')}}" class="btn btn-default waves-effect m-t-10">Назад</a>
            </div>
            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    	@if(count($questions)!=0)
	                        <div class="body table-responsive">
	                            <table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>#</th>
	                                        <th>Экзамен</th>
	                                        <th>Вопрос</th>
	                                        <th>Вариант(A)</th>
	                                        <th>Вариант(B)</th>
	                                        <th>Вариант(C)</th>
	                                        <th>Вариант(D)</th>
	                                        <th>Вариант(E)</th>
	                                        <th>Правильный ответ</th>
	                                        <th>Действие</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php $i = 1?>
	                                    @foreach($questions as $question)
	                                        <tr>
	                                            <th scope="row">{{$i++}}</th>
	                                            <td>
	                                            	{{\App\Models\Quiz::find($question->quiz_id)->title}}
	                                            </td>
	                                            <td>{{$question->question}}</td>
	                                            <td>{{$question->answer_a}}</td>
	                                            <td>{{$question->answer_b}}</td>
	                                            <td>{{$question->answer_c}}</td>
	                                            <td>{{$question->answer_d}}</td>
	                                            <td>{{$question->answer_e}}</td>
	                                            <td>{{$question->right_answer}}</td>
	                                            <td>
	                                            	<a href="{{route('EditQuestion', $question->id)}}" class="btn btn-primary waves-effect"><i class="material-icons">mode_edit</i></a>	
	                                            	<a href="{{route('DeleteQuestion', $question->id)}}" class="btn btn-danger waves-effect"><i class="material-icons">delete_forever</i></a>
	                                            </td>
	                                        </tr>
	                                    @endforeach
	                                </tbody>
	                            </table>
	                        </div>
                        @else
                        	<div class="alert alert-danger">
                        		There are no questions.
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
                    <h4 class="modal-title" id="defaultModalLabel">Добавить вопрос</h4>
                </div>
                <form action="{{route('AddQuestion')}}" method="post" id="myForm" enctype="multipart/form-data">
                	<input type="text" name="quiz_id" value="{{$quiz_id}}" hidden>
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
                                    <input type="text" class="form-control" name="question">
                                    <label class="form-label">Вопрос</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <select class="form-control show-tick" name="right_answer">
                                <option value="">-- Правильный ответ --</option>
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
                                    <input type="text" class="form-control" name="answer_a">
                                    <label class="form-label">Ответ(A)</label>
                                </div>
                            </div>
                        </div>
                    	<div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="answer_b">
                                    <label class="form-label">Ответ(B)</label>
                                </div>
                            </div>
                        </div>
                    	<div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="answer_c">
                                    <label class="form-label">Ответ(C)</label>
                                </div>
                            </div>
                        </div>
                    	<div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="answer_d">
                                    <label class="form-label">Ответ(D)</label>
                                </div>
                            </div>
                        </div>
                    	<div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                	<input type="text" class="form-control" name="answer_e">
                                    <label class="form-label">Ответ(E)</label>
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
		            'question': {
		                required: true
		            },
		            'right_answer': {
		                required: true
		            },
		            'answer_a': {
		                required: true
		            },
		            'answer_b': {
		                required: true
		            },
		            'answer_c': {
		                required: true
		            },
		            'answer_d': {
		                required: true
		            },
		            'answer_e': {
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
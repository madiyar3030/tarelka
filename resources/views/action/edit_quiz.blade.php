@extends('app')
@section('active_quiz', 'active')
@section('title', 'Экзамены')
@section('content')	
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Экзамены</h2>
                <a href="{{route('Quiz')}}" class="btn btn-default waves-effect m-t-10">Назад</a>
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
			                    <form action="{{route('SaveQuiz')}}" method="post" id="AddGoal" enctype="multipart/form-data">
			                    	{{csrf_field()}}
			                    	<input type="text" name="id" value="{{$quiz->id}}" hidden>
			                    	<div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="title" value="{{$quiz->title}}">
			                                    <label class="form-label">Заголовок</label>
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
@extends('app')
@section('active_task', 'active')
@section('title', 'Задание')
@section('content')	
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Блюда</h2>
                <a href="{{route('Tasks')}}" class="btn btn-default waves-effect m-t-10">Назад</a>
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
			                    <form action="{{route('SaveTask')}}" method="post" enctype="multipart/form-data">
			                    	{{csrf_field()}}
			                    	<input type="text" name="id" value="{{$task->id}}" hidden>
			                    	<div class="col-sm-12">
			                            <div class="form-group form-float">
			                                <div class="form-line">
			                                    <input type="text" class="form-control" name="title" value="{{$task->title}}">
			                                    <label class="form-label">Заголовок</label>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="col-sm-12">
			                        	<img src="{{asset($task->image)}}" alt="" id="blah" height="150">
			                        </div>
			                        <div class="col-sm-12">
			                            <div class="form-group">
			                            	<label class="form-label">Изображение</label>
			                                <input type="file" class="form-control" name="image" onchange="readURL(this)">
			                            </div>
			                        </div>
			                       <div class="col-sm-12">
	                                    <div class="form-group">
	                                        <div class="form-line">
	                                            <textarea rows="4" class="form-control no-resize" placeholder="Please type what you want...">
	                                            	{{$task->text}}
	                                            </textarea>
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
    <!-- Jquery Validation Plugin Css -->
    <script src="{{asset('public/plugins/jquery-validation/jquery.validate.js')}}"></script>
	<script type="text/javascript">
		function readURL(input) {

		if (input.files && input.files[0]) {
		    var reader = new FileReader();

		    reader.onload = function(e) {
		      $('#blah').attr('src', e.target.result);
		    }
		    	reader.readAsDataURL(input.files[0]);
			}
		}
		// $(function () {
		//     $('#AddGoal').validate({
		//         rules: {
		//             'image': {
		//                 required: true
		//             },
		//             'title': {
		//                 required: true
		//             }
		//         },
		//         highlight: function (input) {
		//             $(input).parents('.form-line').addClass('error');
		//         },
		//         unhighlight: function (input) {
		//             $(input).parents('.form-line').removeClass('error');
		//         },
		//         errorPlacement: function (error, element) {
		//             $(element).parents('.form-group').append(error);
		//         }
		// 	});
		//     //==================================================================================================
		// });
	</script>
@endsection
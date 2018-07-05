@extends('app')
@section('active_task', 'active')
@section('title', 'Задание')
@section('content')
	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Полезные информации</h2>
                <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#largeModal">Добавить</button>
            </div>
			@if(count($tasks)!=0)
				@foreach($tasks as $task)
		            <div class="row clearfix">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header bg-red">
		                        	<a href="{{route('EditTask', $task->id)}}">
			                            <h2>
			                                {{$task->title}}
			                            </h2> 		                        		
		                        	</a>
		                            <ul class="header-dropdown m-r--5">
		                                <li class="dropdown">
		                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		                                        <i class="material-icons">more_vert</i>
		                                    </a>
		                                    <ul class="dropdown-menu pull-right">
		                                        <li><a href="{{route('DeleteTask', $task->id)}}">Удалить</a></li>
		                                        <li><a href="{{route('EditTask', $task->id)}}">Редактировать</a></li>
		                                    </ul>
		                                </li>
		                            </ul>                          
		                        </div>
		                        <div class="body row">
		                        	<div class="col-md-2">
		                        		<img src="{{asset($task->image)}}" alt="" width="130">
		                        	</div>
		                        	<div class="col-md-10">
			                            {{$task->text}}
		                        	</div>
		                        </div>
		                    </div>
		                </div>
		            </div>
	            @endforeach
            @else
            	<div class="alert alert-danger">
            		There are no meals.
            	</div>            	
            @endif
        </div>
    </section>
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="largeModalLabel">Добавить задание</h4>
                </div>
                <form action="{{route('AddTask')}}" method="post" id="AddGoal" enctype="multipart/form-data">
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
                        <div class="col-sm-12">
                        	<img src="" alt="" id="blah" height="150">
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                            	<label class="form-label">Изображение</label>
                                <input type="file" class="form-control" name="image" onchange="readURL(this)">
                            </div>
                        </div>
                       	<div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <textarea rows="4" class="form-control no-resize" name="text"></textarea>
                                    <label for="" class="form-label">Текст</label>
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
    <!-- Jquery Validation Plugin Css -->
    <script src="../../plugins/jquery-validation/jquery.validate.js"></script>
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
		$(function () {
		    $('#AddGoal').validate({
		        rules: {
		            'image': {
		                required: true
		            },
		            'title': {
		                required: true
		            },
		            'text': {
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
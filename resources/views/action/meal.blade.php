@extends('app')
@section('active_meals', 'active')
@section('active_date', 'active')
@section('title', 'Знакомства | Блюда')
@section('content')	
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Блюда</h2>
                <button type="button" class="btn btn-default waves-effect m-t-10" data-toggle="modal" data-target="#defaultModal">Добавить</button>
            </div>
            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    	@if(count($meals)!=0)
	                        <div class="body table-responsive">
	                            <table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>#</th>
	                                        <th>Заголовок</th>
	                                        <th>Фото</th>
	                                        <th>Действие</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php $i = 1?>
	                                    @foreach($meals as $meal)
	                                        <tr>
	                                            <th scope="row">{{$i++}}</th>
	                                            <td>{{$meal->title}}</td>
	                                            <td>
	                                                <img src="{{asset($meal->image)}}" alt="" width="150">
	                                            </td>
	                                            <td>
	                                            	<a href="{{route('EditMeal', $meal->id)}}" class="btn btn-primary waves-effect">Редактировать</a>		
	                                            	<a href="{{route('DeleteMeal', $meal->id)}}" class="btn btn-danger waves-effect">Удалить</a>	
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
                    <h4 class="modal-title" id="defaultModalLabel">Добавить блюдо</h4>
                </div>
                <form action="{{route('AddMeal')}}" method="post" id="AddMeal" enctype="multipart/form-data">
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
		    $('#AddMeal').validate({
		        rules: {
		            'image': {
		                required: true
		            },
		            'title': {
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
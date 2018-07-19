@extends('app')
@section('active_info', 'active')
@section('title', 'Данные администратора')
@section('content')
	 <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Данные администратора</h2>
            </div>
            @if($info!=null)
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header bg-red">
                                <h2>
                                    egtr <small>wrgewg</small>
                                </h2> 
                            </div>
                            <div class="body demo-masked-input">
                            	<form action="{{route('SaveInfo')}}" method="post">
                            		{{csrf_field()}}
                                	<div class="form-group m-t-10">
                                        <label for="" class="">Whatsapp(Телефон номер)</label>
	                                    <div class="form-line">
	                                        <input type="text" class="form-control mobile-phone-number" name="whatsapp" value="{{$info->whatsapp}}">
	                                    </div>
	                                </div>
	                                <div class="form-group m-t-10">
                                        <label for="" class="">Telegram(username)</label>
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" name="telegram" value="{{$info->telegram}}">
	                                    </div>
	                                </div>
	                                <div class="form-group m-t-10">
                                        <label for="" class="">VK(ID)</label>
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" name="vk" value="{{$info->vk}}">
	                                    </div>
	                                </div>
	                                <div class="form-group m-t-10">
                                        <label for="" class="">Facebook(ID)</label>
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" name="facebook" value="{{$info->facebook}}">
	                                    </div>
	                                </div>
	                                <div class="form-group m-t-10">
                                        <label for="" class="">Viber(URL)</label>
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" name="viber" value="{{$info->viber}}">
	                                    </div>
	                                </div>
	                                <input type="submit" value="Сохранить" class="btn btn-primary waves-effect">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            <div class="alert alert-danger">
                <h4>There are no info</h4>
            </div>
            @endif
        </div>
    </section>
@endsection
@section('js')
    <!-- Input Mask Plugin Js -->
    <script src="{{asset('public/plugins/jquery-inputmask/jquery.inputmask.bundle.js')}}"></script>
	<script>
		$(function(){
			$('.demo-masked-input').find('.mobile-phone-number').inputmask('+9 (999) 999-99-99', { placeholder: '+_ (___) ___-__-__' });
		})
	</script>
@endsection
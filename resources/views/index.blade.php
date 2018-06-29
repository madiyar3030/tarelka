@extends('app')
@section('active_index', 'active')
@section('title', 'Главная страница')
@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Чаты</h2>
            </div>
            <!-- Basic Example -->
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-red">
                            <h2>
                                Red - Title <small>Description text here...</small>
                            </h2> 
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <img src="images/user.png" height="40" style="border-radius: 50%">
                                </li>
                            </ul>                               
                        </div>
                        <div class="body">
                            Quis pharetra a pharetra fames blandit. Risus faucibus velit Risus imperdiet mattis neque volutpat, etiam lacinia netus dictum magnis per facilisi sociosqu. Volutpat. Ridiculus nostra.
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Example -->
        </div>
    </section>
@endsection
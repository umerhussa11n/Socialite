@extends('app')

@section('title', 'Add New Library')

@section('main_content')

	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')

				{{Form::open(array('url'=>'library/save','method'=>'POST','class' => 'form'))}}

						@include('library.form')

				{{ Form::close() }}


			</div>
		</div>
	</div>
@endsection
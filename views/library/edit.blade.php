@extends('app')

@section('title', 'Edit Library')

@section('main_content')

	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')

				
				{{ Form::model($library,array('url'=>'library/update/'.$library->id,'method'=>'Post','class' => 'form')) }} 

						@include('library.form')

				{{ Form::close() }}


			</div>
		</div>
	</div>
@endsection
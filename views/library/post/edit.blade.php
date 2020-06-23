@extends('app')

@section('title', 'Add New Post')

@section('main_content')

	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')

				{{ Form::model($post,array('url'=>'library/'.$lid.'/post/'.$post->id,'method'=>'PUT','class' => 'form')) }} 

						@include('library.post.form')

				{{ Form::close() }}


			</div>
		</div>
	</div>
@endsection
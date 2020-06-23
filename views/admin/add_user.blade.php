@extends('app')

@section('title', 'Add new User')

@section('main_content')	
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-6">
				@include('message')
				<form method="POST" action="/admin/create-user">
					<div class="form-group">
					<label for="username">User Name</label>
					<input type="text" class="form-control" id="username" name="name" value="{{ Input::old('name') }}" placeholder="User Name">
				  </div>
				  <div class="form-group">
					<label for="email">Email address</label>
					<input type="email" class="form-control" id="email" name="email" value="{{ Input::old('email') }}" placeholder="Email">
				  </div>
				  <div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				  </div>
				  <div class="form-group">
					<label for="password_confirmation">Password Confirmation</label>
					<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password Confirmation">
				  </div>
				  <button type="button" class="btn btn-default" onclick="location.href='{{ URL::to('admin') }}'">Back</button>
				  <button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
@endsection
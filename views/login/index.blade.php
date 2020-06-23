@extends('layouts.login')

@section('main_content')
<div class="container" id="wrapper">
	<div id="main_body" class="container">
	<div class="col-lg-12 login-heading">
		<h3 style="text-align:center">Content Grabber for Facebook Pages</h3>
		<div class="description">Ultimate Script for Facebook Pages </div>
		<hr>
	</div>

	<div class="col-lg-4 col-lg-offset-4" id="main_body_inner">
	<div class="title" style="background-color:rgb(78, 78, 78)"><span class="fa fa-user"></span>&nbsp; Admin Login</div>
	<div class="info">					
		<div id="orders_list"></div>
		<div class="control-group">
			<label class="control-label">Enter Username</label>
			<input id="username" type="text" class="form-control" placeholder="Username Here">
		</div>
		<div class="control-group">
			<label class="control-label">Enter Password</label>
			<input id="password" type="password" class="form-control" placeholder="Password Here">
		</div>
		<div class="control-group">
			<button id="submit_details" class="btn btn-primary btn-block">Login</button>
		</div>
	</div>
</div>
<script>
$("#submit_details").click(function(event) {
	save_login_settings();	
});

$("#username, #password").keyup(function (e) {
	if (e.keyCode == 13) {
    	save_login_settings();
	}
});

function save_login_settings(){
	$this = $("#submit_details");
	var username = $("#username").val();
	var password = $("#password").val();
	if(username == '' || password == ''){
		swal("Oops","Please fill in all fields","error");
		return false;
	}
	$this.button('loading');
	$.ajax({
		url: '{{url("login")}}',
		type: 'POST',
		data: {username: username,password:password},
		success: function(res){
			console.log(res);
			$this.button('reset');
			if(res == 'error'){
				swal("Oops","Wrong Details, please check again","error");
			} if(res == 'db'){
				swal({   
					title: "DB Error",
					text: "Cant connect to database, Please install the script if you did not from <a href='install.php'><strong>Here</strong></a>",
					html: true
				 });			
			} else{
				window.location = '{{url("facebook")}}';
			}
		}
	});		
}


@if (Input::has('loggedout'))
swal("Logged Out","Successfully Logged Out","success");
@endif

@if (Input::has('required'))
swal("Login Required","Login required to navigate","error");
@endif
</script>
<style>
	.login-heading 	h3{
		font-size: 24px;
		font-weight: normal;
		font-weight: bold;		
	}
	.login-heading .description{
		font-size: 16px;
		text-align: center;
	}
	.login-heading hr{
		margin-bottom: 0px;
		border-top: 1px solid #DFDFDF;
	}
</style>
@stop
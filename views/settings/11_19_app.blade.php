@extends('app')

@section('title', 'Settings')

@section('main_content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-lg-12">
				@if (Input::has('settings'))
					<div class="alert alert-warning alert-dismissable">
		              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		              <strong>Oops!</strong> Invalid Facebook App Settings / Sandbox Mode, Please check the details.
		            </div>
				@endif
				{{--<ol class="breadcrumb breadcrumb-arrow">--}}
                  {{--<li><a href="#"><i class="fa fa-gear"></i> &nbsp;Settings</a></li>--}}
                  {{--<li><a href="{{url('settings/fb-app-settings')}}"><i class="fa fa-facebook-official"></i>&nbsp; App Settings</a></li>--}}
                {{--</ol>--}}

                <!-- Schedule time settings -->

                <div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							<img src="{{url('assets/img/timezone.png')}}" width="150px" style="margin-top:8px">
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">
		                		<label for="">Select Your Timezone</label>
		                		<select name="" id="timezone" class="form-control" style="max-width:350px">
		                			<option value="Pacific/Wake">Pacific/Wake</option>
									<option value="Pacific/Apia">Pacific/Apia</option>
									<option value="Pacific/Honolulu">Pacific/Honolulu</option>
									<option value="America/Anchorage">America/Anchorage</option>
									<option value="America/Los_Angeles">America/Los_Angeles</option>
									<option value="America/Phoenix">America/Phoenix</option>
									<option value="America/Chihuahua">America/Chihuahua</option>
									<option value="America/Denver">America/Denver</option>
									<option value="America/Managua">America/Managua</option>
									<option value="America/Chicago">America/Chicago</option>
									<option value="America/Mexico_City">America/Mexico_City</option>
									<option value="America/Regina">America/Regina</option>
									<option value="America/Bogota">America/Bogota</option>
									<option value="America/New_York">America/New_York</option>
									<option value="America/Indiana">America/Indiana</option>
									<option value="America/Halifax">America/Halifax</option>
									<option value="America/Caracas">America/Caracas</option>
									<option value="America/Santiago">America/Santiago</option>
									<option value="America/St_Johns">America/St_Johns</option>
									<option value="America/Sao_Paulo">America/Sao_Paulo</option>
									<option value="America/Argentina">America/Argentina</option>
									<option value="America/Godthab">America/Godthab</option>
									<option value="America/Noronha">America/Noronha</option>
									<option value="Atlantic/Azores">Atlantic/Azores</option>
									<option value="Atlantic/Cape_Verde">Atlantic/Cape_Verde</option>
									<option value="Africa/Casablanca">Africa/Casablanca</option>
									<option value="Europe/London">Europe/London</option>
									<option value="Europe/Berlin">Europe/Berlin</option>
									<option value="Europe/Belgrade">Europe/Belgrade</option>
									<option value="Europe/Paris">Europe/Paris</option>
									<option value="Europe/Sarajevo">Europe/Sarajevo</option>
									<option value="Africa/Lagos">Africa/Lagos</option>
									<option value="Europe/Istanbul">Europe/Istanbul</option>
									<option value="Europe/Bucharest">Europe/Bucharest</option>
									<option value="Africa/Cairo">Africa/Cairo</option>
									<option value="Africa/Johannesburg">Africa/Johannesburg</option>
									<option value="Europe/Helsinki">Europe/Helsinki</option>
									<option value="Asia/Jerusalem">Asia/Jerusalem</option>
									<option value="Asia/Baghdad">Asia/Baghdad</option>
									<option value="Asia/Riyadh">Asia/Riyadh</option>
									<option value="Europe/Moscow">Europe/Moscow</option>
									<option value="Africa/Nairobi">Africa/Nairobi</option>
									<option value="Asia/Tehran">Asia/Tehran</option>
									<option value="Asia/Muscat">Asia/Muscat</option>
									<option value="Asia/Tbilisi">Asia/Tbilisi</option>
									<option value="Asia/Kabul">Asia/Kabul</option>
									<option value="Asia/Yekaterinburg">Asia/Yekaterinburg</option>
									<option value="Asia/Karachi">Asia/Karachi</option>
									<option value="Asia/Calcutta">Asia/Calcutta</option>
									<option value="Asia/Katmandu">Asia/Katmandu</option>
									<option value="Asia/Novosibirsk">Asia/Novosibirsk</option>
									<option value="Asia/Dhaka">Asia/Dhaka</option>
									<option value="Asia/Colombo">Asia/Colombo</option>
									<option value="Asia/Rangoon">Asia/Rangoon</option>
									<option value="Asia/Bangkok">Asia/Bangkok</option>
									<option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk</option>
									<option value="Asia/Hong_Kong">Asia/Hong_Kong</option>
									<option value="Asia/Irkutsk">Asia/Irkutsk</option>
									<option value="Asia/Singapore">Asia/Singapore</option>
									<option value="Australia/Perth">Australia/Perth</option>
									<option value="Asia/Taipei">Asia/Taipei</option>
									<option value="Asia/Tokyo">Asia/Tokyo</option>
									<option value="Asia/Seoul">Asia/Seoul</option>
									<option value="Asia/Yakutsk">Asia/Yakutsk</option>
									<option value="Australia/Adelaide">Australia/Adelaide</option>
									<option value="Australia/Darwin">Australia/Darwin</option>
									<option value="Australia/Brisbane">Australia/Brisbane</option>
									<option value="Australia/Sydney">Australia/Sydney</option>
									<option value="Pacific/Guam">Pacific/Guam</option>
									<option value="Australia/Hobart">Australia/Hobart</option>
									<option value="Asia/Vladivostok">Asia/Vladivostok</option>
									<option value="Asia/Magadan">Asia/Magadan</option>
									<option value="Pacific/Auckland">Pacific/Auckland</option>
									<option value="Pacific/Fiji">Pacific/Fiji</option>
									<option value="Pacific/Tongatapu">Pacific/Tongatapu</option>
		                		</select>
		                	</div>             	
		                	<div class="form-group">                	
		                		<button onclick="save_timezone()" class="btn btn-primary">Save TimeZone</button>
		                	</div>
		                	<p>
		                	<span>Timezone is important for scheduling fb posts correctly</span></p>
		                </div>
					</div>
				</div>
				<hr>
				{{-- Facebook app settings --}}

				<div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							<img src="{{url('assets/img/fb-app-icon.png')}}" height="150px" style="margin-top:8px">
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">
		                		<label for="">Application id</label>
		                		<input id="app_id" type="text" value="{{$app_id}}" placeholder="App Id" class="form-control">
		                	</div>
		                	<div class="form-group">                	
		                		<label for="">Application Secret</label>
		                		<input id="app_secret" type="text"  value="{{$app_secret}}" placeholder="App Secret" class="form-control">
		                	</div>                	
		                	<div class="form-group">                	
		                		<button onclick="save_settings()" class="btn btn-primary">Save Settings & Reload Access Tokens</button>
		                	</div>
		                	<p>
		                		<span>Please note that app should <strong style="color:red">Not</strong> be in <strong>SANDBOX</strong> mode while saving settings </span></p>
		                </div>
					</div>
				</div>
				
				<hr>
				
				{{-- Twitter app settings --}}

				<div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							<img src="{{url('assets/img/twi.png')}}" height="150px" style="margin-top:8px">
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">
		                		<label for="consumer_key">Consumer Key</label>
		                		<input id="consumer_key" type="text" value="" placeholder="Consumer Key" class="form-control">
		                	</div>
		                	<div class="form-group">                	
		                		<label for="consumer_secret">Consumer Secret</label>
		                		<input id="consumer_secret" type="text"  value="" placeholder="Consumer Secret" class="form-control">
		                	</div>    
							<div class="form-group">                	
		                		<label for="access_token">Access Token</label>
		                		<input id="access_token" type="text"  value="" placeholder="Access Token" class="form-control">
		                	</div> 	
							<div class="form-group">                	
		                		<label for="access_token_secret">Access Token Secret</label>
		                		<input id="access_token_secret" type="text"  value="" placeholder="Access Token Secret" class="form-control">
		                	</div> 								
		                	<div class="form-group">                	
		                		<button onclick="add_twitter_setting()" class="btn btn-primary">Add Twitter Setting</button>
		                	</div>
		                </div>
					</div>
				</div>
				
				<hr>
				{{-- Instagram app settings --}}

				<div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							<img src="{{url('assets/img/ins.png')}}" height="150px" style="margin-top:8px">
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">
		                		<label for="username">User Name</label>
		                		<input id="username" type="text" value="" placeholder="User Name" class="form-control">
		                	</div>
		                	<div class="form-group">                	
		                		<label for="password">Password</label>
		                		<input id="password" type="text"  value="" placeholder="Password" class="form-control" autocomplete="false">
		                	</div>
							<div class="form-group">                	
		                		<button onclick="add_instagram_setting()" class="btn btn-primary">Add Instagram Setting</button>
		                	</div>							
		                </div>
					</div>
				</div>
				
				<hr>
				{{-- Dropbox app settings --}}

				<div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							<img src="{{url('assets/img/chrome.png')}}" height="150px" style="margin-top:8px">
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder" style="margin-top:15px">
		                	<div class="form-group">
		                		<label for="">Chrome Secret Key</label>		                		
		                		<input id="chrome_extension" value="{{$chrome_key}}" type="text" value="" placeholder="Chrome Extension key" class="form-control">
		                	</div>
		                	<div class="form-group">                	
		                		<button onclick="generateNewKey()" class="btn btn-primary">Generate New Key</button>
		                	</div>
		                	<p>
		                		<span>You have to save this coupon in your chrome extension options page to use chrome extension. This key is required for security purposes</span></p>
		                </div>
					</div>
				</div>


				{{--<hr>--}}
				{{-- Chrome app settings --}}

				{{--<div class="row">--}}
					{{--<div class="col-lg-2">--}}
						{{--<div class="form-group" style="text-align:center">--}}
							{{--<img src="{{url('assets/img/dropbox.png')}}" height="150px" style="margin-top:8px">--}}
						{{--</div>--}}
					{{--</div>					--}}
					{{--<div class="col-lg-10">						--}}
		                {{--<div class="settings-holder" style="margin-top:15px">--}}
		                	{{--<div class="form-group">--}}
		                		{{--<label for="">Dropbox API Key</label>--}}
		                		{{--<input id="dropbox_api" value="{{$dropbox_api}}" type="text" value="" placeholder="dropbox API Key" class="form-control">--}}
		                	{{--</div>--}}
		                	{{--<div class="form-group">                	--}}
		                		{{--<button onclick="save_dropbox_api()" class="btn btn-primary">Save API Key</button>--}}
		                	{{--</div>--}}
		                {{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}


			</div>
		</div>
</div>
@endsection

@section('js')
<script>	
jQuery(document).ready(function($) {
	$("#timezone").val('{{$timezone}}');
});
function save_settings(){
	var app_id = $("#app_id").val();
	var app_secret = $("#app_secret").val();
	if(app_id.length == 0){
		return showError('App id cant be blank');
	}
	if(app_secret.length == 0){
		return showError('App Secret cant be blank');
	}

    $(".preloader").show();

	$.ajax({
		url: '{{url("settings/check-app")}}',
		type: 'POST',
		data: {app_id: app_id, app_secret:app_secret},
		success : function(res){
            $(".preloader").hide();
			if(res == 'invalid'){
				showError('Seems Like App details are wrong/sandbox mode');
			} else {
				window.location = res;
			}
		}
	})
	.fail(function() {
        $(".preloader").hide();
		return showError('Seems Like App details are wrong');
	});	
}

function add_twitter_setting(){
	var consumer_key = $("#consumer_key").val().trim();
	var consumer_secret = $("#consumer_secret").val().trim();
	var access_token = $("#access_token").val().trim();
	var access_token_secret = $("#access_token_secret").val().trim();

	if(consumer_key.length == 0){
		return showError('Consumer Key cant be blank');
	}
	
	if(consumer_secret.length == 0){
		return showError('Consumer Secret cant be blank');
	}
	
	if(access_token.length == 0){
		return showError('Access Token cant be blank');
	}
	
	if(access_token_secret.length == 0){
		return showError('Access Token Secret cant be blank');
	}

    $(".preloader").show();
	$.ajax({
		url: '{{url("settings/add-twitter-setting")}}',
		type: 'POST',
		data: {consumer_key: consumer_key, consumer_secret: consumer_secret, access_token: access_token, access_token_secret: access_token_secret},
		success: function(res){
            $(".preloader").hide();
			if (res == 'false')
			{
				swal("Error", "Bad Authentication data", "error");
			} else
			{
				swal("Saved", "Twitter setting saved successfully", "success");
			}
		}
	});
	
}

function add_instagram_setting(){
	var username = $("#username").val().trim();
	var password = $("#password").val().trim();
	
	if(username.length == 0){
		return showError('User Name cant be blank');
	}
	
	if(password.length == 0){
		return showError('Password cant be blank');
	}

    $(".preloader").show();
	$.ajax({
		url: '{{url("settings/add-instagram-setting")}}',
		type: 'POST',
		data: {username: username, password: password},
		success: function(res){
            $(".preloader").hide();
			if (res !== 'ok')
			{
				swal("Error", res, "error");
			} else
			{
				swal("Saved", "Instagram setting saved successfully", "success");
			}
		}
	});
	
}

function save_dropbox_api(){
	var dropbox_api = $("#dropbox_api").val();
	if(dropbox_api.length == 0){
		return showError('Dropbox API Key cant be blank');
	}

    $(".preloader").show();
	$.ajax({
		url: '{{url("settings/save-dropbox-api")}}',
		type: 'POST',
		data: {dropbox_api: dropbox_api},
		success: function(res){
            $(".preloader").hide();
			swal("Saved", "Dropbox API Key saved successfully", "success");
		}
	});
	
}

function showError(msg){
	sweetAlert("Oops...", msg, "error");
	return false;
}

function generateNewKey(){
    $(".preloader").show();
	$.ajax({
		url: '{{url("settings/new-key")}}',
		type: 'POST',
		success : function(res){
			location.reload();
		}
	});	
}

function save_timezone(){
	var timezone = $("#timezone").val();
    $(".preloader").show();
	$.ajax({
		url: '{{url("settings/save-timezone")}}',
		type: 'POST',
		data: {timezone: timezone},
		success : function(res){
			location.reload();
		}
	});
	
}


$("#chrome_extension").focus(function() {
    var $this = $(this);
    $this.select();

    // Work around Chrome's little problem
    $this.mouseup(function() {
        // Prevent further mouseup intervention
        $this.unbind("mouseup");
        return false;
    });
});
</script>
@stop
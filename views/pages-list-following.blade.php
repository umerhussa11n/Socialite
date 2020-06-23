@extends('app')

@section('title', 'Pages To Follow')

@section('main_content')
	<div class="box box-block bg-white">
			<div class="row y-p-1" style="margin: 25px 0 0 15px">
				<div class="col-lg-6">
					<h5 style="font-size:16px"><i class="fa fa-list"></i>&nbsp; Our Twitter accounts in Feed</h5>
					<hr style="margin:5px 0px">
					<div class="add-watch-page">
						<div class="add-watch-page-container">
							<h5><i class="fa fa-plus-circle"></i>&nbsp; Add New Twitter account to Watch</h5>
							<div class="form-group">								
								<div class="input-group">
								  <span class="input-group-addon" id="twitter-url">http://www.twitter.com/</span>
								  <input type="text" id="twitter-account" class="form-control" placeholder="Ending URL" aria-describedby="twitter-url">
								</div>
							</div>							
							<button id="btn-add-twitter-follow" class="btn btn-block btn-primary" onclick="add_twitter_follow();">Add Twitter Account</button>							
						</div>
					</div>
					<div class="pages-list-holder">
						<ul>
							@foreach ($follow_twitters as $twitter)
								<?php 
									$cover = empty($twitter->cover_image) ? URL::to('assets/img/page-background.jpg') : $twitter->cover_image;
								 ?>

								<li class="page" style="background-image: url('{{$cover}}');background-size:cover">
									<div class="icon-holder">
										<img src="{{$twitter->profile_image}}" alt="">
									</div>
									<div class="page-details">
										<div class="page-name">{{$twitter->name}} ({{ $twitter->screen_name }})</div>
										<div class="page-description">
											<?php echo substr($twitter->description, 0,80); ?>...
										</div>
									</div>
									<div class="close-button">
										<img ftid="{{$twitter->owner_id}}" src="{{url('assets/img/close.png')}}" alt="">
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
				</div>
			<div class="row p-y-1" style="margin: 25px 0 0 15px">
				<div class="col-lg-6">
					<div class="desk-heading">						
						<h5><i class="fa fa-list"></i>&nbsp;Facebook Pages To Follow and Show in Feed</h5><hr style="margin: 5px 0px;">
						<p><strong>Note :</strong>&nbsp;Facebook Pages you want to copy posts from Enter below.</p>
					</div>
					<div class="add-watch-page">
						<div class="add-watch-page-container">
							<h5><i class="fa fa-plus-circle"></i>&nbsp; Add New Facebook Page to Watch</h5>
							<div class="form-group">								
								<div class="description">Add ending url of the Facebook page that you want to follow for example to add <a href="https://www.facebook.com/9gag" target="_blank">https://www.facebook.com/9gag</a> page just enter 9gag in the input box below.</div>
							</div>
							<div class="form-group">								
								<div class="input-group">
								  <span class="input-group-addon" id="basic-addon2">http://www.facebook.com/</span>
								  <input type="text" id="page_id" class="form-control" placeholder="Page Ending URL" aria-describedby="basic-addon2">
								</div>
							</div>							
							<button id="add_page" class="btn btn-block btn-primary" onclick="add_new_page();">Add Facebook Page</button>							
						</div>
					</div>
					<div class="pages-list-holder">
						<ul>
							@foreach ($followPages as $page)	
								<?php 
									if(! strlen($page->cover) < 7){
										$cover = $page->cover;
									} else {
										$cover = URL::to('assets/img/page-background.jpg');
									}
								 ?>
								<li class="page" style="background-image: url('{{$cover}}');background-size:cover">
									<div class="icon-holder">
										<img src="https://graph.facebook.com/{{$page->page_id}}/picture" alt="">
									</div>
									<div class="page-details">
										<div class="page-name">{{$page->name}}</div>
										<div class="page-description">
											<?php echo substr($page->description, 0,80); ?>...
										</div>
									</div>
									<div class="close-button">
										<img ffid="{{$page->id}}" src="{{url('assets/img/close.png')}}" alt="">
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
				</div>

		<div class="row y-p-1" style="margin: 25px 0 0 15px">
				
				<div class="col-lg-6 pages-side">
					<h5 style="font-size:16px"><i class="fa fa-list"></i>&nbsp; Our Instagram accounts in Feed</h5>
					<hr style="margin:5px 0px">
					<div class="add-watch-page">
						<div class="add-watch-page-container">
							<h5><i class="fa fa-plus-circle"></i>&nbsp; Add New Instagram account to Watch</h5>
							<div class="form-group">								
								<div class="input-group">
								  <span class="input-group-addon" id="instagram-url">http://www.instagram.com/</span>
								  <input type="text" id="instagram-account" class="form-control" placeholder="Ending URL" aria-describedby="instagram-url">
								</div>
							</div>							
							<button id="btn-add-instagram-follow" class="btn btn-block btn-primary" onclick="add_instagram_follow();">Add Instagram Account</button>							
						</div>
					</div>
					<div class="pages-list-holder">
						<ul>
							@foreach ($follow_instagrams as $instagram)
								<?php 
									$cover = URL::to('assets/img/page-background.jpg');
								 ?>

								<li class="page" style="background-image: url('{{$cover}}');background-size:cover">
									<div class="icon-holder">
										<img src="{{$instagram->profile_image}}" alt="">
									</div>
									<div class="page-details">
										<div class="page-name">{{$instagram->full_name}} ({{ $instagram->username }})</div>
										<div class="page-description">
										</div>
									</div>
									<div class="close-button">
										<img fiid="{{$instagram->owner_id}}" src="{{url('assets/img/close.png')}}" alt="">
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
		</div>
	</div>
	
		@endsection
@section('js')
	<script>
		function add_new_page(){
			var page_id = $("#page_id").val();
			
			if (page_id == ''){
				sweetAlert("Oops...", "Page Ending URL can't be empty :(", "error");
				return false;
			}
			
			var button_text = $("#add_page").text();
			$("#add_page").text('Please Wait..').attr('disabled','true');

            $(".preloader").show();

			$.ajax({
				url: '{{url("pages-list/add-facebook-page-follow")}}',
				type: 'POST',
				data: {page_id: page_id},
				success: function(res){
                    $(".preloader").hide();
					console.log(res);
					
					$("#add_page").removeAttr('disabled').text(button_text);
					
					if(res == 'no_page'){						
						sweetAlert("Error", "You must add your own Facebook Page in setting first.", "error");
						return false;
					} else if(res == 'invalid'){						
						sweetAlert("Oops...", "Invalid Page URL, Please check again :(", "error");
						return false;
					} else if(res == 'ok'){						
						sweetAlert("Successfully", "New Follow Facebook Page has been added.", "success");
						location.reload();
					} else {
						sweetAlert("Error", "Unexpected error, Please check again :(", "error");
						return false;
					}
				}
			});			
		}
		
		function add_twitter_follow(){

			var twitter_account = $("#twitter-account").val();

			if (twitter_account == ''){
				sweetAlert("Oops...", "Page Ending URL can't be empty :(", "error");
				return false;
			}
			
			var button_text = $("#btn-add-twitter-follow").text();
			$("#add_twitter_follow").text('Please Wait..').attr('disabled','true');

            $(".preloader").show();
			$.ajax({
				url: '{{url("pages-list/add-twitter-account-follow")}}',
				type: 'POST',
				data: {name: twitter_account},
				success: function(res){
                    $(".preloader").hide();
					console.log(res);
					
					$("#btn-add-twitter-follow").removeAttr('disabled').text(button_text);
					
					if(res == 'no_account'){						
						sweetAlert("Error", "You must add your own Twitter account in setting first.", "error");
						return false;
					} else if(res == 'invalid'){						
						sweetAlert("Oops...", "Invalid Page URL, Please check again :(", "error");
						return false;
					} else if(res == 'ok'){						
						sweetAlert("Successfully", "New Follow Twitter Account has been added.", "success");
						location.reload();
					} else {
						sweetAlert("Error", "Unexpected error, Please check again :(", "error");
						return false;
					}
				}
			});				
		}
		
		function add_instagram_follow(){

			var instagram_account = $("#instagram-account").val();

			if (instagram_account == ''){
				sweetAlert("Oops...", "Page Ending URL can't be empty :(", "error");
				return false;
			}
			
			var button_text = $("#btn-add-instagram-follow").text();
			$("#instagram").text('Please Wait..').attr('disabled','true');

            $(".preloader").show();
			$.ajax({
				url: '{{url("pages-list/add-instagram-account-follow")}}',
				type: 'POST',
				data: {name: instagram_account},
				success: function(res){
                    $(".preloader").hide();
					console.log(res);
					
					$("#btn-add-instagram-follow").removeAttr('disabled').text(button_text);
					
					if(res == 'no_account'){						
						sweetAlert("Error", "You must add your own Instagram account in setting first.", "error");
						return false;
					} else if(res == 'invalid'){						
						sweetAlert("Oops...", "Invalid Page URL, Please check again :(", "error");
						return false;
					} else if(res == 'ok'){						
						sweetAlert("Successfully", "New Follow Instagram Account has been added.", "success");
						location.reload();
					} else {
						sweetAlert("Error", "Unexpected error, Please check again :(", "error");
						return false;
					}
				}
			});				
		}

		jQuery(document).ready(function($) {
			$(".close-button > img").click(function(event) {
				var fid = $(this).attr('fid');
				var tid = $(this).attr('tid');
				var iid = $(this).attr('iid');
				
				var ffid = $(this).attr('ffid');
				var ftid = $(this).attr('ftid');
				var fiid = $(this).attr('fiid');
				
				if (fid)
				{
					swal({
					   title: "Are you sure?",
					   text: "You want to remove this page?",
					   type: "warning",
					   showCancelButton: true,
					   confirmButtonColor: "#DD6B55",
					   confirmButtonText: "Yes, remove it!",
					   closeOnConfirm: false }, function(){
                       	 $(".preloader").show();
							$.ajax({
								url: '{{url("pages-list/remove-facebook-page")}}',
								type: 'POST',
								data: {page_id: fid},
								success : function(res){
                                    $(".preloader").hide();
									console.log(res);	
									location.reload();					
								}
							})
							.fail(function() {
								console.log("error");
                                $(".preloader").hide();
							});
					});
				} else if (tid)
				{
					swal({
					   title: "Are you sure?",
					   text: "You want to remove this account?",
					   type: "warning",
					   showCancelButton: true,
					   confirmButtonColor: "#DD6B55",
					   confirmButtonText: "Yes, remove it!",
					   closeOnConfirm: false }, function(){
                        $(".preloader").show();
							$.ajax({
								url: '{{url("pages-list/remove-twitter-account")}}',
								type: 'POST',
								data: {owner_id: tid},
								success : function(res){
                                    $(".preloader").hide();
									console.log(res);	
									location.reload();					
								}
							})
							.fail(function() {
								console.log("error");
                                $(".preloader").hide();
							});
					});
				} else if (iid)
				{
					swal({
					   title: "Are you sure?",
					   text: "You want to remove this account?",
					   type: "warning",
					   showCancelButton: true,
					   confirmButtonColor: "#DD6B55",
					   confirmButtonText: "Yes, remove it!",
					   closeOnConfirm: false }, function(){
                        $(".preloader").show();
							$.ajax({
								url: '{{url("pages-list/remove-instagram-account")}}',
								type: 'POST',
								data: {owner_id: iid},
								success : function(res){
                                    $(".preloader").hide();
									console.log(res);	
									location.reload();					
								}
							})
							.fail(function() {
								console.log("error");
                                $(".preloader").hide();
							});
					});
				} else if (ffid)
				{
					swal({
					   title: "Are you sure?",
					   text: "You want to remove this account?",
					   type: "warning",
					   showCancelButton: true,
					   confirmButtonColor: "#DD6B55",
					   confirmButtonText: "Yes, remove it!",
					   closeOnConfirm: false }, function(){
                        $(".preloader").show();
							$.ajax({
								url: '{{url("pages-list/remove-facebook-page-follow")}}',
								type: 'POST',
								data: {page_id: ffid},
								success : function(res){
                                    $(".preloader").hide();
									console.log(res);	
									location.reload();					
								}
							})
							.fail(function() {
								console.log("error");
                                $(".preloader").hide();
							});
					});
				} else if (ftid)
				{
					swal({
					   title: "Are you sure?",
					   text: "You want to remove this account?",
					   type: "warning",
					   showCancelButton: true,
					   confirmButtonColor: "#DD6B55",
					   confirmButtonText: "Yes, remove it!",
					   closeOnConfirm: false }, function(){
                        $(".preloader").show();
							$.ajax({
								url: '{{url("pages-list/remove-twitter-account-follow")}}',
								type: 'POST',
								data: {owner_id: ftid},
								success : function(res){
                                    $(".preloader").hide();
									console.log(res);	
									location.reload();					
								}
							})
							.fail(function() {
								console.log("error");
                                $(".preloader").hide();
							});
					});
				} else if (fiid)
				{
					swal({
					   title: "Are you sure?",
					   text: "You want to remove this account?",
					   type: "warning",
					   showCancelButton: true,
					   confirmButtonColor: "#DD6B55",
					   confirmButtonText: "Yes, remove it!",
					   closeOnConfirm: false }, function(){
                        $(".preloader").show();
							$.ajax({
								url: '{{url("pages-list/remove-instagram-account-follow")}}',
								type: 'POST',
								data: {owner_id: fiid},
								success : function(res){
                                    $(".preloader").hide();
									console.log(res);	
									location.reload();					
								}
							})
							.fail(function() {
								console.log("error");
                                $(".preloader").hide();
							});
					});
				}
			});
		});
	</script>

@stop
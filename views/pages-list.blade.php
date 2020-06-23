@extends('app')

@section('title', 'Our Social Pages')

@section('main_content')
	<div class="box box-block bg-white">
		<div class="row y-p-1" style="margin: 25px 0 0 15px">		
		
				<div class="col-md-6 col-md-offset-3">
					<h5 style="font-size:16px"><i class="fa fa-list"></i>&nbsp; Our Twitter accounts we can Post To</h5>
					<hr style="margin:5px 0px">
					<div class="pages-list-holder">
						<ul>
							@foreach ($twitters as $twitter)
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
										<img tid="{{$twitter->owner_id}}" src="{{url('assets/img/close.png')}}" alt="">
									</div>
                                                                    <div class="manage_btn">
                                                                        <a class="btn btn-md btn-info" href="{{url("postschedule")}}?tw_page_id={{$twitter->id}}">Manage Schedule</a>
                                                                    </div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
				</div>

		<div class="row p-y-1" style="margin: 25px 0 0 15px">
				<div class="col-lg-6 pages-side">
					<h5 style="font-size:16px"><i class="fa fa-list"></i>&nbsp; Our Facebook Pages we can Post To 
						
					</h5>
					<hr style="margin:5px 0px">
					<p style="padding:5px; margin:0px"><strong>Note :</strong> Only pages that you are admin can be used.</p>
					<div class="pages-list-holder">
						<ul>
							@foreach ($ourPages as $page)
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
										<img fid="{{$page->id}}" src="{{url('assets/img/close.png')}}" alt="">
									</div>
                                                                    <div class="manage_btn">
                                                                        <a class="btn btn-md btn-info" href="{{url("postschedule")}}?fb_page_id={{$page->id}}">Manage Schedule</a>
                                                                    </div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>

		<div class="row y-p-1" style="margin: 25px 0 0 15px">
			<div class="col-lg-6 pages-side">
					<h5 style="font-size:16px"><i class="fa fa-list"></i>&nbsp; Our Instagram accounts we can Post To 
						
					</h5>
					<hr style="margin:5px 0px">
					<div class="pages-list-holder">
						<ul>
							@foreach ($instagrams as $instagram)
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
										<img iid="{{$instagram->owner_id}}" src="{{url('assets/img/close.png')}}" alt="">
									</div>
                                                                    <div class="manage_btn">
                                                                        <a class="btn btn-md btn-info" href="{{url("postschedule")}}?inst_page_id={{$instagram->id}}">Manage Schedule</a>
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
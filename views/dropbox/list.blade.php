@extends('layouts.main')
<!-- https://www.dropbox.com/s/5e7olmcs3mkr4ek/webcam-toy-photo4.jpg?raw=1 -->
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="container-fluid">
					<div class="col-md-12 dropbox-heading" >
						<h3>Dropbox File Browser</h3>
						<div class="description">Choose Photos and Videos and post them to your FB pages Instantly</div>
					</div>
				  <div class="col-md-12 clearfix">
	                <ol class="breadcrumb breadcrumb-arrow">	                 
	                  <li><a href="{{url('dropbox?path=/')}}">home</a></li>
	                  <?php $init_path = ''; ?>
	                  @foreach ($breadcrumb as $path_element)
	                  	<?php $init_path .= '/'.$path_element ?>
	                  	<li><a href="{{url('dropbox?path='.$init_path)}}">{{$path_element}}</a></li>
	                  @endforeach
	                </ol>
	              </div>					

				  <div class="col-lg-12">
				  	@if (!count($total_files))
				  		<div class="alert alert-warning alert-dismissable">
			              <strong>Oops!</strong> looks like there are no folder, photos or videos.
			            </div>						
				  	@endif
				  	@foreach (array_chunk($total_files, 6) as $parts)
					  	<div class="row">				  		
					  		@foreach ($parts as $file)
					  			{{-- expr --}}
					  		<div class="col-lg-2">
					  			<a @if (! (isset($file['image_preview']) || isset($file['video_preview']))) 
					  				href="{{URL::to('dropbox?path='.$file['path'])}}" 
					  			@endif >
						  			<div class="file_container">
						  				<div class="file_icon">
						  					@if (isset($file['image_preview']) || isset($file['video_preview']))	
						  						<?php 
						  						if (isset($file['image_preview'])) {
						  							$icon = 'fa-photo';
						  						} else if(isset($file['video_preview'])){
						  							$icon = 'fa-video-camera';						  							
						  						}
						  						 ?>				  					
						  						<div class="quarter-circle-top-right"><span><i class="fa {{$icon}}"></i></span></div>
						  					@endif

						  					@if ($file["is_dir"])
						  						<img src="{{url('assets/img/folder.png')}}" style="width:100%" alt="">
						  					@endif
						  					<?php 
						  						if (isset($file['image_preview'])) { $type = 'image'; ?>
						  							<img src="{{$file['image_preview']}}" style="width:100%;border: 1px solid #f0f0f0;" alt="">
						  						<?php }
						  					 ?>
						  					 <?php 
						  						if (isset($file['video_preview'])) { $type = 'video'; ?>
						  							<img src="{{$file['video_preview']}}" style="width:100%;border: 1px solid #f0f0f0;" alt="">
						  						<?php }
						  					 ?>

											@if (isset($file['image_preview']) || isset($file['video_preview']))							  			
							  					 <div class="hover-options">						  					 	
						  					 		<div class="icons">
						  					 			<span onclick="postContent('{{$file['path']}}', '{{$type}}')" class="fa-stack fa-lg" data-toggle="tooltip" data-placement="bottom" title="Post to Facebook">
														  <i class="fa fa-circle fa-stack-2x"></i>
														  <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
														</span>
														<?php $path = $file['path']; ?>
						  					 			<a href="{{url('dropbox/view-content?path='.$path)}}" target="_blank"><span class="fa-stack fa-lg"  data-toggle="tooltip" data-placement="bottom" title="View {{$type}}">
														  <i class="fa fa-circle fa-stack-2x"></i>
														  <i class="fa fa-external-link fa-stack-1x fa-inverse"></i>
														</span>
														</a>
						  					 		</div>
							  					 </div>
						  					@endif
						  				</div>
						  				<div class="file_name">
						  					<?php 
						  						$path = explode('/', $file["path"]);
						  						$file_name = end($path);
						  						if (strlen($file_name) > 15) {
						  							$file_name = substr($file_name,0,15).'...';
						  						}
						  					 ?>						  					 
						  					<span>{{$file_name}}</span>
						  					<div class="size">
						  						@if (isset($file['image_preview']) || isset($file['video_preview']))
						  						<span>{{$file["size"]}}</span>
						  						@endif
						  					</div>
						  				</div>
						  				
						  			</div>
					  			</a>
					  		</div>
					  		@endforeach				  		
					  	</div>
				  	@endforeach
				  </div>	
			</div>
		</div>
	</div>
	<script>
	jQuery(document).ready(function($) {
		 $('[data-toggle="tooltip"]').tooltip();
	});

	/* Start of posting to facebook*/
	function postContent(path, type){
		console.log(path);
		var options = <?php 
			$output = '';
			foreach ($ours as $our) {
				$output .= "<option value='".$our->id."'>".$our->name."</option>";
			}
			echo '"'.$output.'";';
		?>

		swal({   
				title: "Select Page to Post",
				text: "<select id='choose_page' class='form-control'>"+options+"</select><br>Schedule Post <span style='font-size:12px'>Leave blank if you dont want to schedule it</span><input type='text' class='form-control' id='datepicker' style='display:block;' />Enter/Edit Status Message<br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px'></textarea>",
				showCancelButton: true,
				confirmButtonColor: "#0066CC",
				confirmButtonText: "OK, Post it",
				cancelButtonText: "Cancel",
				closeOnConfirm: false,
				html: true },
				function(){
					var choose_page = $(".wraper").attr('data-choose-page');
					var status = $("#status_message").val();
					var schedule_time = $("#datepicker").val();

					swal({   title: "Please Wait  Posting",   imageUrl: "{{url('assets/img/loading.gif')}}" });
					$(".sa-button-container").css('display', 'none');	
					if(type == 'video'){
						setTimeout(function(){
							$.ajax({
								url: '{{url("dropbox/content")}}',
								type: 'POST',
								data: {page_id: choose_page , path:path, type: type, status : status, schedule_time:schedule_time},
								success : function(res){
									console.log(res);
								}
							});						
							$(".sa-button-container").css('display', 'block');
							swal("Request Submitted", "Video will be uploaded in few minutes based on size :)", "success");
							swal({
									title : "Request Submitted",
									type: "success",
									text : "Video will be uploaded in few minutes based on size :)",
									confirmButtonColor: "#FF7D7D",
									confirmButtonText: "Close",
									html: true
								});
						});				
					}		
					else{				
						$.ajax({
							url: '{{url("dropbox/content")}}',
							type: 'POST',
							data: {page_id: choose_page , path:path, type: type, status : status, schedule_time:schedule_time},
							success : function(res){
								console.log(res);													
									$(".sa-button-container").css('display', 'block');
									// swal("Great Posted :)", "Post Successfully Posted, <a href='#'>View Here</a>", "success");
									swal({
										title : "Great Posted :)",
										type: "success",
										text : "Post Successfully Posted",
										confirmButtonColor: "#FF7D7D",
										confirmButtonText: "Close",
										html: true
									});						
							}
						});
					}				
				}
			);

		$("#choose_page").change(function(event) {
			var value = $(this).val();
			$('.wraper').attr('data-choose-page', value);
		});

		var d1 = new Date (),
    		d2 = new Date ( d1 );
    		d3 = new Date ( d1 );
    	d2.setMinutes(d1.getMinutes()+15);
    	d3.setMonth(d1.getMonth()+6);

		$( "#datepicker" ).datetimepicker({
			timeFormat: 'HH:mm:z',
			dateFormat: 'yy-m-dd', 
			minDate: d2,
			maxDate: d3
		});	
	}

	/*Set choose page*/
	jQuery(document).ready(function($) {
		setChoosePage();
	});

	function selectCallback(value, index) {
	    $('.wraper').attr('data-choose-page', value);
	}

	function setChoosePage(){
		<?php $our = $ours[0]; ?>
		$(".wraper").attr('data-choose-page', '{{$our["id"]}}');
	}
	</script>
	<script src="{{url('assets/js/jquery.fs.selecter.js')}}"></script>
	<style>
		.selecter{
			max-width: 100%;
		}
		.selecter .selecter-selected:after{
			top: 0px;
		}
	</style>
@stop
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
	                  	<li><a href="{{url('dropbox?path=/'.$init_path)}}">{{$path_element}}</a></li>
	                  @endforeach
	                </ol>
	              </div>					

				  <div class="col-lg-12">
			  		<div class="alert alert-danger alert-dismissable">
		              <strong>Oops!</strong> Dropbox API key is wrong. Please to go setting <a style="color:black; font-weight:bold" href="{{url('settings/apps-settings')}}">Here</a> and update correct details.
		            </div>						
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
				text: "Select one of your pages to post<br><select id='choose_page'>"+options+"</select><br>Enter/Edit Status Message<br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px' placeholder='Enter Status message here'></textarea>",
				showCancelButton: true,
				confirmButtonColor: "#0066CC",
				confirmButtonText: "OK, Post it",
				cancelButtonText: "Cancel",
				closeOnConfirm: false,
				html: true },
				function(){
					var choose_page = $(".wraper").attr('data-choose-page');
					var status = $("#status_message").val();
					swal({   title: "Please Wait  Posting",   imageUrl: "{{url('assets/img/loading.gif')}}" });
					$(".sa-button-container").css('display', 'none');	
					if(type == 'video'){
						setTimeout(function(){
							$.ajax({
								url: '{{url("dropbox/content")}}',
								type: 'POST',
								data: {page_id: choose_page , path:path, type: type, status : status},
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
							data: {page_id: choose_page , path:path, type: type, status : status},
							success : function(res){
								console.log(res);													
									$(".sa-button-container").css('display', 'block');
									// swal("Great Posted :)", "Post Successfully Posted, <a href='#'>View Here</a>", "success");
									swal({
										title : "Great Posted :)",
										type: "success",
										text : "Post Successfully Posted, <a href='http://facebook.com/"+res+"' target='_blank'>View Here</a>",
										confirmButtonColor: "#FF7D7D",
										confirmButtonText: "Close",
										html: true
									});						
							}
						});
					}				
				}
			);

		$("#choose_page").selecter({
			callback: selectCallback
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
		<?php if (isset($ours[0])){
		$our = $ours[0];
		?>
		$(".wraper").attr('data-choose-page', '{{$our["id"]}}');
		<?php } ?>
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
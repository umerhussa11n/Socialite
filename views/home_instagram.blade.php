@extends('app')

@section('title', 'Dashboard')

@section('css')
    <style>
        .fb-post {
            margin-bottom: 10px;
        }

        #likes-count {
            height: 28px;
        }
    </style>
@endsection

@section('main_content')
    <div class="box box-block bg-white">
        <div class="form-group row">
            <label for="type-selecter" class="col-md-2 control-label">Pages </label>
            {{--<div class="col-md-2">--}}
            {{--<select data-plugin="select2" class="form-control" id="type-selecter">--}}
            {{--<option value="posts">All</option>--}}
            {{--<option value="photos">Photos</option>--}}
            {{--<option value="videos">Videos</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            <div class="col-md-2">
                <select data-plugin="select2" class="form-control" id="page-selecter">
                    @foreach ($of['follows'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <?php
        $operator = '';
        $like_count = '';

        if (isset($_GET['opt']) && trim($_GET['opt']) !== '') {
            $opts = explode('_', trim($_GET['opt']));

            if (in_array($opts[0], array('<', '>')) && $opts[1] >= 0) {
                $operator = $opts[0];
                $like_count = $opts[1];
            }
        }
        ?>

        <div class="form-group row">
            <label for="type-selecter" class="col-md-2 control-label">Likes </label>
            <div class="col-md-2">
                <select data-plugin="select2" class="form-control" name="likes-count-operator" id="likes-count-operator">
                    <option value=">" {{ ($operator == '>') ? 'selected' : '' }}>Greater Than</option>
                    <option value="<" {{ ($operator == '<') ? 'selected' : '' }}>Less than</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="likes-count" class="form-control input-sm" id="likes-count"
                       value="{{ $like_count }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="offset-md-2 col-md-10">
                <button id="filter-likes" class="btn btn-primary">
                    Filter
                </button>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <div class="pull-right">
                    Page <b id="page_count">1</b> Results &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-md btn-info" id="button_prev" disabled="disabled"><i class="fa fa-angle-left"></i> Prev </button> &nbsp;
                    <button class="btn btn-md btn-info" id="button_next" onclick="@if($max_id)next_page('{{ $max_id }}');@endif">Next <i class="fa fa-angle-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-block bg-white">
	        <div class="row" id="ajaxContent">	        		        	
	        	@if(empty($partition))
					<div class="col-lg-12">					
						<div class="alert alert-warning">
			              <strong>Something Wrong!</strong> Looks like this page is not published or No Available Posts.
			            </div>
					</div>
	        	@else 
					@foreach ($partition as $posts)
		        	        		
						@foreach ($posts as $post)
						<div class="post-holder">
							<div class="fb-post"><?php echo $post['embed'] ?></div>
							<div class="on-top">
								<div class="buttons-holder">
									<div class="post-details">
										Post type : <strong> &nbsp; <i class="fa fa-external-link"></i> &nbsp; {{ ucfirst($post['type']) }}</strong>
									</div>
									<a href="{{ $post['link'] }}" target="_blank"><button class="btn btn-block btn-primary"><strong><i class="fa fa-external-link"></i></strong> &nbsp;View Post </button></a>  		
									<?php
                                                                        if($post['type'] == 'video')
                                                                        {
                                                                            ?>
                                                                        <button class="btn btn-block btn-primary" data-post-id="{{ $post['id'] }}" onclick="post_content_video('{{ $post['id'] }}','{{ $post['type'] }}')" data-status-message="{{ $post['text'] }}" style="margin-top:4px"><strong><i class="fa fa-external-link"></i></strong> &nbsp;Post {{ ucfirst($post['type']) }}</button>			   
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                        <button class="btn btn-block btn-primary" data-post-id="{{ $post['id'] }}" onclick="post_content('{{ $post['id'] }}','{{ $post['type'] }}')" data-status-message="{{ $post['text'] }}" style="margin-top:4px"><strong><i class="fa fa-external-link"></i></strong> &nbsp;Post {{ ucfirst($post['type']) }}</button>			   
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        
								</div>
							</div>   		
						</div>
						@endforeach
					
					@endforeach
	        	@endif	        	
	        </div>
    </div>

@endsection

@section('js')
    
	<script>
		$(document).on('mouseover', '.fb-post', function(event) {
			$(this).addClass('hover');
		});

		$(document).on('mouseout', '.fb-post', function(event) {
			$(this).removeClass('hover');
		});
	</script>

	<script>
	    $(document).ready(function($) {
            $("#page-selecter").val('{{ $page_id }}').trigger('change');

	        $("#page-selecter").on('change', function(event) {
				
	        	var page_id = $(this).val();
				
	        	var platform = 'facebook';
				
				if (page_id.indexOf('t') > -1)
				{
					platform = 'twitter';
				} else if (page_id.indexOf('i') > -1)
				{
					platform = 'instagram';
				}
				
	        	window.location = '/' + platform + '/' +page_id;
	        });

			$('#filter-likes').click(function() {
				var likes_opt =  $('#likes-count-operator').val();
				var likes_value =  parseInt($('#likes-count').val().trim(), 10);
				if (likes_opt !== '' && likes_value !== '' && likes_value >= 0)
				{
					window.location = window.location.href.split('?')[0] + '?opt=' + likes_opt + '_' + likes_value;
				} else
				{
					alert('Invalid value!');
				}
			});
	    });    
	</script>
	<script>
	$(document).ready(function($) {
		$(document).on('mouseover', '.post-holder', function(event) {
			var height = $(this).find('.fb-post').addClass('blur').height();
			$(this).find('.on-top').height(height).show();			
		});

		$(document).on('mouseout', '.post-holder', function(event) {			
			$(this).find('.fb-post').removeClass('blur');
			$(this).find('.on-top').hide();
		});	
	});



		function doOnChange()
        {
            var choose_page = $('#choose_page').val();                    
            $("#e_span").remove();
            if(choose_page.indexOf('i') > -1 && type_x == 'video')
            {
                $("#status_message").after("<span id='e_span' style='color: red; font-size: 11px; font-style: italic;'>Please note that Instagram will not Post video longer than 60 secs.</span>");


                    setTimeout(function(){ $("#e_span").fadeOut('medium', function(){$("#e_span").remove();}); }, 3000);
                    return;
            }
        }
        
        var type_x = '';

        function post_content(post_id, type, status) {
            
            var status = $("button[data-post-id='" + post_id + "']").attr('data-status-message');
            
            
            type_x = type;

            var options = <?php
			$output = '';
			foreach ($libraries as $lib) {
                $output .= "<option value='".$lib->id."'>".$lib->title."</option>";
            }
			echo '"'.$output.'";';
		?>


		swal({
                    title: "Select Library to Save",
                    text: "<select onchange='doOnChange();' id='choose_page' data-plugin='select2' class='form-control'>" + options + "</select><br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px'>" + status + "</textarea>",
                    showCancelButton: true,
                    confirmButtonColor: "#0066CC",
                    confirmButtonText: "Save",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    html: true
                },
                function () {
                    var choose_page = $('#choose_page').val();
                    
                    // $("#e_span").remove();
                    // if(choose_page.indexOf('t') > -1)
                    // {
                    //     if($("#status_message").val().length > 140)
                    //     {
                    //         $("#status_message").after("<span id='e_span' style='color: red; font-size: 11px; font-style: italic;'>You cannot post text larger than 140 characters in Twitter. Please fix !</span>");


                    //         setTimeout(function(){ $("#e_span").fadeOut('medium', function(){$("#e_span").remove();}); }, 3000);
                    //         return;
                    //     }
                    // }
                    
                    
                    
                    //nimesh
                    // if (type != 'status' && type != 'video' && type != 'link' && choose_page.indexOf('t') > -1 && type !== 'photo') {
                    //     $(".sa-button-container").css('display', 'block');
                    //     swal({
                    //         title: "Twitter",
                    //         type: "error",
                    //         text: "Twitter doesn't support " + type + " post.",
                    //         confirmButtonColor: "#FF7D7D",
                    //         confirmButtonText: "Close"
                    //     });
                    //     return false;
                    // }
                    // if (type != 'video' && type != 'link' && choose_page.indexOf('i') > -1 && type !== 'photo') {
                    //     $(".sa-button-container").css('display', 'block');
                    //     swal({
                    //         title: "Instagram",
                    //         type: "error",
                    //         text: "Instagram doesn't support " + type + " post.",
                    //         confirmButtonColor: "#FF7D7D",
                    //         confirmButtonText: "Close"
                    //     });
                    //     return false;
                    // }

                    var url = '{{url("recordpost")}}';
                    var status = $("#status_message").val();
                    var schedule_time = $("#datepicker").val();

                    $(".preloader").show();
                    swal({title: "Please Wait Posting", imageUrl: "{{url('assets/img/loading.gif')}}"});

                    $(".sa-button-container").hide();

                    if (false && ttype == 'video' && choose_page.indexOf('f') > -1) {

                        setTimeout(function () {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    page_id: choose_page,
                                    post_id: post_id,
                                    type: type,
                                    status: status,
                                    schedule_time: schedule_time
                                }
                            });

                            $(".sa-button-container").show();
                            $(".preloader").hide();

                            swal({
                                title: "Request Submitted",
                                type: "success",
                                text: "Video will be uploaded in few minutes based on size :)",
                                confirmButtonColor: "#FF7D7D",
                                confirmButtonText: "Close"
                            });
                        });
                    }
                    else {
                        $(".preloader").show();
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                library_id: choose_page,
                                social_media: 'i',
                                post_id: post_id,
                                type: type,
                                status: status,
                                schedule_time: schedule_time,
                                cut_video_url: cut_video_url
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $(".preloader").hide();
                                if (jqXHR.status == 500) {
                                    $(".sa-button-container").css('display', 'block');
                                    swal({
                                        title: "Internal Error",
                                        text: "Happens when connection is bad or fb server is down/timedout",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Retry!",
                                        closeOnConfirm: false
                                    }, function () {
                                        console.log(post_id);
                                        retry_posting(choose_page, post_id, type, status, schedule_time);
                                    });
                                    
                                }
                            },
                            success: function (res) {
                                console.log(res);
                                $(".sa-button-container").css('display', 'block');
                                if (res == 'schedule_time') {
                                    swal("Error", "You have to set your timezone in app settings", "error");
                                    return false;
                                } else if (res.indexOf('error|') > -1) {
                                    var error = res.split('|');
                                    swal({
                                        title: "Error",
                                        type: "error",
                                        text: error[1],
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close"
                                    });
                                    $(".preloader").hide();
                                } else {
                                    swal({
                                        title: "Great Posted :)",
                                        type: "success",
                                        text: "Post Successfully Posted",
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close",
                                        cancelButtonText: "Close"
                                    });
                                    $(".sa-confirm-button-container").hide();
                                    $(".sa-button-container .cancel").show();
                                    $(".confirm.cut_and_save").hide();
                                    $(".preloader").hide();
                                }
                            }
                        });
                    }
                }
            );


$('.confirm.cut_and_save').hide();
$(".sa-confirm-button-container").show();
    

                    // if(type == 'video')
                    // {
                    //     //$('#choose_page').find('option[value^=i-]').hide();
                    //     $('#choose_page').find('option[value^=t-]').hide();
                    // }
                    // else
                    // {
                    //     $('#choose_page').find('option').show();
                    // }

            var d1 = new Date(),
                d2 = new Date(d1);
            d3 = new Date(d1);
            d2.setMinutes(d1.getMinutes() + 15);
            d3.setMonth(d1.getMonth() + 6);

            $("#datepicker").datetimepicker({
                timeFormat: 'HH:mm:z',
                dateFormat: 'yy-m-dd',
                minDate: d2,
                maxDate: d3
            });

            //$('.swal2-content [data-plugin="select2"]').select2($(this).attr('data-options'));
        }


	/*
	* Posting links
	 */
	// Post link starts here
	function post_content_old(post_id, type){
		var status = $("button[data-post-id='"+post_id+"']").attr('data-status-message');

		var options = <?php 
			$output = '';
			foreach ($of['pages'] as $id => $name) {
				$output .= "<option value='".$id."'>".$name."</option>";
			}
			echo '"'.$output.'";';
		?>

		swal({   
			title: "Select Page/Account to Post",
			text: "<select id='choose_page' class='form-control'>"+options+"</select><br>Schedule Post <span style='font-size:12px'>Leave blank if you dont want to schedule it</span><input type='text' class='form-control' id='datepicker' style='display:block;' />Enter/Edit Status Message<br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px'>"+status+"</textarea>",
			showCancelButton: true,
			confirmButtonColor: "#0066CC",
			confirmButtonText: "OK, Post it",
			closeOnConfirm: false,
			html: true },
			function(){				
				var choose_page = $('#choose_page').val();
				if (choose_page.indexOf('i') > -1 && type !== 'photo') {
					$(".sa-button-container").css('display', 'block');
					swal({
						title : "Instagram",
						type: "error",
						text : "Instagram doesn't support " + type + " post.",
						confirmButtonColor: "#FF7D7D",
						confirmButtonText: "Close",
						html: true
					});
					return false;
				}

				var url = '{{url("post-from-instagram")}}';
				var status = $("#status_message").val();
				var schedule_time = $("#datepicker").val();
				
				swal({   title: "Please Wait  Posting",   imageUrl: "{{url('assets/img/loading.gif')}}" });
				$(".sa-button-container").hide();
                $(".preloader").show();
				if(type == 'video' && choose_page.indexOf('f') > -1){
					
					setTimeout(function(){
						$.ajax({
							url: url,
							type: 'POST',
							data: {page_id: choose_page , post_id:post_id, type: type, status:status, schedule_time:schedule_time}
						});						
						$(".sa-button-container").show();
                        $(".preloader").hide();
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
                    $(".preloader").show();
					$.ajax({
						url: url,
						type: 'POST',
						data: {page_id: choose_page , post_id:post_id, type: type, status:status, schedule_time:schedule_time},
						error : function(jqXHR, textStatus, errorThrown){
                            $(".preloader").hide();
						  if (jqXHR.status == 500){
						  	$(".sa-button-container").css('display', 'block');
						  	swal({
							   title: "Internal Error",
							   text: "Happens when connection is bad or fb server is down/timedout",
							   type: "warning",
							   showCancelButton: true,
							   confirmButtonColor: "#DD6B55",
							   confirmButtonText: "Retry!",
							   closeOnConfirm: false }, function(){
							   		console.log(post_id);
							   		retry_posting(choose_page, post_id, type, status, schedule_time);
							   });
						  } 
						},
						success : function(res){
                            $(".preloader").hide();
							console.log(res);													
								$(".sa-button-container").css('display', 'block');
								if(res == 'schedule_time'){
									swal("Error", "You have to set your timezone in app settings", "error");
									return false;
								} else if(res.indexOf('error|') > -1)
								{
									var error = res.split('|');
									swal({
										title : "Error",
										type: "error",
										text : error[1],
										confirmButtonColor: "#FF7D7D",
										confirmButtonText: "Close",
										html: true
									});
								} else {
									swal({
										title : "Great Posted :)",
										type: "success",
										text : "Post Successfully Posted",
										confirmButtonColor: "#FF7D7D",
										confirmButtonText: "Close"
									});
                                    $(".sa-confirm-button-container").hide();
                                    $(".sa-button-container .cancel").show();
                                    $(".confirm.cut_and_save").hide();
								}
						}
					});
				}				
			}
		);
		
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

	function prev_page(max_id){
		$("#button_next, #button_prev").attr('disabled','true');
        $(".preloader").show();
		
		$.ajax({
			url: '{{url("pagination-instagram") . ((isset($_GET['opt']) && trim($_GET['opt']) !== '' ) ? ('?opt=' . $_GET['opt']) : '')}}',
			type: 'POST',
			data: {d: 'p', max_id: max_id, page_id: '{{ $page_id }}'},
			dataType: "json",
			success: function(res){
                $(".preloader").hide();
				if (res.hasOwnProperty('html')){
					$("#ajaxContent").html(res.html);
					var done = instgrm.Embeds.process();
					
					if (typeof done == 'undefined')
					{
						$('div.loading').remove();
					}
					
					$("#page_count").html(res.page);
					$("#button_next").attr('onclick', res.next);
					$("#button_prev").attr('onclick', res.prev);
					
					if (res.next !== '')
					{
						$("#button_next").removeAttr('disabled');
					}
					
					if (res.prev !== '')
					{
						$("#button_prev").removeAttr('disabled');
					}
				} else {
					$(".sa-button-container").css('display', 'block');
					swal({
						   title: "Warning",
						   text: "No data found",
						   type: "warning",
						   confirmButtonColor: "#DD6B55",
						   confirmButtonText: "Retry!",
						   confirmButtonText: "Close"
						}
					);
				}
			}
		});		
	}





var xx_max_id = '{{$max_id}}';
var xx_page_id = '{{ $page_id }}';
function loadData() {
    isLoading = true;
    $(".preloader").show();
        $.ajax({
          url: '{{url("pagination-instagram") . ((isset($_GET['opt']) && trim($_GET['opt']) !== '' ) ? ('?opt=' . $_GET['opt']) : '')}}',
        type: 'POST',
                data: {d: 'n', max_id: xx_max_id, page_id: '{{ $page_id }}'},
                dataType: "json",
                success: function(res){
                    
                    isLoading = false;
                    if (res.hasOwnProperty('html')){
                            //xx_max_id = res[1];
                            onLoadData(res.html);
                            var done = instgrm.Embeds.process();
                    
                            if (typeof done == 'undefined')
                            {
                                $('div.loading').remove();
                            }
                            xx_max_id = res.max_id;
                            xx_page_id = res.page;
                    }
                }
        });
      };
      




	function next_page(max_id){
		$("#button_next, #button_prev").attr('disabled','true');
        $(".preloader").show();

		$.ajax({
			url: '{{url("pagination-instagram") . ((isset($_GET['opt']) && trim($_GET['opt']) !== '' ) ? ('?opt=' . $_GET['opt']) : '')}}',
			type: 'POST',
			data: {d: 'n', max_id: max_id, page_id: '{{ $page_id }}'},
			dataType: "json",
			success: function(res){
                $(".preloader").hide();
				if (res.hasOwnProperty('html')){
					$("#ajaxContent").html(res.html);
					var done = instgrm.Embeds.process();
					
					if (typeof done == 'undefined')
					{
						$('div.loading').remove();
					}

					$("#page_count").html(res.page);
					
					$("#button_next").attr('onclick', res.next);
					$("#button_prev").attr('onclick', res.prev);
					
					if (res.next !== '')
					{
						$("#button_next").removeAttr('disabled');
					}
					
					if (res.prev !== '')
					{
						$("#button_prev").removeAttr('disabled');
					}
				} else {
					$(".sa-button-container").css('display', 'block');
					swal({
						   title: "Warning",
						   text: "No data found",
						   type: "warning",
						   confirmButtonColor: "#DD6B55",
						   confirmButtonText: "Retry!",
						   confirmButtonText: "Close"
						}
					);
				}
			}
		});		
	}

	function retry_posting(choose_page, post_id, type, status, schedule_time){
        $(".preloader").show();
		$.ajax({
			url: '{{url("post-from-instagram")}}',
			type: 'POST',
			data: {page_id: choose_page , post_id:post_id, type: type, status:status, schedule_time:schedule_time},
			error : function(jqXHR, textStatus, errorThrown){
                $(".preloader").hide();
			  if (jqXHR.status == 500){
			  	$(".sa-button-container").css('display', 'block');
			  	swal({
				   title: "Internal Error",
				   text: "Happens when connection is bad or fb server is down/timedout",
				   type: "warning",
				   showCancelButton: true,
				   confirmButtonColor: "#DD6B55",
				   confirmButtonText: "Retry!",
				   closeOnConfirm: false }, function(){
				   		console.log(post_id);
				   		retry_posting(choose_page, post_id, type, status, schedule_time);
				   });
			  } 
			},
			success : function(res){
                $(".preloader").hide();
				console.log(res);													
					$(".sa-button-container").css('display', 'block');
					if(res == 'schedule_time'){
						swal("Error", "You have to set your timezone in app settings", "error");
						return false;
					} else if(res.indexOf('error|') > -1)
					{
						var error = res.split('|');
						swal({
							title : "Error",
							type: "error",
							text : error[1],
							confirmButtonColor: "#FF7D7D",
							confirmButtonText: "Close",
							html: true
						});
					} else
					{
						swal({
							title : "Great Posted :)",
							type: "success",
							text : "Post Successfully Posted",
							confirmButtonColor: "#FF7D7D",
							confirmButtonText: "Close"
						});		
                        $(".sa-confirm-button-container").hide();
                        $(".sa-button-container .cancel").show();
                        $(".confirm.cut_and_save").hide();
					}					
			}
		});
	}
	</script>
	<script async defer src="//platform.instagram.com/en_US/embeds.js"></script>
@stop


 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link href="https://vjs.zencdn.net/6.2.7/video-js.css" rel="stylesheet">

  <!-- If you'd like to support IE8 -->
  <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
  
  
   

  <script src="{{ asset('/public/video/video.js') }}"></script>
  <script src="{{ asset('/public/video/video-offset.js') }}"></script>
  
  
  <script type="text/javascript">

  $(document).ready(function(){

          //console.log('registering......');
          $(document).on('click', '.cutter-palette.cancel_button', function(event){
                try
                {
                          //console.log("pausing.....");
                          myPlayer.pause();
                }
                catch(e){
                  console.log(e);
                }

                $('.stop-scrolling').removeClass('stop-scrolling');
            });

});

$(document).ready(function(){
      $(document).bind('scroll', onScroll);
  });

  
  var g_choose_page, g_status, g_schedule_time;
  function initiateVideoPosting(post_id, video_len_x)
  {
      //console.log('dddd');
      var url = '{{url("recordpost")}}';
      //$(".preloader").show();
      //swal({title: "Please Wait Posting", imageUrl: "{{url('assets/img/loading.gif')}}"});

      $(".sa-confirm-button-container").show();

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                library_id: g_choose_page,
                                social_media: 'i',
                                post_id: post_id,
                                type: 'video',
                                status: g_status,
                                schedule_time: g_schedule_time,
                                cut_video_url: cut_video_url,
                                video_len_x: video_len_x
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $(".preloader").hide();
                                if (jqXHR.status == 500) {
                                    $(".sa-button-container").css('display', 'block');
                                    swal({
                                        title: "Internal Error",
                                        text: "Happens when connection is bad or fb server is down/timedout",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Retry!",
                                        closeOnConfirm: false
                                    }, function () {
                                        console.log(post_id);
                                        retry_posting(g_choose_page, post_id, 'video', g_status, g_schedule_time);
                                    });
                                    
                                }
                            },
                            success: function (res) {
                                $(".preloader").hide();
                                //console.log(res);
                                $(".sa-confirm-button-container").hide();
                                if (res == 'schedule_time') {
                                    swal("Error", "You have to set your timezone in app settings", "error");
                                    return false;
                                } else if (res.indexOf('error|') > -1) {
                                    var error = res.split('|');
                                    swal({
                                        title: "Error",
                                        type: "error",
                                        text: error[1],
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close"
                                    });
                                    $(".preloader").hide();
                                } else {
                                    swal({
                                        title: "Great Posted :)",
                                        type: "success",
                                        text: "Post Successfully Posted",
                                        confirmButtonColor: "#FF7D7D",
                                        cancelButtonText: "Close",
                                        showCancelButton: false
                                    });
                                    $(".sa-confirm-button-container").hide();
                                    $(".sa-button-container .cancel").show();
                                    $(".confirm.cut_and_save").hide();
                                    $(".preloader").hide();
                                }
                            }
                        });
  }
  
  function showCutter(idx, type)
  {
      v_idx = idx;
      g_choose_page = $('#choose_page').val();  
      g_status = $("#status_message").val();
     $('.sweet-alert.showSweetAlert.visible').hide();
      
      $('.preloader').hide();
      $('#fetch_notice').show();
      $('.cutter-palette').hide();
      $('#link_msg').html('');
      
      $('#mainx').show();
      adjustLayout();
      
      $.ajax({
           dataType: 'json',
                method: "POST",
                url: "{{url("getinstagramvideo")}}",
                data: { v_id: idx }
              })
                .done(function( msg ) {
                    
                    
                    duration = (Number(msg.mins) * 60) + Number(msg.secs);
                    
                    current_v_url = msg.video_url;
                  //alert(msg);
          $('#my-video source').attr('src', current_v_url);
          $("#my-video video")[0].load();
          
          $('#from_').html(0);
          $('#to_').html(duration);
          $('#total_').html(duration);
          
          m_slider.slider("option", "max", duration);
          m_slider.slider("option", "values", [0, duration]);
          
          checkForError(duration);
          
                    $('.cutter-palette').show();
                    $('#fetch_notice').hide();
                });
  }
  
  var m_slider;
  var current_v_url;
  var v_idx;
  var max_video_time_limit = 0;
  var social_media = "";
  
  
  function post_content_video(idx, type)
  {
    cut_video_url = '';
      post_id = idx;
    var status = $("button[data-post-id='" + post_id + "']").attr('data-status-message');
    type_x = type;
    var options = <?php
                $output = '';
                foreach ($libraries as $lib) {
                    $output .= "<option value='".$lib->id."'>".$lib->title."</option>";
                }
                echo '"'.$output.'";';
        ?>
            $(".sa-confirm-button-container").show();
            
            
        swal({
            title: "Select Library to Save",
            text: "<select onchange='doOnChange();' id='choose_page' data-plugin='select2' class='form-control'>" + options + "</select><br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px'>" + status + "</textarea>",        
            showCancelButton: true,
            confirmButtonColor: "#0066CC",
            confirmButtonText: "Save",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            html: true,
        },
        function () {
            
            var choose_page = $('#choose_page').val();                    
            // $("#e_span").remove();
            // if(choose_page.indexOf('t') > -1)
            // {
            //     if($("#status_message").val().length > 140)
            //     {
            //         $("#status_message").after("<span id='e_span' style='color: red; font-size: 11px; font-style: italic;'>You cannot post text larger than 140 characters in Twitter. Please fix !</span>");

            //         setTimeout(function(){ $("#e_span").fadeOut('medium', function(){$("#e_span").remove();}); }, 3000);
            //         return;
            //     }
            // }
            
            var url = '{{url("recordpost")}}';
            var status = $("#status_message").val();
            var schedule_time = $("#datepicker").val();

            $(".preloader").show();
            //$(".sa-button-container").hide();

            if (false && type == 'video' && choose_page.indexOf('f') > -1) {

                setTimeout(function () {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            page_id: choose_page,
                            post_id: post_id,
                            type: type,
                            status: status,
                            schedule_time: schedule_time,
                            cut_video_url: cut_video_url
                        }
                    });

                   // $(".sa-button-container").show();
                    $(".preloader").hide();

                    swal({
                        title: "Request Submitted",
                        type: "success",
                        text: "Video will be uploaded in few minutes based on size :)",
                        confirmButtonColor: "#FF7D7D",
                        confirmButtonText: "Close"
                    });
                });
            }
            else
            {

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                library_id: choose_page,
                                social_media: 'i',
                                post_id: post_id,
                                type: type,
                                status: status,
                                schedule_time: schedule_time,
                                cut_video_url: cut_video_url
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $(".preloader").hide();
                                if (jqXHR.status == 500) {
                                    $(".sa-button-container").css('display', 'block');
                                    swal({
                                        title: "Internal Error",
                                        text: "Happens when connection is bad or fb server is down/timedout",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Retry!",
                                        closeOnConfirm: false
                                    }, function () {
                                        console.log(post_id);
                                        retry_posting(choose_page, post_id, type, status, schedule_time);
                                    });
                                    
                                }
                            },
                            success: function (res) {
                                $(".preloader").hide();
                                //console.log(res);
                                $(".sa-confirm-button-container").hide();
                                if (res == 'schedule_time') {
                                    swal("Error", "You have to set your timezone in app settings", "error");
                                    return false;
                                } else if (res.indexOf('error|') > -1) {
                                    var error = res.split('|');
                                    swal({
                                        title: "Error",
                                        type: "error",
                                        text: error[1],
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close"
                                    });
                                    $(".preloader").hide();
                                } else {
                                    swal({
                                        title: "Great Posted :)",
                                        type: "success",
                                        text: "Post Successfully Posted",
                                        confirmButtonColor: "#FF7D7D",
                                        cancelButtonText: "Close",
                                        showCancelButton: false
                                    });
                                    $(".sa-confirm-button-container").hide();
                                    $(".sa-button-container .cancel").show();
                                    $(".confirm.cut_and_save").hide();
                                    $(".preloader").hide();
                                }
                            }
                        });
                /*
                g_choose_page = choose_page;
                g_status = status;
                g_schedule_time = schedule_time;
                
                if(type == 'video' && choose_page.indexOf('t') > -1)
                {
                    social_media = "Twitter";
                    max_video_time_limit = 30;
                }
                else
                if(type == 'video' && choose_page.indexOf('i') > -1)
                {
                    social_media = "Instagram";
                    max_video_time_limit = 60;
                }
                
                //console.log("max_video_time_limit : "+max_video_time_limit);
                showCutter(idx, type);
                */
            }
                    
        }
    );

g_choose_page = choose_page;
g_status = status;
g_schedule_time = '';

max_video_time_limit = 30;
social_media = "Twitter";


    if($('.sa-button-container button.confirm').html() == 'Save')
    {
        $('.sa-button-container button.cut_and_save').remove();
        $('.sa-button-container button.cancel').after('<button class="confirm cut_and_save" onclick="showCutter(\''+idx+'\', \'video\');" tabindex="3" style="display: inline-block;">Save</button>');
    
        $('.sa-button-container .sa-confirm-button-container button.confirm').hide();
    }  
    else
    {
        $('.sa-button-container button.cut_and_save').remove();
    }

  }
  
  function post_content_video_xxxxxxx(idx, type)
  {
    cut_video_url = '';
      post_id = idx;
    var status = $("button[data-post-id='" + post_id + "']").attr('data-status-message');
    type_x = type;
    var options = <?php
                $output = '';
                foreach ($libraries as $lib) {
                    $output .= "<option value='".$lib->id."'>".$lib->title."</option>";
                }
                echo '"'.$output.'";';
        ?>
        swal({
            title: "Select Library to Save",
            text: "<select onchange='doOnChange();' id='choose_page' data-plugin='select2' class='form-control'>" + options + "</select><br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px'>" + status + "</textarea>",        
            showCancelButton: true,
            confirmButtonColor: "#0066CC",
            confirmButtonText: "Save",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            html: true,
        },
        function () {
            
            var choose_page = $('#choose_page').val();                    
            // $("#e_span").remove();
            // if(choose_page.indexOf('t') > -1)
            // {
            //     if($("#status_message").val().length > 140)
            //     {
            //         $("#status_message").after("<span id='e_span' style='color: red; font-size: 11px; font-style: italic;'>You cannot post text larger than 140 characters in Twitter. Please fix !</span>");

            //         setTimeout(function(){ $("#e_span").fadeOut('medium', function(){$("#e_span").remove();}); }, 3000);
            //         return;
            //     }
            // }
            
            var url = '{{url("recordpost")}}';
            var status = $("#status_message").val();
            var schedule_time = $("#datepicker").val();

            $(".preloader").show();
            //$(".sa-button-container").hide();

            if (false && type == 'video' && choose_page.indexOf('f') > -1) {

                setTimeout(function () {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            page_id: choose_page,
                            post_id: post_id,
                            type: type,
                            status: status,
                            schedule_time: schedule_time,
                            cut_video_url: cut_video_url
                        }
                    });

                   // $(".sa-button-container").show();
                    $(".preloader").hide();

                    swal({
                        title: "Request Submitted",
                        type: "success",
                        text: "Video will be uploaded in few minutes based on size :)",
                        confirmButtonColor: "#FF7D7D",
                        confirmButtonText: "Close"
                    });
                });
            }
            else
            {

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                library_id: choose_page,
                                social_media: 'i',
                                post_id: post_id,
                                type: type,
                                status: status,
                                schedule_time: schedule_time,
                                cut_video_url: cut_video_url
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $(".preloader").hide();
                                if (jqXHR.status == 500) {
                                    $(".sa-button-container").css('display', 'block');
                                    swal({
                                        title: "Internal Error",
                                        text: "Happens when connection is bad or fb server is down/timedout",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Retry!",
                                        closeOnConfirm: false
                                    }, function () {
                                        console.log(post_id);
                                        retry_posting(choose_page, post_id, type, status, schedule_time);
                                    });
                                    
                                }
                            },
                            success: function (res) {
                                console.log(res);
                                $(".sa-button-container").css('display', 'block');
                                if (res == 'schedule_time') {
                                    swal("Error", "You have to set your timezone in app settings", "error");
                                    return false;
                                } else if (res.indexOf('error|') > -1) {
                                    var error = res.split('|');
                                    swal({
                                        title: "Error",
                                        type: "error",
                                        text: error[1],
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close"
                                    });
                                    $(".preloader").hide();
                                } else {
                                    swal({
                                        title: "Great Posted :)",
                                        type: "success",
                                        text: "Post Successfully Posted",
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close"
                                    });
                                    $(".sa-confirm-button-container").hide();
                                    $(".sa-button-container .cancel").show();
                                    $(".confirm.cut_and_save").hide();
                                    $(".preloader").hide();
                                }
                            }
                        });
                /*
                g_choose_page = choose_page;
                g_status = status;
                g_schedule_time = schedule_time;
                
                if(type == 'video' && choose_page.indexOf('t') > -1)
                {
                    social_media = "Twitter";
                    max_video_time_limit = 30;
                }
                else
                if(type == 'video' && choose_page.indexOf('i') > -1)
                {
                    social_media = "Instagram";
                    max_video_time_limit = 60;
                }
                
                //console.log("max_video_time_limit : "+max_video_time_limit);
                showCutter(idx, type);
                */
            }
                    
        }
    );

g_choose_page = choose_page;
g_status = status;
g_schedule_time = '';

max_video_time_limit = 30;
social_media = "Twitter(30 secs)";

        $('.sa-button-container button.cut_and_save').remove();
        $('.sa-button-container button.cancel').after('<button class="confirm cut_and_save" onclick="showCutter(\''+idx+'\', \'video\');" tabindex="3" style="display: inline-block;">Cut & Save</button>');
        

  }
  </script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js"></script>
  <style type="text/css">
    .grid-item { width: 320px; }
  </style>
  <script type="text/javascript">
    $('.grid').masonry({
    // options
    itemSelector: '.grid-item',
    columnWidth: 320
  });


     var cutter_base_url = '{{url("ffmpeg/cutter_script.php")}}';
  </script>
  
<?php
$video_url = url("ffmpeg/test.mp4");
include_once(base_path('app/views')."/chunker.php");
?>
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
            <div class="col-md-2">
                <select data-plugin="select2" class="form-control" id="type-selecter">
                    <option value="posts">All</option>
                    <option value="photos">Photos</option>
                    <option value="videos">Videos</option>
                </select>
            </div>
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
                        <button class="btn btn-md btn-info" id="button_prev" disabled="disabled"
                                onclick="prev_page('{{urlencode($previous)}}', '{{$type}}')"><i
                                    class="fa fa-angle-left"></i> Prev
                        </button> &nbsp;
                        <button class="btn btn-md btn-info" id="button_next"
                                onclick="next_page('{{urlencode($next)}}','{{$type}}')">Next <i
                                    class="fa fa-angle-right"></i></button>
                </div>
            </div>
        </div>
    </div>


    <div class="box box-block bg-white">
        <div class="row" id="ajaxContent">
            @if($unpublished)
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        <strong>Something Wrong!</strong> Looks like this page is not published or No Available Posts.
                    </div>
                </div>
            @else
                @if ($type == 'posts')
                    @include ('types.posts')
                @endif

                @if ($type == 'photos')
                    @include ('types.photos')
                @endif

                @if ($type == 'statuses')
                    @include ('types.statuses')
                @endif

                @if ($type == 'videos')
                    @include ('types.videos')
                @endif

                @if ($type == 'links')
                    @include ('types.links')
                @endif
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('mouseover', '.fb-post', function (event) {
            $(this).addClass('hover');
        });

        $(document).on('mouseout', '.fb-post', function (event) {
            $(this).removeClass('hover');
        });
    </script>

    <script>
        $(document).ready(function ($) {
            $("#type-selecter").val('{{ $type }}').trigger('change');
            $("#page-selecter").val('{{ $page_id }}').trigger('change');

            // Page selecter
            $("#page-selecter").on('change', function (event) {
                var type = '';

                var page_id = $(this).val();

                var platform = 'facebook';

                if (page_id.indexOf('t') > -1) {
                    platform = 'twitter';
                } else if (page_id.indexOf('i') > -1) {
                    platform = 'instagram';
                }

                if (platform == 'facebook') {
                    type = '/' + $("#type-selecter").val();
                }

                window.location = '/' + platform + '/' + page_id + type;
            });

            // Type selecter
            $("#type-selecter").on('change', function (event) {
                var type = '';
                var page_id = $("#page-selecter").val();
                var platform = 'facebook';

                if (page_id.indexOf('t') > -1) {
                    platform = 'twitter';
                } else if (page_id.indexOf('i') > -1) {
                    platform = 'instagram';
                }

                if (platform == 'facebook') {
                    type = '/' + $(this).val();
                }

                window.location = '/' + platform + '/' + page_id + type;
            });

            $('#filter-likes').click(function () {
                var likes_opt = $('#likes-count-operator').val();
                var likes_value = parseInt($('#likes-count').val().trim(), 10);
                if (likes_opt !== '' && likes_value !== '' && likes_value >= 0) {
                    window.location = window.location.href.split('?')[0] + '?opt=' + likes_opt + '_' + likes_value;
                } else {
                    swal('Error', 'Invalid value!', 'error');
                }
            });
        });
    </script>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9&appId=1783766658609931";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <script>
        $(document).ready(function ($) {
            $(document).on('mouseover', '.post-holder', function (event) {
                var height = $(this).find('.fb-post').addClass('blur').height();
                $(this).find('.on-top').height(height).show();
            });

            $(document).on('mouseout', '.post-holder', function (event) {
                $(this).find('.fb-post').removeClass('blur');
                $(this).find('.on-top').hide();
            });
        });
        /*
         * Posting links
         */
        // Post link starts here
        function post_content(post_id, type, status) {

            var status = $("button[data-post-id='" + post_id + "']").attr('data-status-message');

            var options = <?php
			$output = '';
			foreach ($of['pages'] as $id => $name) {
				$output .= "<option value='".$id."'>".$name."</option>";
			}
			echo '"'.$output.'";';
		?>


		swal({
                    title: "Select Page/Account to Post",
                    text: "<select id='choose_page' data-plugin='select2' class='form-control'>" + options + "</select><br>Schedule Post <span style='font-size:12px'>Leave blank if you dont want to schedule it</span><input type='text' class='form-control' id='datepicker' style='display:block;' />Enter/Edit Status Message<br><textarea id='status_message' rows='5' class='form-control' style='margin-top:10px'>" + status + "</textarea>",
                    showCancelButton: true,
                    confirmButtonColor: "#0066CC",
                    confirmButtonText: "OK, Post it",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    html: true
                },
                function () {
                    var choose_page = $('#choose_page').val();
                    if (choose_page.indexOf('i') > -1 && type !== 'photo') {
                        $(".sa-button-container").css('display', 'block');
                        swal({
                            title: "Instagram",
                            type: "error",
                            text: "Instagram doesn't support " + type + " post.",
                            confirmButtonColor: "#FF7D7D",
                            confirmButtonText: "Close"
                        });
                        return false;
                    }

                    var url = '{{url("post")}}';
                    var status = $("#status_message").val();
                    var schedule_time = $("#datepicker").val();

                    $(".preloader").show();
                    swal({title: "Please Wait Posting", imageUrl: "{{url('assets/img/loading.gif')}}"});

                    $(".sa-button-container").hide();

                    if (type == 'video' && choose_page.indexOf('f') > -1) {

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
                                page_id: choose_page,
                                post_id: post_id,
                                type: type,
                                status: status,
                                schedule_time: schedule_time
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
                                } else {
                                    swal({
                                        title: "Great Posted :)",
                                        type: "success",
                                        text: "Post Successfully Posted",
                                        confirmButtonColor: "#FF7D7D",
                                        confirmButtonText: "Close"
                                    });
                                }
                            }
                        });
                    }
                }
            );

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

        function prev_page(pagetoken,type){
            $("button").attr('disabled','true');
            $(".preloader").show();
            var loading_html = '<div class="col-lg-12 loader"><h4>Please Wait Loading Next Page</h4><div><img src="{{ url("assets/img/loading.gif") }}" alt=""></div></div>';
            $("#ajaxContent").html(loading_html);
            $.ajax({
                url: '<?php echo url("/pagination?follow_page_id=" . $follow_page_id) . ((isset($_GET['opt']) && trim($_GET['opt']) !== '' ) ? ('&opt=' . $_GET['opt']) : '') ?>',
                type: 'POST',
                data: {pagetoken: pagetoken, type: type},
            dataType: "json",
                success: function(res){
                    $(".preloader").hide();
                $count = parseInt($("#page_count").text());

                cur_count = $count-1;

                if(cur_count > 0){
                    $("#page_count").html(cur_count);

                    $("button").removeAttr('disabled');

                    if (cur_count == 1)
                    {
                        $("#button_prev").attr('disabled','true');
                    }

                    $("#ajaxContent").html(res[0]);

                    $("#button_prev").attr('onclick', 'prev_page("'+res[1]+'", "{{$type}}")');
                    $("#button_next").attr('onclick', 'prev_page("'+res[2]+'", "{{$type}}")');
                    FB.XFBML.parse();
                }
            }
        });
        }

        function next_page(pagetoken,type){
            $("button").attr('disabled','true');
            $(".preloader").show();
            var loading_html = '<div class="col-lg-12 loader"><h4>Please Wait Loading Next Page</h4><div><img src="{{url('assets/img/loading.gif')}}" alt=""></div></div>';
            $("#ajaxContent").html(loading_html);

            $.ajax({
                url: '<?php echo url("/pagination?follow_page_id=" . $follow_page_id) . ((isset($_GET['opt']) && trim($_GET['opt']) !== '' ) ? ('&opt=' . $_GET['opt']) : '') ?>',
                type: 'POST',
                data: {pagetoken: pagetoken, type: type},
                dataType: "json",
                success: function(res){
                $(".preloader").hide();
                $count = parseInt($("#page_count").html());
                $("#page_count").html($count+1);
                $("button").removeAttr('disabled');
                $("#ajaxContent").html(res[0]);
                $("#button_next").attr('onclick', 'next_page("'+res[1]+'", "{{$type}}")');
                $("#button_prev").attr('onclick', 'prev_page("'+res[2]+'", "{{$type}}")');
                FB.XFBML.parse();
            }
        });
        }

        function retry_posting(choose_page, post_id, type, status, schedule_time) {
            $(".preloader").show();

            $.ajax({
                url: '{{url("post")}}',
                type: 'POST',
                data: {
                    page_id: choose_page,
                    post_id: post_id,
                    type: type,
                    status: status,
                    schedule_time: schedule_time
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
                            confirmButtonText: "Close",
                            html: true
                        });
                    } else {
                        swal({
                            title: "Great Posted :)",
                            type: "success",
                            text: "Post Successfully Posted",
                            confirmButtonColor: "#FF7D7D",
                            confirmButtonText: "Close"
                        });
                    }
                }
            });
        }
    </script>
@endsection
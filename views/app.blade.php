<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
<script src="{{ asset('public/vendor/jquery/jquery-1.12.3.min.js') }}"></script>
<script src="{{ asset('public/js/sortable.js') }}"></script>
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/jscrollpane/jquery.jscrollpane.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    {{--<link rel="stylesheet" href="{{ asset('public/vendor/sweetalert2/sweetalert2.min.css') }} ">--}}
    <link rel="stylesheet" href="{{ asset('public/vendor/select2/dist/css/select2.min.css') }} ">
    {{--<link rel="stylesheet" href="{{ asset('assets/css/glyphicons.css') }} ">--}}
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">--}}
    <link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">

    {{--<link rel="stylesheet" type="text/css" href="{{url('assets/css/jquery.fs.selecter.css')}}">--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/jquery-ui-timepicker-addon.css')}}">
     <link rel="stylesheet" type="text/css" href="{{asset('public/css/spectrum.css')}}">

    <!-- Neptune CSS -->
    <link rel="stylesheet" href="{{ asset('public/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/extra.css') }}">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('css')
</head>

<body id="app-layout" class="large-sidebar fixed-sidebar fixed-header skin-5">
<!-- Wrapper -->
<div class="wrapper">
    <!-- Preloader -->
   <div class="preloader"></div> 

  <!--    <div class="site-sidebar-overlay"></div> -->

    <!-- Sidebar -->
    <div class="site-sidebar">
        <a class="logo" href="/">
            <span class="l-text">SociAutomate</span>
            <span class="l-icon"></span>
        </a>
        <div class="custom-scroll custom-scroll-dark">
            <ul class="sidebar-menu">
                <li class="menu-title m-t-0-5">Navigation</li>

                <li>
                    <a href="{{ url('/content') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-dashboard"></i></span>
                        <span class="s-text">Dashboard</span>
                    </a>
                </li>
                
                
                
                <li>
                    <a href="{{ url('/pages-list') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-facebook"></i></span>
                        <span class="s-text">Our Social Pages</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/pages-list/following') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-sharethis"></i></span>
                        <span class="s-text">Pages To Follow</span>
                    </a>
                </li>
<!--
				<li class="with-sub">
                    <a href="#" class="waves-effect waves-light">
                        <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                        <span class="s-icon"><i class="fa fa-facebook"></i></span>
                        <span class="s-text">Pages List</span>
                    </a>
                    <ul>
                        <li><a href="{{ url('/pages-list') }}">Our Social Pages</a></li>
                        <li><a href="{{ url('/pages-list/following') }}">Pages To Follow</a></li>
                    </ul>
                </li>-->

                <li style="display: none;">
                    <a target="_blank" href="https://chrome.google.com/webstore/detail/content-grabber-for-faceb/lhbanfjhpmnabdninmjfmenolaklaaof" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-link"></i></span>
                        <span class="s-text">Chrome Extension</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('settings/apps-settings') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-settings"></i></span>
                        <span class="s-text">Settings</span>
                    </a>
                </li>
                
                <li style="display: none;">
                    <a href="{{ url('schedules') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-settings"></i></span>
                        <span class="s-text">Schedules</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('library') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="fa fa-book"></i></span>
                        <span class="s-text">Library</span>
                    </a>
                </li>
                
		<li style="display: none;">
                    <a href="{{ url('postschedule') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="fa fa-book"></i></span>
                        <span class="s-text">Post Schedule</span>
                    </a>
                </li>
                
                
                {{--<li>
                    <a href="{{ url('/filemanager/public/laravel-filemanager') }}" class="waves-effect waves-light popup-file">
                        <span class="s-icon"><i class="ti-folder"></i></span>
                        <span class="s-text">File Manager</span>
                    </a>
                </li>--}}
            </ul>
        </div>
    </div>
    <!-- Sidebar second -->
    <div class="site-sidebar-second">

    </div>
    <!-- Header -->
    <div class="site-header">
        <nav class="navbar navbar-dark">
            <ul class="nav navbar-nav">
                <li class="nav-item m-r-1 hidden-lg-up">
                    <a class="nav-link collapse-button" href="#">
                        <i class="ti-menu"></i>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav pull-xs-right">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
                        <div class="avatar box-32">
                            <img src="{{ asset('public/images/avatar.jpg') }}" alt="">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                        @if(Auth::user()->isAdmin())
                            <a class="dropdown-item" href="{{ url('/admin/levels') }}"><i class="fa fa-area-chart"></i> Account Levels</a>
                            <a class="dropdown-item" href="{{ url('/admin') }}"><i class="fa fa-user-secret"></i>
                                Manage Users</a>
                        @endif
                        <a class="dropdown-item" href="{{ url('settings/login-settings') }}"><i class="ti-user m-r-0-5"></i> Profile</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}"><i class="ti-power-off m-r-0-5"></i> Sign
                            out</a>
                    </div>
                </li>
                <li class="nav-item hidden-md-up">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse-1">
                        <i class="ti-more"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- Content -->
    <div class="site-content">
        <div class="content-area p-y-1" style="margin-top: 15px">
            <div class="container-fluid">
                <h4>@yield('title')</h4>

                @yield('main_content')
            </div>
        </div>

        <!-- Footer -->

        <footer class="footer">
            <div class="container-fluid">
                2017 © SociAutomate
            </div>
        </footer>
    </div>
</div>

<!-- Vendor JS -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{ asset('public/vendor/detectmobilebrowser/detectmobilebrowser.js') }}"></script>
<script src="{{ asset('public/vendor/jscrollpane/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('public/vendor/jscrollpane/mwheelIntent.js') }}"></script>
<script src="{{ asset('public/vendor/jscrollpane/jquery.jscrollpane.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
{{--<script src="{{ asset('public/vendor/sweetalert2/sweetalert2.min.js') }}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="{{ asset('public/vendor/select2/dist/js/select2.js') }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>--}}
<script src="{{ asset('public/js/spectrum.js') }}"></script>
<script src="{{ asset('public/js/app.js') }}"></script>

{{--<script src="{{ URL::to('assets/js/icheck.js') }}"></script>--}}
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ URL::to('assets/js/jquery-ui-timepicker-addon.js') }}"></script>
{{--<script src="{{url('assets/js/jquery.fs.selecter.js')}}"></script>--}}
<script>
    $('[data-plugin="select2"]').select2($(this).attr('data-options'));
</script>

 <link rel="stylesheet" href="{{ asset('public/vendor/Magnific-Popup/dist/magnific-popup.css') }}">
<script type="text/javascript" src="{{ asset('public/vendor/Magnific-Popup/dist/jquery.magnific-popup.min.js') }}"></script>
<script>
    $("document").ready(function(){
		$('.popup-file').magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,

			fixedContentPos: false
		});
    });
</script>


<script src="{{ asset('/public/js/wookmark.js')}}"></script>
<script type="text/javascript">
var wookmark_done = false;

var wookmark = undefined;


     $(document).ready(function(){
       
        setTimeout(function(){checkHeight();}, 5000);


        

    });

var isLoading = false;
     function onScroll(event) {
        // Only check when we're not still waiting for data.
        if(!isLoading) {
          // Check if we're within 100 pixels of the bottom edge of the broser window.
          var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
          if (closeToBottom) {
            loadData();
          }
        }
      }


      function onLoadData(data) {
        $("#ajaxContent").append(data);
        

        setTimeout(function(){checkHeight();}, 5000);
      };


      


     function checkHeight()
     {


if($('.fb-post').length <= 0)
{
   return; 
}


        $(".preloader").show();
        //console.log('checking height....');
        all_0 = false;
        $('.fb-post').each(function(k,v){

            //console.log('========'+$(this).height());
            if($(this).height() < 20)
            {
                all_0 = true;
                
            }

        });

        

        if(all_0)
        {
            setTimeout(function(){checkHeight();}, 5000);
        }
        else{


                if (wookmark === undefined) {
                    wookmark = new Wookmark('#ajaxContent', {
                        autoResize: true,
                        outerOffset: 10,
                        direction: 'left',
                        align: 'left',
                        offset: 2
                    });
                  } else {
                    //console.log(wookmark);
                    wookmark.initItems();
                    wookmark.layout(true);


                  }

                  $(".preloader").hide();

            
        }




     }
</script>

<style type="text/css">
.post-holder{
    width: 350px;
    xheight: auto !important;
}
.fb-post{
    position: relative !important;
    width: 350px;
    float: left;
    clear: both;
}

.post-holder .fb_iframe_widget span
{
    width: 100% !important;
}

.fb_iframe_widget iframe{
    width: 100% !important;
}
</style>


@yield('js')
</body>
</html>

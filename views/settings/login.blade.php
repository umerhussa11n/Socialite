@extends('app')

@section('title', 'Profile')

@section('main_content')
    <div class="box box-block bg-white">

        {{--<ol class="breadcrumb breadcrumb-arrow">--}}
        {{--<li><a href="#"><i class="fa fa-gear"></i> &nbsp;Settings</a></li>--}}
        {{--<li><a href="{{url('settings/login-settings')}}">&nbsp; Login Settings</a></li>--}}
        {{--</ol>--}}
        {{--</div>--}}

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group" style="text-align:center">
                    <img src="{{url('assets/img/key.png')}}" width="150px" style="margin-top:8px">
                </div>
            </div>
            <div class="col-lg-10">
                <div id="settings-holder">
                    <div class="form-group">
                        <label for="">User Name: </label>
                        {{$username}}
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input id="password" type="password" placeholder="Enter Password here" class="form-control">
                    </div>
                    <div class="form-group">
                        <button onclick="save_login_settings()" class="btn btn-primary">Save Settings</button>
                    </div>
                    <p>
                </div>
            </div>

        </div>
        @endsection
        @section('js')

            <script>
                function save_login_settings() {
                    var password = $("#password").val();
                    if (password.length < 6) {
                        swal("Oops!", "Password should be atleast 6 characters long", "error");
                        return false;
                    }

                    $(".preloader").show()
                    $.ajax({
                        url: '{{url("settings/update-login-details")}}',
                        type: 'POST',
                        data: {password: password},
                        success: function (res) {
                            $(".preloader").hide()
                            swal("Settings Saved", "New Login Details Saved", "success");
                        }
                    });
                }
            </script>
@stop
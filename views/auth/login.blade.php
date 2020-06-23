@extends('auth.layout')

@section('title', 'Login')
@section('bg'){{ asset('public/images/photos-1/7.jpg') }}@stop

@section('content')
    <div class="p-a-2 text-xs-center">
        <h5>Welcome</h5>
    </div>
    <form class="form-material m-b-1">
        <div class="form-group">
            <input id="username" type="text" class="form-control" name="email" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
        </div>

        <div class="p-x-2 form-group m-b-0">
            <div class="checkbox">
                <label>
                    <input id="remember" type="checkbox" name="remember"> Remember Me
                </label>
            </div>
        </div>

        <div class="p-x-2 form-group m-b-0">
            <button type="submit" id="submit_details" class="btn btn-purple btn-block text-uppercase">Sign in</button>
        </div>
</form>
    <div class="p-a-2 text-xs-center text-muted">
        <a class="text-black" href="{{ action('RemindersController@postRemind') }}"><span
                    class="underline">Forgot your password?</span></a>
    </div>
@endsection

@section('extra')
    <script>
        $("form").on('submit', function(e) {
            e.preventDefault();
        });

        $("#submit_details").on('click', function(event) {
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
            var remember = $("#remember").is(':checked') ? 1 : 0;
            if(username == '' || password == ''){
                swal("Oops","Please fill in all fields","error");
                return false;
            }

            $(".preloader").show();

            $.ajax({
                url: '{{url("login")}}',
                type: 'POST',
                data: {username: username,password:password,remember:remember},
                success: function(res){
                    $this.button('reset');
                    if(res == 'error'){
                        $(".preloader").hide();
                        swal("Oops","Wrong Details, please check again","error");
                    } if(res == 'db'){
                        $(".preloader").hide();
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
@endsection
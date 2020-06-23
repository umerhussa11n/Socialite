@extends('auth.layout')

@section('title', 'Join us today')
@section('bg'){{ asset('public/images/photos-1/9.jpg') }}@stop

@section('content')
    <div class="p-a-2 text-xs-center">
        <h5>Join us today</h5>
    </div>
    <form class="form-material" method="POST" action="{{ url('/register') }}">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <input type="text" class="form-control" name="name" placeholder="Username (Don't forget)" required>
            @if ($errors->has('name'))
                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" class="form-control" name="email" placeholder="Email" required>

            @if ($errors->has('email'))
                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" name="password" placeholder="Password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} m-b-3">
            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password">

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="p-x-2 form-group m-b-0">
            <button type="submit" class="btn btn-purple btn-block text-uppercase">Sign up</button>
        </div>
    </form>
    <div class="p-a-2 text-xs-center text-muted">
        Already have an account? <a class="text-black" href="{{ url('/login') }}"><span class="underline">Sign in</span></a>
    </div>
@endsection

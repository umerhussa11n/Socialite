@extends('auth.layout')

@section('title', 'Reset Password')
@section('bg'){{ asset('images/photos-1/5.jpg') }}@stop

@section('content')
    <div class="p-a-2 text-xs-center">
        <h5>Reset Password</h5>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-material m-b-1" method="POST" action="{{ url('/password/reset') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="form-control" name="email" placeholder="Email"
                   value="{{ $email or old('email') }}">
            @if ($errors->has('email'))
                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" name="password" placeholder="Password">

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

        <div class="p-a-2 form-group m-b-0">
            <button type="submit" class="btn btn-purple btn-block text-uppercase">Reset Password</button>
        </div>
    </form>
@endsection

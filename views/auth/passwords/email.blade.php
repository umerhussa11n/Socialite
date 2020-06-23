@extends('auth.layout')

@section('title', 'Reset Password')
@section('bg'){{ asset('images/photos-1/4.jpg') }}@stop

@section('content')
    <div class="p-a-2 text-xs-center">
        <h5>Reset Password</h5>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-material m-b-1" method="POST" action="{{ url('/password/email') }}">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} m-b-0">
            <input id="email" type="email" class="form-control" name="email" placeholder="Email"
                   value="{{ old('email') }}">
            @if ($errors->has('email'))
                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>

        <div class="p-a-2 form-group m-b-0">
            <button type="submit" class="btn btn-purple btn-block text-uppercase">Send Password Reset Link</button>
        </div>
    </form>
@endsection

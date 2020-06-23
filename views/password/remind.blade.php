@extends('auth.layout')

@section('title', 'Reset Password')
@section('bg'){{ asset('public/images/photos-1/7.jpg') }}@stop

@section('content')
    <div class="p-a-2 text-xs-center">
        <h5>Reset Password</h5>
    </div>
    
    @if (\Session::has('status'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{ Session::get('status') }}
        </div>
    @endif
    @if (\Session::has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{ \Session::get('error') }}
        </div>
    @endif

    <form class="form-material m-b-1" method="POST" action="{{ action('RemindersController@postRemind') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} m-b-0">
            <input id="email" type="email" class="form-control" name="email" placeholder="Email"
                   value="">
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

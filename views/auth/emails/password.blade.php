Hi {{ $user->name }}!<br />
Click here to reset your password: <a href="{{ $link = url('password/reset', $token) }}"> {{ $link }} </a>

<?php

class UserController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter(function()
		{
			if (Auth::check())
			{
				// The user is logged in...
				return Redirect::intended('facebook');
			}
		}, ['except' => ['getLogout']]);
	}

	public function getLogin(){
		return View::make('auth.login');
	}

	public function postLogin(){
            
		$username = Input::get('username');
		$password = Input::get('password');
        $remember = Input::get('remember');

		if (Auth::attempt(array('name' => $username, 'password' => $password), $remember))
		{
			return Redirect::intended('facebook');
		} else
		{
                    
			return 'error';
		}
	}

	public function getRegister(){
		return View::make('auth.register');
	}

	public function postRegister(){

		$rules = array(
			'name' => 'required|unique:users|min:6',
			'password' => 'required|min:6|confirmed',
			'email' => 'required|email|unique:users'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::to('register')->withErrors($validator);
		} else {
			$user = User::create([
				'name' => Input::get('name'),
				'email' => Input::get('email'),
				'password' => Hash::make(Input::get('password')),
			]);

			Auth::login($user);

			return Redirect::intended('facebook');
		}
	}

	public function getLogout(){
		Auth::logout();
		return Redirect::to('login');
	}

}

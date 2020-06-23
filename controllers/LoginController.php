<?php

class LoginController extends \BaseController {

	public function getIndex(){		
		return View::make('login.index');
	}

	public function postIndex(){
		$username = Input::get('username');
		$password = Input::get('password');
		try {
			DB::connection()->getDatabaseName();
		} catch (Exception $e) {
			die('db');
		}


		$db_username = Settings::find(3)->value;
		$db_password = Settings::find(4)->value;

		if (($username == $db_username) && ($password == $db_password)) {
			Session::put('logged', true);
		} else {
			return 'error';
		}
	}

	public function getLogout(){
		if (Session::has('logged')) {
			Session::forget('logged');
		}
		return Redirect::to('login?loggedout=true');
	}

}

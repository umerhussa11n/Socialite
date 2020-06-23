<?php 

class LoginFilter
{
	public function filter(){
		if (! Session::has('logged')) {
			return Redirect::to('login?required=true');
		}
	}
}
 ?>
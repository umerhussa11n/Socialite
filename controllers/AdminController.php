<?php

class AdminController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter(function()
		{
			if (!Auth::user()->is_admin)
			{
				// The user is not admin...
				return Redirect::intended('content');
			}
		});
	}

	public function getIndex(){
		$users = User::paginate(15); //where('is_admin', '!=', 1)->orWhereNull('is_admin')->
		return View::make('admin.users', compact('users'));
	}
	
	 public function getEditUser($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        return View::make('admin.edit_user', compact('user'));
    }

    public function getAddUser()
    {
        return View::make('admin.add_user');
    }

    public function postUpdateUser($id)
    {
		Input::flash();
		
		$user = User::where('id', $id)->firstOrFail();
		
		$rules = array(
			'name' => 'required|unique:users,name,' . $user->id . '|min:6',
			'email' => 'required|email|unique:users,email,' . $user->id,
		);
		
		if(Input::has('password'))
		{
			$rules['password'] = 'required|min:6|confirmed';
		}

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator);
		} else {
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			
			if(Input::has('password'))
			{
				$user->password = Hash::make(Input::get('password'));
			}
			
			$user->save();
			
			Session::flash('flash_success', 'The user has been updated successfully.');

			return Redirect::back();
		}
    }

    public function postCreateUser()
    {
		Input::flash();
		
		$rules = array(
			'name' => 'required|unique:users|min:6',
			'password' => 'required|min:6|confirmed',
			'email' => 'required|email|unique:users'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator);
		} else {
			$user = new User();
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->password = Hash::make(Input::get('password'));
			$user->save();
			
			Session::flash('flash_success', 'The user has been created successfully.');

			return Redirect::back();
		}
    }

    public function getDeleteUser($id)
    {
		if($id != Auth::id())
		{
			$user = User::where('id', $id)->firstOrFail();

			$user->delete();

			Session::flash('flash_success', 'The user has been deleted successfully.');
		}
		
        return Redirect::back();
    }
    
    public function getLevels() {
        $level_platinum = Settings::where('name','level_platinum')->pluck('value');
        $level_gold = Settings::where('name','level_gold')->pluck('value');
        return View::make('admin.levels', compact('level_platinum', 'level_gold'));
    }    
    public function postLevels() {
        Input::flash();
		
		$rules = array(
			'level_platinum' => 'integer',
			'level_gold' => 'integer'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator);
		} 
        else
        {
            $level_platinum = Input::get('level_platinum');
            $level_gold = Input::get('level_gold');
            
			$level_platinum = Settings::where('name','level_platinum')->update(['value' => $level_platinum]);
            $level_gold = Settings::where('name','level_gold')->update(['value' => $level_gold]);
			
			Session::flash('flash_success', 'The settings has been saved successfully.');

			return Redirect::back();
		}
    }
    
}

<?php 

class SettingsController extends \BaseController {

	public function appSettings(){
		$user = Auth::user();
		
		$app_id = $user->app_id;
		$app_secret = $user->app_secret;
		$dropbox_api = $user->dropbox_key;
		$chrome_key = $user->chrome_secret_key;
		$timezone = $user->timezone;
        
        $level_platinum_key = $user->level_platinum_key;
        $level_gold_key = $user->level_gold_key;
        
        list($can_add_fb,$can_add_in,$can_add_tw,$error_level_message) = $this->canAddAccounts();
        
        $facebooks = FbAccounts::where('user_id', Auth::id())->get();
        $_user_facebooks = FbAccounts::where('user_id', Auth::id())->where('app_id', $app_id)->where('app_secret', $app_secret)->first();
        $user_facebooks = false;
        if ($app_id && $app_secret && !$_user_facebooks) {
            $user_facebooks = true;
        }
        $twitters = Twitters::where('user_id', Auth::id())->get();
        $instagrams = Instagrams::where('user_id', Auth::id())->get();
        
		//$twitter = empty($user->twitter) ? array() : json_decode($user->twitter, true);
		return View::make('settings.app', compact('app_id','app_secret','dropbox_api','chrome_key','timezone','level_platinum_key','level_gold_key','can_add_fb','can_add_in','can_add_tw','error_level_message',  'facebooks','twitters','instagrams','user_facebooks' ));
	}

	public function loginSettings(){
		$username = Auth::user()->name;
		return View::make('settings.login', compact('username'));
	}

	public function updateLoginDetails(){
		$password = trim(Input::get('password'));
		$user = Auth::user();
		$user->password = Hash::make($password);
		$user->save();
	}
	public function checkApp(){
        list($can_add_fb,$can_add_in,$can_add_tw,$error_level_message) = $this->canAddAccounts();
            
		$app_id = Input::get('app_id');
		$app_secret = Input::get('app_secret');
		$output = false;
		$output = @file_get_contents('http://graph.facebook.com/'.$app_id);
		if($output && $can_add_fb){
			$user = Auth::user();
			$user->app_id = $app_id;
			$user->app_secret = $app_secret;
			$user->save();
            
            $user_id = Auth::user()->id;
            $fbAccounts = FbAccounts::firstOrNew(array(
                'user_id' => $user_id, 
                'app_id' => $app_id, 
                'app_secret' => $app_secret
            ));
            $fbAccounts->user_id = $user_id;
            $fbAccounts->app_id = $app_id;
			$fbAccounts->app_secret = $app_secret;
            $fbAccounts->save();
            
			return App::make('FacebookController')->login();
		} else {
			return 'invalid';
		}
	}
    public function reloadApp(){
		$app_id = Input::get('app_id');
		$app_secret = Input::get('app_secret');
		$output = false;
		$output = @file_get_contents('http://graph.facebook.com/'.$app_id);
		if($output){
            $user = Auth::user();
			$user->app_id = $app_id;
			$user->app_secret = $app_secret;
			$user->save();
            $user_id = Auth::user()->id;
            $fbAccounts = FbAccounts::firstOrNew(array(
                'user_id' => $user_id, 
                'app_id' => $app_id, 
                'app_secret' => $app_secret
            ));
            $fbAccounts->user_id = $user_id;
            $fbAccounts->app_id = $app_id;
			$fbAccounts->app_secret = $app_secret;
            $fbAccounts->save();
            
			return App::make('FacebookController')->login();
		} else {
			return 'invalid';
		}
	}

	public function generateChromeKey(){
		$user = Auth::user();
		$user->chrome_secret_key = $this->generateRandomString(30);
		$user->save();
	}

	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function save_dropbox_api(){
		$user = Auth::user();
		$user->dropbox_key = Input::get('dropbox_api');
		$user->save();
	}

	public function saveTimezone(){
		$user = Auth::user();
		$user->timezone = Input::get('timezone');
		$user->save();
	}	
	
	public function addTwitterSetting(){
		$data = array(
				'consumer_key' => Input::get('consumer_key'),
				'consumer_secret' => Input::get('consumer_secret'),
				'access_token' => Input::get('access_token'),
				'access_token_secret' => Input::get('access_token_secret'),
			);
			
		$twitter_user = TwitterHelper::validateAuthData($data);
			
        list($can_add_fb,$can_add_in,$can_add_tw,$error_level_message) = $this->canAddAccounts();
        
		if ($twitter_user && $can_add_tw)
		{
			$owner = json_decode($twitter_user, true);
			
			$twitter = Twitters::firstOrNew(array('user_id' => Auth::id(), 'owner_id' => $owner[0]['id']));
			$twitter->consumer_key = Input::get('consumer_key');
			$twitter->consumer_secret = Input::get('consumer_secret');
			$twitter->access_token = Input::get('access_token');
			$twitter->access_token_secret = Input::get('access_token_secret');
			$twitter->name = $owner[0]['name'];
			$twitter->screen_name = $owner[0]['screen_name'];
			$twitter->cover_image = isset($owner[0]['profile_banner_url']) ? $owner[0]['profile_banner_url'] : (isset($owner[0]['profile_background_image_url']) ? $owner[0]['profile_background_image_url'] : '');
			$twitter->profile_image = $owner[0]['profile_image_url'];
			$twitter->description = $owner[0]['description'];
			
			$twitter->save();
			
			return $twitter_user;
		}
			
		return 'false';
	}
	
	public function addInstagramSetting(){
		$username = Input::get('username');
		$password = Input::get('password');
		
		$key = 'instagram_' . $username;
		Session::forget($key);
		
		$instagram = InstagramHelper::getInstance($username, $password);
		
        list($can_add_fb,$can_add_in,$can_add_tw,$error_level_message) = $this->canAddAccounts();
        
		if ($instagram && $can_add_in)
		{
			try {
				$user = $instagram->getLoggedInUser();
				
				if ($user)
				{
					$instagram_user = Instagrams::firstOrNew(array('user_id' => Auth::id(), 'owner_id' => $user->getPk()));
					$instagram_user->password = $password;
					$instagram_user->profile_image = $user->getProfilePicUrl();
					$instagram_user->full_name = $user->getFullName();
					$instagram_user->username = $username;
					$instagram_user->save();
					
					return 'ok';
				}
			} catch(Exception $e){
				return $e->getMessage();
			}
		}
		
		return 'invalid';
	}
    
    public function saveLevel(){
        $level = Input::get('level');
        $level_key = Input::get('level_key');
        if ( in_array( $level_key, array('platinum','gold') ) ) { //  && in_array($level, array('006-023-936-041','083-926-967-411') )
            if ( !empty($level) && 'gold' == $level_key && '006-023-936-041' != $level ) {
                return 'Something wrong';
            }
            if ( !empty($level) && 'platinum' == $level_key && '083-926-967-411' != $level ) {
                return 'Something wrong';
            }
            $level_key_name = "level_{$level_key}_key";
            $user = Auth::user();
            $user->$level_key_name = $level;
            $user->save();
            return 'ok';
        } else {
            return 'Something wrong';
        }
	}    
    private function canAddAccounts() {
        $user = Auth::user();
        $level_platinum_key = $user->level_platinum_key;
        $level_gold_key = $user->level_gold_key;
        
        $level_platinum = Settings::where('name','level_platinum')->pluck('value');
        $level_gold = Settings::where('name','level_gold')->pluck('value');
        
        $user_id = Auth::id();
        $fbaccounts = FbAccounts::where('user_id', $user_id)->count();
        $instagrams = Instagrams::where('user_id', $user_id)->count();
        $twitters = Twitters::where('user_id', $user_id)->count();
        $total_accounts = $fbaccounts + $instagrams + $twitters;
        
        $can_add_fb = true;
        $can_add_in = true;
        $can_add_tw = true;
        $error_level_message = 'If you need to install more social accounts you can upgrade here:';
        if ( !empty($level_platinum_key) ) {
            if ( $total_accounts >= $level_platinum ) {
                $can_add_fb = $can_add_in = $can_add_tw = false;
                //$error_level_message = 'Your platinum level is complete.';
            }
        } elseif ( !empty($level_gold_key) ) {
            if ( $total_accounts >= $level_gold ) {
                $can_add_fb = $can_add_in = $can_add_tw = false;
                //$error_level_message = 'Your gold level is complete.';
            }
        } else {
            $can_add_fb = $fbaccounts > 0 ? false : true;
            $can_add_in = $instagrams > 0 ? false : true;
            $can_add_tw = $twitters > 0 ? false : true;
        }
        return array($can_add_fb,$can_add_in,$can_add_tw,$error_level_message);
	}
    
    public function removeFacebookAccount(){
        $user_id = Auth::id();
		$app_id = Input::get('app_id');
		$app_secret = Input::get('app_secret');
        $account = FbAccounts::where( array( 'user_id' => $user_id, 'app_id' => $app_id, 'app_secret' => $app_secret ) );
        $user = Auth::user();
        if ($user['app_id']==$app_id && $user['app_secret']==$app_secret)
        {
            $user->app_id = NULL;
            $user->app_secret = NULL;
            $user->save();
        }
		if ( $account && $account->delete() )
		{
            //return 'ok';
		}
		return 'ok';
	}
    public function removeTwitterAccount(){
		$account_id = Input::get('account_id');
        $account = Twitters::where( array( 'user_id' => Auth::id(), 'id' => $account_id ) );
		if ( $account && $account->delete() )
		{
            return 'ok';
		}
		return 'invalid';
	}
    public function removeInstagramAccount(){
		$account_id = Input::get('account_id');
        $account = Instagrams::where( array( 'user_id' => Auth::id(), 'id' => $account_id ) );
		if ( $account && $account->delete() )
		{
            return 'ok';
		}
		return 'invalid';
	}
    
}
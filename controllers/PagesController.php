<?php

class PagesController extends \BaseController {

	private $fb, $fb_error;

	public function checkFB(){
		$this->fb_error = false;
		$app_id = Auth::user()->app_id;
		$app_secret = Auth::user()->app_secret;
		$success = true;
		$fb = true;
		try {			
			$fb = new FacebookHelper($app_id, $app_secret);
		} catch (Exception $e) {
			$this->fb_error = true;				
		}		
		$this->fb = $fb;
	}	

	public function getIndex(){
		$ourPages = Pages::where('user_id', Auth::id())->get();
		$twitters = Twitters::where('user_id', Auth::id())->get();
		$instagrams = Instagrams::where('user_id', Auth::id())->get();
		
		return View::make('pages-list',compact('ourPages', 'twitters', 'instagrams'));
	}
	
	public function getFollowing(){
		$followPages = Follow::where('user_id', Auth::id())->get();
//		$follow_count = Follow::where('user_id', Auth::id())->count();
		
		$follow_twitters = FollowTwitters::where('user_id', Auth::id())->get();
		$follow_instagrams = FollowInstagrams::where('user_id', Auth::id())->get();

		return View::make('pages-list-following',compact('followPages', 'follow_twitters', 'follow_instagrams'));
	}

	public function postRemoveFacebookPage(){
		$page_id = Input::get('page_id');
		Pages::where('id', $page_id)->where('user_id', Auth::id())->delete();
	}
	
	public function postRemoveFacebookPageFollow(){
		$page_id = Input::get('page_id');
		Follow::where('id', $page_id)->where('user_id', Auth::id())->delete();
	}
	
	public function postRemoveTwitterAccount(){
		$owner_id = Input::get('owner_id');
		Twitters::where('owner_id', $owner_id)->where('user_id', Auth::id())->delete();
	}
	
	public function postRemoveTwitterAccountFollow(){
		$owner_id = Input::get('owner_id');
		FollowTwitters::where('owner_id', $owner_id)->where('user_id', Auth::id())->delete();
	}
	
	public function postRemoveInstagramAccount(){
		$owner_id = Input::get('owner_id');
		Instagrams::where('owner_id', $owner_id)->where('user_id', Auth::id())->delete();
	}

	public function postRemoveInstagramAccountFollow(){
		$owner_id = Input::get('owner_id');
		FollowInstagrams::where('owner_id', $owner_id)->where('user_id', Auth::id())->delete();
	}

	public function postAddFacebookPageFollow(){
		$this->checkFB();
		
		$first_page = Pages::where('user_id', Auth::id())->first();
		
		if (!$first_page)
		{
			return 'no_page';
		}
		
		$page_id = Input::get('page_id');
		
		if(! $this->fb->checkPage($page_id, $first_page->access_token)){
			return 'invalid';
		} 		
		
		$pageinfo = $this->fb->pageInfo($page_id);
		
		$source = 'none';
		if(isset($pageinfo["cover"]->source)){
			$source = $pageinfo["cover"]->source;
		}
		
		$about = '';
		if (isset($pageinfo["about"])) {
			$about = $pageinfo["about"];
		}
		
		$facebook = Follow::firstOrCreate(array(
					'page_id' => $pageinfo["id"],
					'user_id' => Auth::id()
				));
				
		$facebook->name = $pageinfo["name"];
		$facebook->description = $about;
		$facebook->cover = $source;
		
		$facebook->save();
		
		return 'ok';
	}
	
	public function postAddTwitterAccountFollow(){
		$twitter = Twitters::where('user_id', Auth::id())->first();
		
		if (!$twitter)
		{
			return 'no_account';
		}
		
		Twitter::reconfig([
			'consumer_key' => $twitter->consumer_key,
			'consumer_secret' => $twitter->consumer_secret,
			'token' => $twitter->access_token,
			'secret' => $twitter->access_token_secret,
		]);
		
		$user = Twitter::getUsers(['screen_name' => Input::get('name'), 'format' => 'json']);
		
		if ($user)
		{
			$owner = json_decode($user, true);
			
			$follow = FollowTwitters::firstOrNew(array('user_id' => Auth::id(), 'owner_id' => $owner['id']));
			$follow->name = $owner['name'];
			$follow->screen_name = $owner['screen_name'];
			$follow->cover_image = isset($owner['profile_banner_url']) ? $owner['profile_banner_url'] : (isset($owner['profile_background_image_url']) ? $owner['profile_background_image_url'] : '');
			$follow->profile_image = $owner['profile_image_url'];
			$follow->description = $owner['description'];
			
			$follow->save();
			
			return 'ok';
		}
	
		return 'invalid';
	}
	
	public function postAddInstagramAccountFollow(){
		$instagram_ = Instagrams::where('user_id', Auth::id())->first();
		
		if (!$instagram_)
		{
			return 'no_account';
		}
		
		$instagram = InstagramHelper::getInstance($instagram_->username, $instagram_->password);
		
		if ($instagram)
		{
			try {
				$user = $instagram->getUserByUsername(Input::get('name'));
				
				if ($user)
				{
					$instagram_user = FollowInstagrams::firstOrNew(array('user_id' => Auth::id(), 'owner_id' => $user->getPk()));
					$instagram_user->profile_image = $user->getProfilePicUrl();
					$instagram_user->full_name = $user->getFullName();
					$instagram_user->username = $user->getUserName();
					$instagram_user->save();
					
					return 'ok';
				}
			} catch(Exception $e){
				return $e->getMessage();
			}
		}

		return 'invalid';
	}
}
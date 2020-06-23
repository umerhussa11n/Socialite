<?php

class FacebookController extends \BaseController {
	private $fb;

	public function __construct(){
		$this->fb = $this->getConstruct();
	}

	public function getConstruct(){
		if (Auth::check())
		{
			$app_id = Auth::user()->app_id;
			$app_secret = Auth::user()->app_secret;
			$fb = new FacebookHelper($app_id, $app_secret);
			return $fb;
		}
	}
	
	public function getConstructChrome($chrome_key){
		return User::where('chrome_secret_key', trim($chrome_key))->first();
	}

	public function login(){
		return $this->fb->getUrlLogin();
	}

	public function callback(){
		if(! $this->fb->generateSessionFromRedirect()){
			return 'Session Null Error';
		}
		$pages = $this->fb->getRequest();
		
		if (isset($pages['data']))
		{
			foreach ($pages['data'] as $page) {
				try
				{
						if ($this->fb->checkPage($page->id, $page->access_token)) {
							$source = '';				
							$pageinfo = $this->fb->pageInfo($page->id);
							if(isset($pageinfo["cover"]->source)){
								$source = $pageinfo["cover"]->source;
							}
							$about = '';
							if (isset($pageinfo["about"])) {
								$about = $pageinfo["about"];
							}
							if(in_array('ADMINISTER', $page->perms)){				
								if(Pages::where('page_id', $page->id)->where('user_id', Auth::id())->count()){
									Pages::where('page_id', $page->id)->where('user_id', Auth::id())->update(array(
											'name' => $page->name,
											'description' => $about,
											'cover' => $source,
											'access_token' => $page->access_token,
											'perms' => implode(',', $page->perms)
										));
								} else {
									Pages::create(array(
										'name' => $page->name,
										'description' => $about,
										'cover' => $source,
										'access_token' => $page->access_token,
										'page_id' => $page->id,
										'perms' => implode(',', $page->perms),
										'user_id' => Auth::id()
									));
								}
							}
						}
				}
				catch(Exception $e){}


			}
		}
		
		return Redirect::to('settings/apps-settings');
	}

	public function postPage(){
		$app_id = 2;
		$app = Pages::find(1);
		return serialize($this->fb->postPage($app));
	}


}

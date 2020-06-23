<?php

class DropboxController extends \BaseController {

	private $fb, $fb_error;

	public function __construct(){
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
		$ours = Pages::where('user_id', Auth::id())->get();
		$breadcrumb = array();
		$total_files = array();		
		if(Auth::user()->dropbox_key == ''){
			return View::make('dropbox.error_key', compact('breadcrumb','ours','total_files'));
		}
		if ($this->fb_error) {
			return Redirect::to('settings/apps-settings?settings=invalid');
		}
		$path = '/';
		if (Input::get('path')) {
			$path = Input::get('path');
			$test = explode('/', substr($path, 1));
			$breadcrumb = $test;
		}
		$dropbox = new DropboxHelper();
		try {
			$total_files = $dropbox->listFiles($path);						
		} catch (Exception $e) {
			$total_files = array();
			return View::make('dropbox.error_key', compact('breadcrumb','ours','total_files'));
		}
		// showArray($total_files);
		return View::make('dropbox.list', compact('total_files','breadcrumb','ours'));
	}

	public function getViewContent(){
		$dropbox = new DropboxHelper();
		$path = Input::get('path');
		return Redirect::to($dropbox->getRawUrl($path));
	}

	public function postContent(){

		$path = Input::get('path');
		$page_id = Input::get('page_id');
		$type = Input::get('type');
		$status = Input::get('status');
		$schedule_time = Input::get('schedule_time');
		$timestamp = 0;

		if (strlen($schedule_time) > 0) {
			$user_timezone = Auth::user()->timezone;
			$schedule_time = explode(':+', $schedule_time);
			$date = new DateTime($schedule_time[0], new DateTimeZone($user_timezone));			
			$timestamp = $date->getTimestamp();
		}

		$page = Pages::where('id', $page_id)->where('user_id', Auth::id())->first();

		if ($type === 'video') {
			return $this->fb->postDropboxVideo($path, $page, $status, $timestamp);
		} else if($type === 'image'){
			return var_dump($this->fb->postDropboxImage($path, $page, $status, $timestamp));
		}		
	}
}

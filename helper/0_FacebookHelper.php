<?php 
/**
* 
*/
session_start();

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookAuthorizationException;

class FacebookHelper
{
	private $helper;
	private $session;

	public function __construct($app_id, $app_secret){
		$redirect = url('callback');		
		FacebookSession::setDefaultApplication($app_id,$app_secret);
		$this->helper = new FacebookRedirectLoginHelper($redirect);
	}

	public function getUrlLogin(){
		$params = array('publish_pages','manage_pages');
		return $this->helper->getLoginUrl($params);
	}	

	public function generateSessionFromRedirect(){
		$this->session = null;
		try {
			$this->session = $this->helper->getSessionFromRedirect();
		} catch (FacebookRequestException $e) {
			// Facebook error
		} catch( \Exception $ex){
			// validation error
		}
		return $this->session;
	}

	public function getRequest(){
		try {
		    $responce = (new FacebookRequest($this->session, 'GET', '/me/accounts'))->execute()->getGraphObject()->AsArray();		    
		    // $responce = json_decode($responce->getRawResponse(), true);
		    // $responce = (array) $responce->getGraphObject();
		    return $responce;
		  } catch(FacebookRequestException $e) {
		    echo "Exception occured, code: " . $e->getCode();
		    echo " with message: " . $e->getMessage();
		  }  
	}

	public function postPage($app){
		$session = new FacebookSession($app->access_token);
		$responce = new FacebookRequest($session, 'POST', '/'.$app->app_id.'/feed',array('message' => 'Life is not about finding yourself. Life is about creating yourself.'));
		$responce = $responce->execute()->getGraphObject()->AsArray();		
		return $responce;
	}

	// Getting page posts of single page
	public function getPosts($page, $type){
		$responce = null;
		$session = FacebookSession::newAppSession();	
		if($type == 'photos'){
			$type = 'photos?fields=link,name,likes.limit(1).summary(true)&type=uploaded';
		} elseif($type == 'posts'){
			$type = 'posts?fields=created_time,message,status_type,likes.limit(1).summary(true)';
		} elseif($type == 'videos'){
			$type = 'videos?fields=from,source,likes.limit(1).summary(true)';
		}
		
		$responce = new FacebookRequest($session, 'GET', '/'.$page->page_id.'/'.$type);
		
		$responce = $responce->execute()->getGraphObject()->AsArray();		

		return $responce;
	}

	public function getPostsHttp($token){
		$access_token = $this->getAppToken();
		$url = $token.'&access_token='.$access_token;
		$posts = file_get_contents($url);		
		return json_decode($posts);
	}

	public function getFirstPostsHttp($page, $tail){		
		$access_token = $this->getAppToken();
		$url = 'https://graph.facebook.com/'.$page->page_id.'/'.$tail.'?access_token='.$access_token;
		$posts = file_get_contents($url);		
		return json_decode($posts);
	}

	public function getAppToken(){
		$app_id = Auth::user()->app_id;
		$app_secret = Auth::user()->app_secret;
		return $app_id.'|'.$app_secret;
	}

	/*
	* Start of pages list code	
	 */
	// Check page
	public function checkPage($page_id, $access_token = ''){
		$responce = false;
		try {
			$responce = file_get_contents('https://graph.facebook.com/'.$page_id . '?access_token=' . $access_token);			
		} catch (Exception $e) {
			dd($e->getMessage());
		}
		if(! $responce){
			return false;
		}
		$json = json_decode($responce);
		if(isset($json->id)){
			return true;
		}
		return false;
	}

	// Get page info	
	public function pageInfo($page_id){
		$session = FacebookSession::newAppSession();		
		try {
			$responce = new FacebookRequest($session, 'GET', '/'.$page_id.'?fields=id,name,about,cover');								
		} catch (GraphMethodException $e) {
			return false;
		}
		$responce = $responce->execute()->getGraphObject()->AsArray();					
		return $responce;	
	}

	// Get Post Info
	public function getPostInfo($post_id, $type){
		//$array = explode("_", $post_id);
		//$post_id = end($array);
		$session = FacebookSession::newAppSession();		
		
		if($type == 'photo'){
			$type = '?fields=full_picture,name';
		} elseif($type == 'link'){
			$type = '?fields=name,link';
		} elseif($type == 'video'){
			$type = '?fields=from,source';
		} else {
			$type = '';
		}
		
		try {
			$responce = new FacebookRequest($session, 'GET', '/'.$post_id . $type);								
		} catch (GraphMethodException $e) {
			return false;
		}
		$responce = $responce->execute()->getGraphObject()->AsArray();	

		return $responce;		
	}
	
	public function cURL($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($ch);
		curl_close($ch);
		
		return $return;
	}

	/*
	* Posting starts here
	 */
	
	public function postLink($page, $post){
		$data = array();
		
		$data['access_token'] = $page->access_token;
		$data['link'] = $post['link'];
		$data['message'] = $post["status"];
		
		if (isset($post['timestamp'])) {
			$data['published'] = "false";
			$data['scheduled_publish_time'] = $post['timestamp'];
		}
		
		return $this->cURL('https://graph.facebook.com/'.$page->page_id.'/feed', $data);
	}

	public function postImage($page, $post){
		$image_source = $post["full_picture"];		
		$data = array();
		
		$data['access_token'] = $page->access_token;
		$data['url'] = $image_source;
		$data['message'] = $post["status"];
		
		if (isset($post['timestamp'])) {
			$data['published'] = "false";
			$data['scheduled_publish_time'] = $post['timestamp'];
		}
		
		return $this->cURL('https://graph.facebook.com/'.$page->page_id.'/photos', $data);
	}

	public function postVideo($page, $post){
		$source = $post['source'];
		$data = array();
		
		$data['access_token'] = $page->access_token;
		$data['description'] = $post["status"];
		$data['file_url'] = $source;
		
		if (isset($post['timestamp'])) {
			$data['published'] = "false";
			$data['scheduled_publish_time'] = $post['timestamp'];
		}
		
		return $this->cURL('https://graph.facebook.com/'.$page->page_id.'/videos', $data);
	}


	public function postStatus($page, $status, $timestamp){		
		$data = array();
		
		$data['access_token'] = $page->access_token;
		$data['message'] = $status;
		
		if (!empty($timestamp)) {
			$data['published'] = "false";
			$data['scheduled_publish_time'] = $timestamp;
		}

		return $this->cURL('https://graph.facebook.com/'.$page->page_id.'/feed', $data);
	}

	public function getNewResponce($responce){
		$responce = $responce->getRequestForPreviousPage();
		$posts = $responce->execute();
		return $posts;
	}

	/*
	* Checking facebook app
	 */
	
	public function checkApp($app_id, $app_secret){
		$output = false;
		$output = @file_get_contents('http://graph.facebook.com/'.$app_id);
		return ($output) ? true : false;
	}

	public function getPageSummary($page, $type){		
		$session = FacebookSession::newAppSession();	
		$responce = new FacebookRequest($session, 'GET', '/'.$page->page_id.'/'.$type,array('fields' => 'message,shares,comments.limit(1).summary(true),likes.limit(1).summary(true)'));
		$responce = $responce->execute()->getGraphObject()->AsArray();		
		return $responce;	
	}


	/* Dropbox methods */

	public function postDropboxVideo($path, $page, $status, $schedule_time){
		$dh = new DropboxHelper();		
		$video_url = $dh->getRawUrl($path);
		if (strlen($schedule_time)) {
			$url = 'https://graph-video.facebook.com/'.$page->page_id.'/videos?access_token='.$page->access_token.'&description='.urlencode($status).'&method=post&file_url='.urlencode($video_url).'&published=false&scheduled_publish_time='.$schedule_time;			
		} else {
			$url = 'https://graph-video.facebook.com/'.$page->page_id.'/videos?access_token='.$page->access_token.'&description='.urlencode($status).'&method=post&file_url='.urlencode($video_url);
		}
		return file_get_contents($url);
	}

	public function postDropboxImage($path, $page, $status, $schedule_time){
		$dh = new DropboxHelper();		
		$image_url = $dh->getRawUrl($path);
		if (strlen($schedule_time)) {
			$url = 'https://graph.facebook.com/'.$page->page_id.'/photos?access_token='.$page->access_token.'&message='.urlencode($status).'&method=post&url='.urlencode($image_url).'&published=false&scheduled_publish_time='.$schedule_time;			
		} else {
			$url = 'https://graph.facebook.com/'.$page->page_id.'/photos?access_token='.$page->access_token.'&message='.urlencode($status).'&method=post&url='.urlencode($image_url);						
		}
		return file_get_contents($url);	
	}
}

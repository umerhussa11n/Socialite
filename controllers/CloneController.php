<?php

class CloneController extends \BaseController {

	private $fb;

	public function __construct(){
		$app_id = Auth::user()->app_id;
		$app_secret = Auth::user()->app_secret;
		$fb = new FacebookHelper($app_id, $app_secret);
		$this->fb = $fb;
	}

	public function getIndex(){
		$pages = Pages::where('user_id', Auth::id())->get();
		$follows = Follow::where('user_id', Auth::id())->get();
		$app_id = Auth::user()->app_id;;//Settings::where('id',1)->pluck('value');
		return View::make('clone.index', compact('pages', 'follows','app_id'));  
	}

	public function postClonePage(){
		$main_page = Input::get('main_page');
		$empty_page = Input::get('empty_page');
		$post_type = Input::get('post_type');
		$min_likes = Input::get('min_likes');
		$min_shares = Input::get('min_shares');
		$min_comments = Input::get('min_comments');
		$post_days = Input::get('post_days');
		$post_hours = Input::get('post_hours');
		$post_minutes = Input::get('post_minutes');
		$page = Follow::where('id', $main_page)->where('user_id', Auth::id())->first();

		$posts = array();
		if ($post_type == 'all') {
			$tail = 'posts'; 
			$posts = $this->fb->getPageSummary($page, $tail);	
			$date = new DateTime();
			$timestamp = $date->getTimestamp();			
			Session::set($timestamp, $posts["paging"]);
			$sorted = $this->filterPosts($posts['data'], $min_likes, $min_shares, $min_comments);
			return json_encode($sorted);
		} else {
			
		}

		return var_dump($posts);

	}

	function filterPosts($posts, $likes,  $shares, $comments){
		$output = array();
		foreach ($posts as $post) {
			if ((int) $likes) {
				if (isset($post->likes->summary->total_count)) {
					if ($post->likes->summary->total_count < $likes) {						
						continue;
					}
				} else { continue; }
			}
			if ((int) $comments) {
				if (isset($post->comments->summary->total_count)) {
					if ($post->comments->summary->total_count < $comments) {						
						continue;
					}
				} else { continue; }
			}
			if ((int) $shares) {
				if (isset($post->shares->count)) {
					if ($post->shares->count < $shares) {						
						continue;
					}
				} else { continue; }
			}
			array_push($output, $post);
		}
		return $output;
	}
}
<?php

class ChromeController extends \BaseController {

	private $fb;
	private $user;

	public function setup($key){
		$this->user = App::make('FacebookController')->getConstructChrome($key);
		
		if ($this->user)
		{
			$this->fb = new FacebookHelper($this->user->app_id, $this->user->app_secret);
		} else
		{
			$result = json_encode(array('output' => 'chrome_secret'));
			exit($result);
		}
	}

	public function pageDetails(){
		$this->setup(Input::get('chrome_secret'));
		
		$pages = Pages::where('user_id', $this->user->id)->select('id', 'name')->get();
		$twitters = Twitters::where('user_id', $this->user->id)->select('id', 'name', 'screen_name')->get();
		$instagrams = Instagrams::where('user_id', $this->user->id)->select('id', 'full_name', 'username')->get();
		
		$data = array();
		
		foreach($pages as $page)
		{
			$data[] = array(
				'id' => 'f-' . $page->id,
				'name' => 'Facebook: ' . $page->name
			);
		}
		
		foreach($twitters as $twitter)
		{
			$data[] = array(
				'id' => 't-' . $twitter->id,
				'name' => "Twitter: $twitter->name ($twitter->screen_name)"
			);
		}
		
		foreach($instagrams as $instagram)
		{
			$data[] = array(
				'id' => 'i-' . $instagram->id,
				'name' => "Instagram: $instagram->full_name ($instagram->username)"
			);
		}
		$libraries = Tbllibrary::where(['is_active' => 1, 'user_id' => $this->user->id])->take(1000)->get();
		
		if((sizeof($libraries)>0)&&(Input::get('vid'))){
		   $vformats = array("mp4", "3gpp");
           $vid = Input::get('vid');
           parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=".$vid),$info); 
           $streams = $info['url_encoded_fmt_stream_map']; 

           $streams = explode(',',$streams);
           $viscounter=0;
           foreach($streams as $stream){
               parse_str($stream,$datas); 

               foreach($vformats as $vformat){
                 if(stripos($datas['type'],$vformat) !== false){ 
                    
					  $libraries[0]->videourl = $datas['url'];
					  $libraries[0]->visformat = $vformat;
                      $viscounter=1;

                       break;
                  }
                }
                if($viscounter==1)break;
            }
		
	   }
		return json_encode($libraries);	
	}

	public function postContent(){
		$post_details = json_decode(Input::get('post_details'));

		$this->setup($post_details->chrome_secret);
		
		$social_media = $post_details->social_media;
    	$type 		  = $post_details->type;
    	$message	  = $post_details->status;
    	$cut_video_url = $post_details->cut_video_url;
    
    	$library_id 	  = $post_details->library_id;
    	$post_id 	  = "278611478977359_818871551618013";


        

    	

        if($social_media == 't')
        {
            $tweet = TwitterHelper::getTweet($post_id);
            // echo '===========<pre>';
            // print_r($tweet);
            // exit();
            
            if($type == 'link')
            {
                    $l =  $tweet['video'];
                    $p = $tweet['photo'];
                    $v = $tweet['video'];
            }
            else
            if($type == 'status')
            {
                    $l =  '';
                    $p = '';
                    $v = '';
            }
            else
            if($type == 'photo')
            {
                    $l =  '';
                    $p = $tweet['photo'];
                    $v = '';
            }
            else
            if($type == 'video')
            {
                    $l =  '';
                    $p = $tweet['photo'];
                    if(trim($cut_video_url) != '')
                    {
                    	$v = $cut_video_url;
                    }
                    else
                    {
                    	$v = $tweet['video'];
                    }
            }

            $tblpost = new Tblpost();
            $tblpost->social_media 	= $social_media;
            $tblpost->type 			= $type;
            $tblpost->message 		= urlencode($message);
            $tblpost->link_url 		= $l;
            $tblpost->photo_url	 	= $p;
            $tblpost->video_url 		= $v;
            $tblpost->social_media_id 	= $post_id;
            $tblpost->library_id 	= $library_id;


            //echo '<pre>'; 
            //print_r($tblpost);
            //exit();
             
             $tblpost->save();
        }
        else
    	if($social_media == 'i')
    	{
            $feed = InstagramHelper::getFeed($post_id);
            // echo '<pre>';
            // print_r($feed);
            // exit();

            if($type == 'photo')
            {
                    $l =  '';
                    $p = $feed['photo'];
                    $v = '';
            }
            else
            if($type == 'video')
            {
                    $l =  '';
                    $p = $feed['photo'];
                    if(trim($cut_video_url) != '')
                    {
                    	$v = $cut_video_url;
                    }
                    else
                    {
                    	$v = $feed['video'];
                    }
            }

            $tblpost = new Tblpost();
            $tblpost->social_media 	= $social_media;
            $tblpost->type 			= $type;
            $tblpost->message 		= urlencode($message);
            $tblpost->link_url 		= $l;
            $tblpost->photo_url	 	= $p;
            $tblpost->video_url 		= $v;
            $tblpost->social_media_id 	= $post_id;
            $tblpost->library_id 	= $library_id;


             // echo '<pre>'; 
             // print_r($tblpost);
             // exit();

            $tblpost->save();
    	}
    	else
        if($social_media == 'f')
        {
        	// $this->checkFB();

        	if($type  == 'link')
        	{
        		$post = $this->fb->getPostInfo($post_id, 'link');
        		$l =  '';
        		$p = '';
        		$v = '';

        	}
        	elseif($type == 'status')
        	{
        		$post = $this->fb->getPostInfo($post_id, 'status');
//        		echo '<pre>========';
//                 print_r($post);
//                 exit();
                        
        		$l =  '';
        		$p = "";
        		$v = '';

        	}
        	elseif($type == 'video'){
        		$post = $this->fb->getPostInfo($post_id, 'video');
                //echo '<pre>========';
                // print_r($post);
                // exit();

        		$l =  '';
        		//$p = $post['full_picture'];
        		
                            $v = $post_details->content;
                     $p='';
                       
        	}
        	else
        	{
        		$post = $this->fb->getPostInfo($post_id, 'photo');
				if($type=="image"){
				$type='photo';
				}
				
        		$l =  '';
        		$p = $post_details->content;
        		$v = '';
        	}
        	// echo '<pre>'; 
        	// print_r($post);
        	// exit();

        	$tblpost = new Tblpost();
        	$tblpost->social_media 	= $social_media;
        	$tblpost->type 			= $type;
        	$tblpost->message 		= urlencode($message);
        	$tblpost->link_url 		= $l;
        	$tblpost->photo_url	 	= $p;
        	$tblpost->video_url 		= $v;
        	$tblpost->social_media_id 	= $post_id;
        	$tblpost->library_id 	= $library_id;


        	// echo '<pre>'; 
        	// print_r($tblpost);
        	// exit();

        	$tblpost->save();

        	//return Redirect::to('postschedule');
        }
		return json_encode(array('output' => $post_details->chrome_secret));
	}

	public function getTimestamp($schedule_time){
		$user_timezone = Auth::user()->timezone;
		$schedule_time = explode(':+', $schedule_time);	
		$date = new DateTime($schedule_time[0], new DateTimeZone($user_timezone));			
		$timestamp = $date->getTimestamp();
		return $timestamp;
	}
}

<?php 
/**
* 
*/



class SocialHelper
{
	private static $fb;
	private static $fb_error;

    public static function checkFB()
    {
		self::$fb_error = false;
		$app_id = Auth::user()->app_id;
		$app_secret = Auth::user()->app_secret;
		$fb = true;
		try {	
			$fb = new FacebookHelper($app_id, $app_secret);

		} catch (Exception $e) {

			self::$fb_error = true;				
		}		
		self::$fb = $fb;
    }

/*POST TO FACEBOOK STARTS*/
   public static function  postLinkToFB($page_id, $link_url, $status)
   {
   //	echo '<pre>----'.$page_id;

       self::checkFB();        
       $page = Pages::where('id', $page_id)->where('user_id', Auth::id())->first();//Pages::find($page_id);

       //print_r($page);exit();
       
       $data = array();
       $data['link'] = $link_url;
       $data['status'] = $status;

       $sch_post = self::$fb->postLink($page, $data);
       
      // print_r($sch_post);
       return $sch_post;
   }
    
    public static function postStatusToFB($page_id, $message)
    {
        self::checkFB();        
        $page = Pages::where('id', $page_id)->where('user_id', Auth::id())->first();//Pages::find($page_id);

        $sch_post = self::$fb->postStatus($page, $message, '');

        return ($sch_post);
    }
    
    public static function postPhotoToFB($page_id, $full_picture_url, $status)
   {
       self::checkFB();        
       $page = Pages::where('id', $page_id)->where('user_id', Auth::id())->first();//Pages::find($page_id);
       
       $data = array();
       $data['full_picture'] = $full_picture_url;
       $data['status'] = $status;

       $sch_post =  self::$fb->postImage($page, $data);
//        echo '<pre>----';
//        print_r($sch_post);
       return $sch_post;
   }
   

   public static function postVideoToFB($page_id, $video_url, $status)
   {
       self::checkFB();        
       $page = Pages::where('id', $page_id)->where('user_id', Auth::id())->first();//Pages::find($page_id);
       
       $data = array();
       $data['source'] = $video_url;
       $data['status'] = $status;

       $sch_post =  self::$fb->postVideo($page, $post);
//        echo '<pre>----';
//        print_r($sch_post);
       return $sch_post;
   }


/*POST TO FACEBOOK ENDS*/

/*POST TO TWITTER STARTS*/

   public static function  postLinkToTwitter($page_id, $link_url, $status)
   {
        $twitter = Twitters::where('user_id', Auth::id())->first();
        Twitter::reconfig([
                                'consumer_key' => $twitter->consumer_key,
                                'consumer_secret' => $twitter->consumer_secret,
                                'token' => $twitter->access_token,
                                'secret' => $twitter->access_token_secret,
                        ]);
        $twitter_status = $status . PHP_EOL . $link_url;
        try {
               return Twitter::postTweet(['status' => $twitter_status, 'format' => 'json']);
        } 
        catch (Exception $e)
        {
            return 'error|' . $e->getMessage();
        }
        return 1;
   }
    
    public static function postStatusToTwitter($page_id, $message)
    {
        echo "postStatusToTwitter";
  	$twitter = Twitters::where('user_id', Auth::id())->first();
        Twitter::reconfig([
                                'consumer_key' => $twitter->consumer_key,
                                'consumer_secret' => $twitter->consumer_secret,
                                'token' => $twitter->access_token,
                                'secret' => $twitter->access_token_secret,
                        ]);
        $twitter_status = $message;
        try {
               return Twitter::postTweet(['status' => $twitter_status, 'format' => 'json']);
        } 
        catch (Exception $e)
        {
            return 'error|' . $e->getMessage();
        }
        return 1;
    }
    
    public static function postPhotoToTwitter($page_id, $full_picture_url, $status)
   {
       echo "postPhotoToTwitter";
       
       $twitter = Twitters::where('user_id', Auth::id())->first();
        Twitter::reconfig([
                                'consumer_key' => $twitter->consumer_key,
                                'consumer_secret' => $twitter->consumer_secret,
                                'token' => $twitter->access_token,
                                'secret' => $twitter->access_token_secret,
                        ]);
        try {

                $uploaded_media = Twitter::uploadMedia(['media' => @file_get_contents($full_picture_url)]);
                return Twitter::postTweet(['format' => 'json', 'status' => $status, 'media_ids' => $uploaded_media->media_id_string]);

        } catch (Exception $e)
        {
           return 'error|' . $e->getMessage();
        }
        return 1;
   }
   

   public static function postVideoToTwitter($page_id, $video_url, $status)
   {
       echo 'postVideoToTwitter';
       $twitter = Twitters::where('user_id', Auth::id())->first();
        Twitter::reconfig([
                                'consumer_key' => $twitter->consumer_key,
                                'consumer_secret' => $twitter->consumer_secret,
                                'token' => $twitter->access_token,
                                'secret' => $twitter->access_token_secret,
                        ]);
        try {

        $cut_video_url = $video_url;
        //echo '<pre>';
        //echo $cut_video_url;
        $post['source'] = $cut_video_url;
        print_r($post);

        $post['source'] = str_replace("&", "||", $post['source']);

        $twitter_api_url = url('/ffmpeg/twitter_video_upload.php?');
        $twitter_api_url .= "tw_key=".$twitter->consumer_key."&";
        $twitter_api_url .= "tw_secret=".$twitter->consumer_secret."&";
        $twitter_api_url .= "tw_token=".$twitter->access_token."&";
        $twitter_api_url .= "tw_token_secret=".$twitter->access_token_secret."&";
        $twitter_api_url .= "v_url=".$post['source']."&";
        $twitter_api_url .= "status=".urlencode($status);

        //$instagram_api_url = ($instagram_api_url);
        echo '==='.$twitter_api_url.'<br/>';
        //exit();
        //$content_x = file_get_contents($instagram_api_url);
        $ch = curl_init($twitter_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        $content_x = curl_exec($ch);
        curl_close($ch);

        //echo '<br/>===='.$content_x;
        $arx = json_decode($content_x);
        //echo '<pre>';
        //print_r($arx);
        try
        {
            if($arx->status == 'fail')
            {
                echo 'error|' . $arx->message;
            }
        }
        catch(Exception $e){}


        } catch (Exception $e)
        {

                return 'error|v|' . $e->getMessage();

        }
        return 1;
   }

/*POST TO TWWITTER ENDS*/

/*POST TO INSTAGRAM STARTS*/

   public static function  postVideoToInstagram($page_id, $cut_video_url, $pic_url, $status)
   {
        echo 'postVideoToInstagram';
        $instagram_user = Instagrams::where('user_id', Auth::id())->first();
        $instagram = InstagramHelper::getInstance($instagram_user->username, $instagram_user->password);
        //$cut_video_url = Input::get('cut_video_url');
        //echo '<pre>';
        //echo $cut_video_url;
        $post['source'] = $cut_video_url;
        //print_r($post);
        //exit();

        $post['full_picture'] = str_replace("&", "||", $pic_url);
        $post['source'] = str_replace("&", "||", $post['source']);

        $instagram_api_url = url('/iitest/upload.php?');
        $instagram_api_url .= "uxname=".$instagram_user->username."&";
        $instagram_api_url .= "uxpwd=".$instagram_user->password."&";
        $instagram_api_url .= "thmbx=".$post['full_picture']."&";
        $instagram_api_url .= "vidx=".$post['source']."&";
        $instagram_api_url .= "statusx=".urlencode($status);

        //$instagram_api_url = ($instagram_api_url);
        echo '==='.$instagram_api_url.'<br/>';
        //$content_x = file_get_contents($instagram_api_url);
        $ch = curl_init($instagram_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        $content_x = curl_exec($ch);
        curl_close($ch);

        echo '<br/>===='.$content_x;
        $arx = json_decode($content_x);
        //echo '<pre>';
        //print_r($arx);
        try
        {
            if($arx->status == 'fail')
            {
                echo 'error|' . $arx->message;
            }
        }
        catch(Exception $e){}
        return 1;
   }
    
    public static function postPhotoToInstagram($page_id, $full_picture_url, $status)
   {
        $instagram_user = Instagrams::where('user_id', Auth::id())->first();
        $instagram = InstagramHelper::getInstance($instagram_user->username, $instagram_user->password);
        echo '<pre>';
        //print_r($post);

        $post['full_picture'] = str_replace("&", "||", $full_picture_url);

        $instagram_api_url = url('/iitest/upload_photo.php?');
        $instagram_api_url .= "uxname=".$instagram_user->username."&";
        $instagram_api_url .= "uxpwd=".$instagram_user->password."&";
        $instagram_api_url .= "thmbx=".$post['full_picture']."&";
        $instagram_api_url .= "statusx=".urlencode($status);

        //$instagram_api_url = ($instagram_api_url);
        echo '==='.$instagram_api_url.'<br/>';
        //$content_x = file_get_contents($instagram_api_url);
        $ch = curl_init($instagram_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        $content_x = curl_exec($ch);
        curl_close($ch);

        echo '<br/>===='.$content_x;
        $arx = json_decode($content_x);
        //echo '<pre>';
        //print_r($arx);
        try
        {
            if($arx->status == 'fail')
            {
                echo 'error|' . $arx->message;
            }
        }
        catch(Exception $e){}
                                                  
       echo 'postPhotoToInstagram';
       return 1;
   }
   
   
   
   public static function postLinkToInstagram($page_id, $full_picture_url, $status)
   {
        $instagram_user = Instagrams::where('user_id', Auth::id())->first();
        $instagram = InstagramHelper::getInstance($instagram_user->username, $instagram_user->password);
        echo '<pre>';
        //print_r($post);

        $post['full_picture'] = str_replace("&", "||", $full_picture_url);

        $instagram_api_url = url('/iitest/upload_photo.php?');
        $instagram_api_url .= "uxname=".$instagram_user->username."&";
        $instagram_api_url .= "uxpwd=".$instagram_user->password."&";
        $instagram_api_url .= "thmbx=".$post['full_picture']."&";
        $instagram_api_url .= "statusx=".urlencode($status);

        //$instagram_api_url = ($instagram_api_url);
        echo '==='.$instagram_api_url.'<br/>';
        //$content_x = file_get_contents($instagram_api_url);
        $ch = curl_init($instagram_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        $content_x = curl_exec($ch);
        curl_close($ch);

        echo '<br/>===='.$content_x;
        $arx = json_decode($content_x);
        //echo '<pre>';
        //print_r($arx);
        try
        {
            if($arx->status == 'fail')
            {
                echo 'error|' . $arx->message;
            }
        }
        catch(Exception $e){}
                                                  
       echo 'postLinkToInstagram';
       return 1;
   }
   

	/*POST TO INSTAGRAM ENDS*/


}

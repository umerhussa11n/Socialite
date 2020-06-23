<?php 
/**
* 
*/



class SocialHelper
{
	private static $fb;
	private static $fb_error;


  private static $debugx = false;
  
  
  public static function getTimeFromUserToServer($user_time)
    {
        $user = Auth::user();
        $triggerOn = $user_time;
        $user_tz = $user->timezone;

        //echo 'FROM : '.$triggerOn.'<br/>'; // echoes 04/01/2013 03:08 PM

        $schedule_date = new DateTime($triggerOn, new DateTimeZone($user_tz) );
        $schedule_date->setTimeZone(new DateTimeZone('CST'));
        $triggerOn =  $schedule_date->format('H:i');

        //echo 'TO : '.$triggerOn; // echoes 2013-04-01 22:08:00
        return $triggerOn; // echoes 2013-04-01 22:08:00
    }
    
    
    public static function getTimeFromServerToUser($server_time)
    {
        $user = Auth::user();
        $triggerOn = $server_time;
        $user_tz = 'CST';

        //echo 'FROM : '.$triggerOn.'<br/>'; // echoes 04/01/2013 03:08 PM

        $schedule_date = new DateTime($triggerOn, new DateTimeZone($user_tz) );
        $schedule_date->setTimeZone(new DateTimeZone($user->timezone));
        $triggerOn =  $schedule_date->format('H:i');

        //echo 'TO : '.$triggerOn; // echoes 2013-04-01 22:08:00
        return $triggerOn; // echoes 2013-04-01 22:08:00
    }

    public static function checkFB($user_id)
    {

//ob_end_clean();


		self::$fb_error = false;
		

$user = User::find($user_id);



$app_id = $user->app_id;
        $app_secret = $user->app_secret;

		$fb = true;
		try {	
			$fb = new FacebookHelper($app_id, $app_secret);

		} catch (Exception $e) {

echo '<pre>';
            print_r($e);

			self::$fb_error = true;				
		}		
		self::$fb = $fb;
    }

/*POST TO FACEBOOK STARTS*/
   public static function  postLinkToFB($user_id, $page_id, $link_url, $status, $full_picture_url)
   {
        $str = "postLinkToFB - ".$page_id." - ".$link_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }

       self::checkFB($user_id);        
       $page = Pages::where('id', $page_id)->where('user_id', $user_id)->first();//Pages::find($page_id);

       //print_r($page);exit();
       $status = urldecode($status);
       $data = array();
        $data['status'] = $status;

        if(trim($full_picture_url) != '')
        {
            $data['full_picture'] = $full_picture_url;
            $data['status'] = $link_url.'   '.$status;

            $sch_post =  self::$fb->postImage($page, $data);
        }
        else
        {
            $data['link'] = $link_url;
            $sch_post = self::$fb->postLink($page, $data);
        }




       
      // print_r($sch_post);
       return $sch_post;
   }
    
    public static function postStatusToFB($user_id, $page_id, $message, $full_picture_url)
    {

        $str = "postStatusToFB - ".$page_id." - ".$message."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }

        self::checkFB($user_id);        
        $page = Pages::where('id', $page_id)->where('user_id', $user_id)->first();//Pages::find($page_id);
$message = urldecode($message);



if(trim($full_picture_url) != '')
        {
            $data['full_picture'] = $full_picture_url;
            $data['status'] = $message;

            $sch_post =  self::$fb->postImage($page, $data);
        }
        else
        {
            $sch_post = self::$fb->postStatus($page, $message, '');
        }



        

        return ($sch_post);
    }
    
    public static function postPhotoToFB($user_id, $page_id, $full_picture_url, $status)
   {

       $str = "postPhotoToFB - ".$page_id." - ".$full_picture_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }


       self::checkFB($user_id);        
       $page = Pages::where('id', $page_id)->where('user_id', $user_id)->first();//Pages::find($page_id);
       $status = urldecode($status);
       $data = array();
       $data['full_picture'] = $full_picture_url;
       $data['status'] = $status;

       $sch_post =  self::$fb->postImage($page, $data);
//        echo '<pre>----';
//        print_r($sch_post);
       return $sch_post;
   }
   

   public static function postVideoToFB($user_id, $page_id, $video_url, $status)
   {

      $str = "postVideoToFB - ".$page_id." - ".$video_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }


       self::checkFB($user_id);        
       $page = Pages::where('id', $page_id)->where('user_id', $user_id)->first();//Pages::find($page_id);
       $status = urldecode($status);
       $data = array();
       $data['source'] = $video_url;
       $data['status'] = $status;

       $sch_post =  self::$fb->postVideo($page, $data);
//        echo '<pre>----';
//        print_r($sch_post);
       return $sch_post;
   }


/*POST TO FACEBOOK ENDS*/

/*POST TO TWITTER STARTS*/

   public static function  postLinkToTwitter($user_id, $page_id, $link_url, $status, $full_picture_url)
   {

        $str = "postLinkToTwitter - ".$page_id." - ".$link_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }

        $twitter = Twitters::where('id', $page_id)->first();
        Twitter::reconfig([
                                'consumer_key' => $twitter->consumer_key,
                                'consumer_secret' => $twitter->consumer_secret,
                                'token' => $twitter->access_token,
                                'secret' => $twitter->access_token_secret,
                        ]);
        $status = urldecode($status);
        $twitter_status = $status . PHP_EOL . $link_url;


        if(trim($full_picture_url) != '')
        {
                try {

                        $uploaded_media = Twitter::uploadMedia(['media' => @file_get_contents($full_picture_url)]);
                        return Twitter::postTweet(['format' => 'json', 'status' => $twitter_status, 'media_ids' => $uploaded_media->media_id_string]);

                } catch (Exception $e)
                {
                   return 'error|' . $e->getMessage();
                }
        }
        else
        {
                try {
                       return Twitter::postTweet(['status' => $twitter_status, 'format' => 'json']);
                } 
                catch (Exception $e)
                {
                    return 'error|' . $e->getMessage();
                }
        }


        return 1;
   }
    
    public static function postStatusToTwitter($user_id, $page_id, $message, $full_picture_url)
    {
      
      $str = "postStatusToTwitter - ".$page_id." - ".$message."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }

  	$twitter = Twitters::where('id', $page_id)->first();
        Twitter::reconfig([
                                'consumer_key' => $twitter->consumer_key,
                                'consumer_secret' => $twitter->consumer_secret,
                                'token' => $twitter->access_token,
                                'secret' => $twitter->access_token_secret,
                        ]);
        $message = urldecode($message);
        $twitter_status = $message;



        if(trim($full_picture_url) != '')
        {
                try {

                        $uploaded_media = Twitter::uploadMedia(['media' => @file_get_contents($full_picture_url)]);
                        return Twitter::postTweet(['format' => 'json', 'status' => $twitter_status, 'media_ids' => $uploaded_media->media_id_string]);

                } catch (Exception $e)
                {
                   return 'error|' . $e->getMessage();
                }
        }
        else
        {
                try {
                    return Twitter::postTweet(['status' => $twitter_status, 'format' => 'json']);
                } 
                catch (Exception $e)
                {
                    return 'error|' . $e->getMessage();
                }
        }


        
        return 1;
    }

    
    public static function postPhotoToTwitter($user_id, $page_id, $full_picture_url, $status)
   {
      $str = "postPhotoToTwitter - ".$page_id." - ".$full_picture_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }
       $status = urldecode($status);
       $twitter = Twitters::where('id', $page_id)->first();
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
   

   public static function postVideoToTwitter($user_id, $page_id, $video_url, $status)
   {
       
        $str = "postVideoToTwitter - ".$page_id." - ".$video_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }
$status = urldecode($status);
       $twitter = Twitters::where('id', $page_id)->first();
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

   public static function  postVideoToInstagram($user_id, $page_id, $cut_video_url, $pic_url, $status)
   {
        
        $str = "postVideoToInstagram - ".$page_id." - ".$cut_video_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }
$status = urldecode($status);

        $instagram_user = Instagrams::where('id', $page_id)->first();
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
    
    public static function postPhotoToInstagram($user_id, $page_id, $full_picture_url, $status)
   {
        $str = "postPhotoToInstagram - ".$page_id." - ".$full_picture_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }
$status = urldecode($status);
        $instagram_user = Instagrams::where('id', $page_id)->first();
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
   
   
   
   public static function postLinkToInstagram($user_id, $page_id, $full_picture_url, $status)
   {

        $str = "postLinkToInstagram - ".$page_id." - ".$full_picture_url." - ".$status."<br/>";
        if(self::$debugx)
        {
            echo $str;
            return;
        }
        else
        {
            echo $str;
        }
$status = urldecode($status);
        $instagram_user = Instagrams::where('id', $page_id)->first();
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

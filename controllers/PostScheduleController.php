<?php

class PostScheduleController extends \BaseController {
    
    
    
    
    
    public function test()
    {
        
        
		
	echo SocialHelper::getTimeFromServerToUser("00:39");
        echo SocialHelper::getTimeFromUserToServer("08:39");
        exit();
        // echo '----';
        // $tweet = TwitterHelper::getTweet('927225407346216961');
        // exit();

        //SocialHelper::postLinkToTwitter('NO_USE', "http://google.com", "New Test");
        //SocialHelper::postStatusToTwitter('NO_USE', "Status New Test");
        //SocialHelper::postPhotoToTwitter('NO_USE', "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRbeCcTzT3iPgaDbi6p1q6rg33knZRrLprUOOO_xP1RuKx1KZf", "Status New Test Photo");
        //SocialHelper::postVideoToTwitter('NO_USE', "http://social.softsengine.com/ffmpeg/downloads/5a01e4dc5693f_cut.mp4", "Status New Test Video");
        
        
        //SocialHelper::postLinkToInstagram('NO_USE', "https://www.looktracker.com/wp-content/uploads/2014/06/Choose-Eye-Tracking-User-Test-Website-URL.png", "http://yahoo.com");
        //SocialHelper::postPhotoToInstagram('NO_USE', "https://www.looktracker.com/wp-content/uploads/2014/06/Choose-Eye-Tracking-User-Test-Website-URL.png", "Status New Test Photo");
//        SocialHelper::postVideoToInstagram('NO_USE', 
//                "http://social.softsengine.com/ffmpeg/downloads/5a01e4dc5693f_cut.mp4", 
//                "https://www.looktracker.com/wp-content/uploads/2014/06/Choose-Eye-Tracking-User-Test-Website-URL.png",
//                "Status New Test Video");
        
        exit();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{      
            $sch_id =Input::get('del_sch_id');
            // // $sch_id = $_REQUEST['del_sch_id'];
            // echo '<pre>';
            // print_r($sch_id);
            // exit();

            if( isset ($sch_id)) {
                $sch = Tblpostscheduler::find($sch_id);
                if( count ($sch) > 0)
                {
                    $sch->delete();    
                }
                
            }


            $current_social_media = '';
            
            $page_meta = array();
            if(isset($_REQUEST['tw_page_id']))
            {
                $tw_page_id = $_REQUEST['tw_page_id'];
                $twitters = Twitters::where('id', $tw_page_id)->get();
                $twitter = $twitters[0];
                
                $cover = empty($twitter->cover_image) ? URL::to('assets/img/page-background.jpg') : $twitter->cover_image;
                
                $page_meta['s_media'] = 'tw_page_id';
                $page_meta['page_id'] = $tw_page_id;
                $page_meta['cover'] = $cover;
                $page_meta['profile_image'] = $twitter->profile_image;
                $page_meta['name'] = $twitter->name;
                $page_meta['screen_name'] = '('.$twitter->screen_name.')';
                $page_meta['description'] = $twitter->description;
                
                $page_meta['is_twitter'] = true;
                $page_meta['is_facebook'] = false;
                $page_meta['is_instagram'] = false;
                
                $current_social_media = 'T';
            }
            
            if(isset($_REQUEST['fb_page_id']))
            {
                $fb_page_id = $_REQUEST['fb_page_id'];
                $ourPages = Pages::where('id', $fb_page_id)->get();
                $page = $ourPages[0];
                
                if(! strlen($page->cover) < 7){
                        $cover = $page->cover;
                } else {
                        $cover = URL::to('assets/img/page-background.jpg');
                }
                
                $page_meta['s_media'] = 'fb_page_id';
                $page_meta['page_id'] = $fb_page_id;
                $page_meta['cover'] = $cover;
                $page_meta['profile_image'] = 'https://graph.facebook.com/'.$page->page_id.'/picture';
                $page_meta['name'] = $page->name;
                $page_meta['screen_name'] = '';
                $page_meta['description'] = $page->description;
                
                
                $page_meta['is_twitter'] = false;
                $page_meta['is_facebook'] = true;
                $page_meta['is_instagram'] = false;
                
                $current_social_media = 'F';
                
            }
            
            if(isset($_REQUEST['inst_page_id']))
            {
                $inst_page_id = $_REQUEST['inst_page_id'];
                $instagrams = Instagrams::where('id', $inst_page_id)->get();
                $instagram = $instagrams[0];
                
                $cover = URL::to('assets/img/page-background.jpg');
                
                $page_meta['s_media'] = 'inst_page_id';
                $page_meta['page_id'] = $inst_page_id;
                $page_meta['cover'] = $cover;
                $page_meta['profile_image'] = $instagram->profile_image;
                $page_meta['name'] = $instagram->full_name;
                $page_meta['screen_name'] = '('.$instagram->username.')';
                $page_meta['description'] = '';
                
                $page_meta['is_twitter'] = false;
                $page_meta['is_facebook'] = false;
                $page_meta['is_instagram'] = true;
                
                $current_social_media = 'I';
            }

            
            $library_id = -1;
            if(isset($_REQUEST['lib_id']))
            {            
                $library_id = $_REQUEST['lib_id'];
            }
            
            $user_id = Auth::id();  
            if(true || $library_id == -1)
            {
                 
                $sql = "select m.*, a.color_code from tbl_scheduler m inner join 
                       tbl_library a on m.library_id = a.id
                      where m.page_id='".$page_meta['page_id']."' AND "
                        . "m.social_media_flag = '".$current_social_media."' AND a.user_id='".$user_id."' "
                        . "ORDER BY STR_TO_DATE(m.schedule_time,'%T') ASC";
                
                //echo $sql;exit();
                
               $res = DB::select(DB::raw($sql));
               //echo '<pre>';
               $schedules = ($res);
              //  echo '=====';exit();
            }
            else 
            {
                $sql = "select m.*, a.color_code from tbl_scheduler m inner join 
                       tbl_library a on m.library_id = a.id  
                      where m.library_id = '".$library_id."' AND m.page_id='".$page_meta['page_id']."' "
                        . "AND m.social_media_flag='".$current_social_media."' "
                        . "ORDER BY STR_TO_DATE(m.schedule_time,'%T') ASC";
                
                //echo $sql;exit();
                
               $res = DB::select(DB::raw($sql));
               //echo '<pre>';
               $schedules = ($res);
                //print_r(['page_id' => $page_meta['page_id'], 'library_id' => $library_id, 'social_media_flag' => $current_social_media]);
               // $schedules = Tblpostscheduler::where(['page_id' => $page_meta['page_id'], 'library_id' => $library_id, 'social_media_flag' => $current_social_media])->get();
            }
            
            $finalx = array();
            foreach($schedules as $k => $v)
            {
                $usr_time = SocialHelper::getTimeFromServerToUser($v->schedule_time);
                $schedules[$k]->schedule_time = $usr_time;
                
                $finalx[$usr_time] = $schedules[$k];
            }
            
            ksort($finalx);
            
            
            $schedules = $finalx;
            
//            echo '<pre>';
//            print_r($schedules);
//            exit();
            
            //$library = Tbllibrary::where('user_id',$user_id)->get();

            $library = Tbllibrary::where('user_id',$user_id)->lists('title','id');
            return View::make('postschedule.index',compact('page_meta', 'library', 'schedules','library_id'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
                
//                echo '<pre>';
//                print_r($input);
//                exit();
		
		$social = implode(', ', $input['social_media_flag']);
		if($input['day'] == '')
		{
			$input['day'] = '-1';
		}
              

        $postscheduler = new Tblpostscheduler();	
		$postscheduler->library_id = $input['library_id'];
		$postscheduler->frequency = $input['frequency'];
		$postscheduler->week_day = $input['days'];
		$postscheduler->month_day = $input['day'] + 1;
		$postscheduler->schedule_time = SocialHelper::getTimeFromUserToServer($input['time']);
		$postscheduler->social_media_flag = $social;
                $postscheduler->page_id = $input['page_id'];
                
                
                $slot_available = true;
                if($postscheduler->frequency == 'd' || $postscheduler->frequency == 'm')
                {
                    $msql_v = "select m.*, a.title from tbl_scheduler m "
                            . "inner join tbl_library a on m.library_id = a.id where "
                            . "m.social_media_flag = '".$postscheduler->social_media_flag."' AND"
                        . " m.page_id = '".$postscheduler->page_id."' "
                        . " AND m.schedule_time = STR_TO_DATE('".$postscheduler->schedule_time.":00','%T')";
                    //echo $msql_v; exit();
                    $result_v = DB::select($msql_v);
                    
                    if(count($result_v) > 0)
                    {
                        $slot_available = false;
                        Session::flash('errorx', 'This timeslot has been occupied by library - <b>'.$result_v[0]->title.'</b>');
                    }
                }
                else
                if($postscheduler->frequency == 'w')
                {
                    $msql_v = "select m.*, a.title from tbl_scheduler m "
                            . "inner join tbl_library a on m.library_id = a.id where "
                            . "m.social_media_flag = '".$postscheduler->social_media_flag."' AND"
                            . " ((m.frequency = 'd' OR m.frequency='w') AND m.week_day = '".$postscheduler->week_day."') AND "
                        . " m.page_id = '".$postscheduler->page_id."' "
                        . " AND m.schedule_time = STR_TO_DATE('".$postscheduler->schedule_time.":00','%T')";
                    //echo $msql_v; exit();
                    $result_v = DB::select($msql_v);
                    
                    if(count($result_v) > 0)
                    {
                        $slot_available = false;
                        Session::flash('errorx', 'This timeslot has been occupied by library - <b>'.$result_v[0]->title.'</b>');
                    }
                }
                


		if(!$slot_available)
                {
                    
                    $jst_saved = 'w';
                    if($input['frequency'] == 'm')
                    {
                        $jst_saved = 'm';
                    }
                    return Redirect::to('postschedule?no_slot=1&'.$input['s_media'].'='.$input['page_id'].'&cal_type='.$jst_saved.'&lib_id='.$input['lib_id']);
                }
                else
                {
                            $postscheduler->save();
                            Session::flash('success', 'Library Successfully Created');
                                // redirect

                            $jst_saved = 'w';
                            if($input['frequency'] == 'm')
                            {
                                $jst_saved = 'm';
                            }

                            return Redirect::to('postschedule?'.$input['s_media'].'='.$input['page_id'].'&cal_type='.$jst_saved.'&lib_id='.$input['lib_id']);
                }
                
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function postCron()
	{

ob_start();

 echo '<pre>=======';
 
$msql = 'select tbl_scheduler.*, '
                        . 'tbl_library.user_id as user_id from tbl_scheduler '
                        . 'INNER JOIN tbl_library ON '
                        . 'tbl_scheduler.library_id = tbl_library.id where '
                        . 'tbl_library.is_active = ?';


		$result = DB::select($msql, array(1));
                
                //print_r($result);
		
		$final = array();

		foreach($result as $v)
		{	
			$r = $this->getToPostLibrary($v);
                        print_r($r);

			if(count($r) > 0)
			{
                            
                                $r->user_id = $v->user_id;
                                
//                                if($v->last_posted_post == 0)
//                                {
//                                    $r->orderx = 0;
//                                }
//                                else
//                                {
//                                    $result_post = DB::select("SELECT * FROM tbl_post where id = '".$v->last_posted_post."'");
//                                    //print_r($result_post);
//                                    foreach($result_post as $fkf => $fvf)
//                                    {
//                                        $r->orderx = $fvf->_order;
//                                    }
//                                }
                                
				$final[] = $r;
                
			}
		}


//
//
 //print_r($final);

 //exit();
 




		
		$list = array();
        foreach ($final as $fk => $fv) {
            $test_key = '';
            $reset_scheduler_id = $fv->id;

            /*$last_sql = "SELECT * FROM `tbl_scheduler` WHERE "
                . "STR_TO_DATE(`schedule_time`,'%T') < STR_TO_DATE('" . $fv->schedule_time . "','%T') "
                . "AND page_id='" . $fv->page_id . "' and library_id='" . $fv->library_id . "' order by "
                . "STR_TO_DATE(`schedule_time`,'%T') DESC limit 0,1";

            $res = DB::select(DB::raw($last_sql));
            $last_posted_post_id = 0;
            if (count($res) > 0) {
                $last_posted_post_id = ($res[0]->last_posted_post);
            } else //YO ELSE ADD GAREKO
            {
                $last_sql = "SELECT * FROM `tbl_scheduler` WHERE "
                    . "STR_TO_DATE(`schedule_time`,'%T') = STR_TO_DATE('" . $fv->schedule_time . "','%T') "
                    . "AND page_id='" . $fv->page_id . "' and library_id='" . $fv->library_id . "' order by "
                    . "STR_TO_DATE(`schedule_time`,'%T') DESC limit 0,1";
                $res = DB::select(DB::raw($last_sql));
                if (count($res) > 0) {
                    $last_posted_post_id = ($res[0]->last_posted_post);
                }
            }*/

            $last_sql = "SELECT * FROM `tbl_scheduler` WHERE page_id='" . $fv->page_id . "' and library_id='" . $fv->library_id . "' and social_media_flag = '" . $fv->social_media_flag . "' order by "
                . "STR_TO_DATE(`schedule_time`,'%T') DESC limit 0,1";

            $res = DB::select(DB::raw($last_sql));
            $last_posted_post_id = 0;
            if (count($res) > 0) {
                $last_posted_post_id = ($res[0]->last_posted_post);
            }

            /*if ($fv->library_id == 48) {
                print "\r\n\r\nLast posted: " . $last_posted_post_id . "\r\n";
            }*/

            if ($last_posted_post_id == '0') {
                $last_sql = "SELECT * FROM `tbl_post` WHERE library_id = '" . $fv->library_id . "' ORDER BY _order ASC limit 0,1";
                $res = DB::select(DB::raw($last_sql));
            } else {
                $last_sql = "SELECT * FROM `tbl_post` WHERE _order > (SELECT _order FROM tbl_post WHERE id = '" . $last_posted_post_id . "') "
                    . " AND library_id = '" . $fv->library_id . "' ORDER BY _order ASC limit 0,1";
                $res = DB::select(DB::raw($last_sql));
                if (empty($res[0])) {
                    $last_sql = "SELECT * FROM `tbl_post` WHERE _order > 0 AND library_id = '" . $fv->library_id . "' ORDER BY _order ASC limit 0,1";
                    $res = DB::select(DB::raw($last_sql));
                }
                /*if ($fv->library_id == 48) {
                    $v = $res[0];
                    print $last_sql . "\r\n";
                    print $v->id."\r\n";
                }*/
            }
//print_r($post_to_post);
//continue;

                    
			//$post_list = Tblpost::where('library_id',$fv->library_id)->orderBy('_order')->get();			
			//foreach($post_list as $k => $v)

//                            if($fv->last_posted_post == $v->id)
//                            {
//                                
//                                continue;
//                            }
//                            
//                            if($fv->last_posted_post > 0)
//                            {
//                                if($fv->orderx >= $v->_order)
//                                {
//
//                                    continue;
//                                }
//                            }

            if (!empty($res)) {
                $v = $res[0];
                $tmp = array();
                $tmp['post_id'] = $v->id;
                $tmp['scheduler_id'] = $fv->id;
                $tmp['library_id'] = $fv->library_id;

                $tmp['type'] = $v->type;
                $tmp['message'] = $v->message;
                $tmp['link_url'] = $v->link_url;

                                $tmp['photo_url'] = $v->photo_url;
                                $tmp['video_url'] = $v->video_url;
                                $tmp['video_len_x'] = $v->video_len_x;
                                $tmp['post_to'] = $fv->social_media_flag;

                                 $tmp['post_to_page'] = $fv->page_id;

                                 $tmp['user_id'] = $fv->user_id;
                                 
                if (!isset($list[$tmp['post_to'] . "_" . $tmp['post_to_page'] . "_" . $tmp['post_id']])) {
                    $test_key = $tmp['post_to'] . "_" . $tmp['post_to_page'] . "_" . $tmp['post_id'];
                    $list[$test_key] = $tmp;
                }

                if (!isset($list[$test_key])) {
                    $sqlx = "UPDATE tbl_scheduler SET last_posted_post = '0' WHERE library_id = '" . $fv->library_id . "' AND page_id='" . $fv->page_id . "'";
                    DB::statement($sqlx);
                } /*else {
                    if ($fv->library_id == 48) {
                        $sqlx = "UPDATE tbl_scheduler SET last_posted_post = '" . $tmp['post_id'] . "' WHERE id='" . $tmp['scheduler_id'] . "'";
                        print $sqlx . "\r\n";
                        DB::statement($sqlx);
                    }
                }
                if ($fv->library_id == 48) {
                    print_r($tmp);
                }*/
            }
            unset($res);
        }


        // print_r($list);
        //exit();


 
       


foreach($list as $lv)
{
    if($lv['post_to'] == 'T')
    {

        if($lv['type'] == 'status')
        {
            SocialHelper::postStatusToTwitter($lv['user_id'], $lv['post_to_page'], $lv['message'], $lv['photo_url']);
        }
        else
        if($lv['type'] == 'link')
        {
            SocialHelper::postLinkToTwitter($lv['user_id'], $lv['post_to_page'], $lv['link_url'],  $lv['message'], $lv['photo_url']);
        }
        else
        if($lv['type'] == 'photo')
        {
            SocialHelper::postPhotoToTwitter($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
        }
        else
        if($lv['type'] == 'video')
        {
            if($lv['video_len_x'] > 0 && $lv['video_len_x'] <= 30)
            {
                SocialHelper::postVideoToTwitter($lv['user_id'], $lv['post_to_page'], $lv['video_url'],  $lv['message']);
            }
            else
            {
                echo '<br/>Twitter video must be cut...to 30 secs.<br/>';
            }
        }
        else
        if($lv['type'] == 'custom')
        {
            if(trim($lv['photo_url']) == '')
            {
                    SocialHelper::postStatusToTwitter($lv['user_id'], $lv['post_to_page'], $lv['message']);
            }
            else
            {
                    SocialHelper::postPhotoToTwitter($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
            }
        }
    }
    else
    if($lv['post_to'] == 'F')
    {
        if($lv['type'] == 'status')
        {
            SocialHelper::postStatusToFB($lv['user_id'], $lv['post_to_page'], $lv['message'], $lv['photo_url']);
        }
        else
        if($lv['type'] == 'link')
        {
            SocialHelper::postLinkToFB($lv['user_id'], $lv['post_to_page'], $lv['link_url'],  $lv['message'], $lv['photo_url']);
        }
        else
        if($lv['type'] == 'photo')
        {
            SocialHelper::postPhotoToFB($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
        }
        else
        if($lv['type'] == 'video')
        {
            SocialHelper::postVideoToFB($lv['user_id'], $lv['post_to_page'], $lv['video_url'],  $lv['message']);
        }
        else
        if($lv['type'] == 'custom')
        {
            if(trim($lv['photo_url']) == '')
            {
                    SocialHelper::postStatusToFB($lv['user_id'], $lv['post_to_page'], $lv['message']);
            }
            else
            {
                    SocialHelper::postPhotoToFB($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
            }
        }
    }
    else
    if($lv['post_to'] == 'I')
    {

        if($lv['type'] == 'link')
        {
            SocialHelper::postLinkToInstagram($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
        }
        else
        if($lv['type'] == 'photo')
        {
            SocialHelper::postPhotoToInstagram($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
        }
        else
        if($lv['type'] == 'video')
        {
            if($lv['video_len_x'] > 0 && $lv['video_len_x'] <= 60)
            {
                SocialHelper::postVideoToInstagram($lv['user_id'], $lv['post_to_page'], $lv['video_url'], $lv['photo_url'],  $lv['message']);
            }
            else
            {
                echo '<br/>Instagram video must be cut...to 60 secs.<br/>';
            }
            
        }
        else
        if($lv['type'] == 'custom' || $lv['type'] == 'status')
        {
            if(trim($lv['photo_url']) == '')
            {
                    SocialHelper::postLinkToInstagram($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
            }
            else
            {
                    SocialHelper::postPhotoToInstagram($lv['user_id'], $lv['post_to_page'], $lv['photo_url'],  $lv['message']);
            }
        }
    }
    
        
    //$sqlx = "UPDATE tbl_scheduler SET last_posted_post = '".$lv['post_id']."' WHERE id='".$lv['scheduler_id']."'";
    $sqlx = "UPDATE tbl_scheduler SET last_posted_post = '" . $lv['post_id'] . "' WHERE library_id = '" .$lv['library_id'] . "' AND page_id='" . $lv['post_to_page'] . "'  and social_media_flag = '" . $lv['post_to'] . "'";
    //echo '<br/>==========='.$sqlx.'<br/>';
    DB::statement($sqlx);
    
}

exit();

		
	}




	public function getToPostLibrary($obj)
	{

//$sql = "SELECT * FROM `tbl_scheduler` WHERE id = '".$obj->id."'";
//echo '<br/>'.$sql .'<br/>';
//				 $res = DB::select($sql);
//				$res = array_shift($res);
//				return $res;

//$now_str = date('Y-m-d h:i:s');

        $now_str = 'NOW()';

			if($obj->frequency == 'd')
			{

                $sql = "SELECT * FROM `tbl_scheduler` WHERE (UNIX_TIMESTAMP($now_str) >= UNIX_TIMESTAMP(concat(CURDATE(),' ',schedule_time)) AND (UNIX_TIMESTAMP($now_str) - (15 * 60)) < UNIX_TIMESTAMP(concat(CURDATE(),' ',schedule_time))) AND id = '".$obj->id."'";
//echo '<br/>'.$sql .'<br/>';
				 $res = DB::select($sql);
				$res = array_shift($res);
				return $res;

			}
			if($obj->frequency == 'm')
			{
                $sql = "SELECT * FROM `tbl_scheduler` WHERE (UNIX_TIMESTAMP($now_str) >= UNIX_TIMESTAMP(concat(CURDATE(),' ',schedule_time)) AND (UNIX_TIMESTAMP($now_str) - (15 * 60)) < UNIX_TIMESTAMP(concat(CURDATE(),' ',schedule_time))) AND week_day = DAYOFWEEK(NOW()) AND month_day = DAYOFMONTH(NOW()) AND id = '".$obj->id."'";
               //echo '<br/>'.$sql .'<br/>';
				 $res = DB::select($sql);
				 $res = array_shift($res);
					return $res;

			}
			if($obj->frequency == 'w')
			{

                $sql = "SELECT * FROM `tbl_scheduler` WHERE (UNIX_TIMESTAMP($now_str) >= UNIX_TIMESTAMP(concat(CURDATE(),' ',schedule_time)) AND (UNIX_TIMESTAMP($now_str) - (15 * 60)) < UNIX_TIMESTAMP(concat(CURDATE(),' ',schedule_time))) AND week_day = DAYOFWEEK(NOW()) AND id = '".$obj->id."'";

                //echo '<br/>'.$sql .'<br/>';

				 $res = DB::select($sql);
				 $res = array_shift($res);
					return $res;

			}
			return;
	}



    function _get_video_attributes($video) {
            
                    $command = 'ffmpeg -i "' . $video . '" -vstats 2>&1';
                    $output = shell_exec($command);

                    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; // or : $regex_sizes = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; (code from @1owk3y)
                    if (preg_match($regex_sizes, $output, $regs)) {
                        $codec = $regs [1] ? $regs [1] : null;
                        $width = $regs [3] ? $regs [3] : null;
                        $height = $regs [4] ? $regs [4] : null;
                    }

                    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
                    if (preg_match($regex_duration, $output, $regs)) {
                        $hours = $regs [1] ? $regs [1] : null;
                        $mins = $regs [2] ? $regs [2] : null;
                        $secs = $regs [3] ? $regs [3] : null;
                        $ms = $regs [4] ? $regs [4] : null;
                    }

                    return array('codec' => $codec,
                        'width' => $width,
                        'height' => $height,
                        'hours' => $hours,
                        'mins' => $mins,
                        'secs' => $secs,
                        'ms' => $ms
                    );
                }

	public function recordPost()
    {


    	$social_media = Input::get('social_media');
    	$type 		  = Input::get('type');
    	$message	  = Input::get('status');
    	$cut_video_url = Input::get('cut_video_url');
    	// $link_url     = Input::get('link_url');
    	// $photo_url 	  = Input::get('photo_url');
    	// $video_url 	  = Input::get('video_url');
    	$library_id 	  = Input::get('library_id');
    	$post_id 	  = Input::get('post_id');

$video_len_x = Input::get('video_len_x');
        if($video_len_x == null)
        {
            $video_len_x = 0;
        }

      
    	

        if($social_media == 't')
        {
            $tweet = TwitterHelper::getTweet($post_id);
            // echo '===========<pre>';
            // print_r($tweet);
            //  exit();
            
            if($type == 'link')
            {
                    $l =  '';
                    $p = (isset($tweet['photo']))?$tweet['photo']:'';
                    $v = (isset($tweet['video']))?$tweet['video']:'';
            }
            else
            if($type == 'status')
            {
                    $l =  '';
                    $p = (isset($tweet['photo']))?$tweet['photo']:'';
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
                        $finalvx = $this->_get_video_attributes($v);
                        $video_len_x= $finalvx['mins'] * 60 + $finalvx['secs'];
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
            $tblpost->_order = time();

$tblpost->video_len_x = $video_len_x;
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
                        $finalvx = $this->_get_video_attributes($v);
                        $video_len_x= $finalvx['mins'] * 60 + $finalvx['secs'];
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
$tblpost->_order = time();
$tblpost->video_len_x = $video_len_x;
             // echo '<pre>'; 
             // print_r($tblpost);
             // exit();

            $tblpost->save();
    	}
    	else
        if($social_media == 'f')
        {
        	$this->checkFB();

        	if($type  == 'link')
        	{
        		$post = $this->fb->getPostInfo($post_id, 'link');
        		$l =  $post['link'];
        		$p = $post['full_picture'];
        		$v = '';

        	}
        	elseif($type == 'status')
        	{
        		$post = $this->fb->getPostInfo($post_id, 'status');
//        		echo '<pre>========';
//                 print_r($post);
//                 exit();
                        
        		$l =  '';
        		$p = isset($post['full_picture'])?$post['full_picture']:'';
        		$v = isset($post['source'])?$post['source']:'';

        	}
        	elseif($type == 'video'){
        		$post = $this->fb->getPostInfo($post_id, 'video');
                //echo '<pre>========';
                // print_r($post);
                // exit();

        		$l =  '';
        		$p = $post['full_picture'];
        		if(trim($cut_video_url) != '')
                        {
                            $v = $cut_video_url;
                        }
                        else
                        {
                            $v = $post['source'];
                        }
        	}
        	else
        	{
        		$post = $this->fb->getPostInfo($post_id, 'photo');
        		$l =  '';
        		$p = $post['full_picture'];
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
$tblpost->_order = time();
$tblpost->video_len_x = $video_len_x;
        	// echo '<pre>'; 
        	// print_r($tblpost);
        	// exit();

        	$tblpost->save();

        	//return Redirect::to('postschedule');
        }
    }






	private $fb;
	private $fb_error;

    public function checkFB()
    {
		$this->fb_error = false;
		$app_id = Auth::user()->app_id;
		$app_secret = Auth::user()->app_secret;
		$fb = true;
		try {	
			$fb = new FacebookHelper($app_id, $app_secret);

		} catch (Exception $e) {

			$this->fb_error = true;				
		}		
		$this->fb = $fb;
    }


}

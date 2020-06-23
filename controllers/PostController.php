<?php

class PostController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index($lid)
	{
		$lib = Tbllibrary::where('id',$lid)->first();
		$lib_title = $lib -> title;
		$post = Tblpost::where('library_id',$lid)->orderBy('_order')->get();
		return View::make('library.post.index',compact('lid','post','lib_title'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($lid)
	{
		// $post = array('photo_url'=>'', 'link_url'=>'','video_url'=>'');



		// $post = (object) $post;
		// echo '<pre>';
		// print_r($post);
		// exit();


           $library = Tbllibrary::where('id',$lid)->first();
	   return View::make('library.post.create',compact('library', 'lid', 'post'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($lid)
	{
		$social_media = Input::get('social_media');
		$type = Input::get('type');
		$message = Input::get('message');
		$photo_url = Input::get('photo_url');
		
		$post = new Tblpost();	
		$post->social_media = $social_media;
		$post->type = $type;
		$post->message = urlencode($message);

        $filesize_limit = 102400; // 100 MB 
        $rules = array();
		if(Input::hasFile('photo_url'))
		{
			$rules['photo_url'] = 'max:'.$filesize_limit.'|mimes:jpg,jpeg,png,gif,bmp, m4v,avi,flv,mp4,mov,qt';
            $validator = Validator::make(Input::all(), $rules);
            if ( $validator->fails() )
            {
                return Redirect::back()->withErrors($validator);
            }
            else
            {
                $file = Input::file('photo_url');
                $destinationPath = 'uploads';
                $destinationPathUser = $destinationPath.'/'.Auth::id();
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); 
                $upload_success = $file->move($destinationPathUser, $filename);
                if ($upload_success)
                {
                    $photo_url = url($destinationPathUser).'/'.$filename;
                }
                else
                {
                    Session::flash('error', 'Something Goes Wrong');
                    return Redirect::back();
                }
            }
        }
        
		$post->photo_url = $photo_url;
		$post->library_id = $lid;
        $post->_order = time();
		$post->save();
        
		Session::flash('success', 'Library Successfully Created');
	            // redirect
		return Redirect::to('library/'.$lid.'/post');
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
	public function edit($lid,$id)
	{
		$library = Tbllibrary::where('id',$lid)->first();
		$post = Tblpost::find($id);
		$post->message = urldecode($post->message);
		// echo '<pre>';
  //   	print_r($post);
  //   	exit();
		return View::make('library.post.edit',compact('library','lid','post'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($lid,$id)
	{
		$message = Input::get('message');
		$photo_url = Input::get('photo_url');
		
		$post = Tblpost::find($id);
		$post->message = $message;
		$post->photo_url = $photo_url;
		if($post->type != 'custom')
		{
			$post->link_url = Input::get('link_url');
			$post->video_url = Input::get('video_url');
		}
		//$post->library_id = $lid;
		$post->save();


		Session::flash('success', 'Library Successfully Created');
	            // redirect
		return Redirect::to('library/'.$lid.'/post');
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

	public function reorderPost()
	{

		$library_id = Input::get('library_id');
    	$post_ids 		  = Input::get('post_ids');
    	
    	// $post =  new Tblpost();
    	$oind = 1;
    	foreach($post_ids as $pk=>$pv)
    	{
    		$post = Tblpost::find($pv);
    		$post->_order = $oind;
    		$post->save();
    		$oind++;
    	}
	}
	public function deletePost($lid,$id)
	{
		
		$post = Tblpost::find($id);
		$post->delete();

		return Redirect::to('library/'.$lid.'/post');
	}


}

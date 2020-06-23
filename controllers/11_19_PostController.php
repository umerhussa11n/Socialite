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
           $library = Tbllibrary::where('id',$lid)->first();
	   return View::make('library.post.create',compact('library', 'lid'));
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
		$media_url = Input::get('media_url');
		
		$post = new Tblpost();	
		$post->social_media = $social_media;
		$post->type = $type;
		$post->message = $message;
		$post->photo_url = $media_url;
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
		$post = Tblpost::find($id);
		return View::make('library.post.edit',compact('lid','post'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($lid,$id)
	{
		$social_media = Input::get('social_media');
		$type = Input::get('type');
		$message = Input::get('message');
		$media_url = Input::get('media_url');
		
		$post = Tblpost::find($id);
		$post->social_media = $social_media;
		$post->type = $type;
		$post->message = $message;
		$post->media_url = $media_url;
		$post->library_id = $lid;
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
    	// echo '<pre>';
    	// print_r($post_ids);
    	// exit();
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

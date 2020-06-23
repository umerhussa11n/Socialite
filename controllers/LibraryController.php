<?php


class LibraryController extends \BaseController {

		public function showLibrary(){   
			$user_id = Auth::id();    
			$library = Tbllibrary::where('user_id',$user_id)->get();
                        $res = array();
			foreach($library as $lk => $lv) {
				
				$post = Tblpost::where('library_id',$lv->id)->get();
				$post_count = count($post);

				$lib = array();
				$lib['id'] = $lv->id;
				$lib['title'] = $lv->title;
				$lib['is_active'] = $lv->is_active;
				$lib['is_one_time'] = $lv->is_one_time;
				$lib['color_code'] = $lv->color_code;
				$lib['post_count'] = $post_count;
				$res[] = $lib;
				}

			return View::make('library.index',compact('res'));

		}

		public function getAddLibrary(){
			return View::make('library.add');
		}

		public function saveLibrary(){

			$title = Input::get('title');
			$is_one_time = Input::get('is_one_time');
			$color_code = Input::get('color_code');
			if($is_one_time == '')
			{
				$is_one_time = '0';
			}
			else {
				$is_one_time = '1';
			}

			$user_id = Auth::id();

			$library = new Tbllibrary();	
			$library->title = $title;
			$library->is_active = '1';
			$library->is_one_time = $is_one_time;
			$library->user_id = $user_id;
			$library->color_code = $color_code;
                        
		
			$library->save();
			Session::flash('success', 'Library Successfully Created');
	            // redirect
			return Redirect::to('library');
		}

		public function editLibrary($id)
		{
			$user_id = Auth::id();  
			$library = Tbllibrary::where(['user_id' => $user_id, 'id' => $id])->first();
			return View::make('library.edit',compact('library'));
		}

		public function updateLibrary($id)
		{
			
			$is_one_time = Input::get('is_one_time');
				if($is_one_time == '')
			{
				$is_one_time = '0';
			}
			else {
				$is_one_time = '1';
			}
			 

			 $library = Tbllibrary::find($id);
			 $library->title = Input::get('title');
			 $library->is_one_time = $is_one_time;
			 $library->color_code = Input::get('color_code');
			 $library->save();

			return Redirect::to('library')->with('message', 'Library Successfully Updated');
		}
		public function playPauseToggle($id)
		{
			$library = Tbllibrary::find($id);

			if($library->is_active == '1')
			{
				$val = '0';
			}
			else{
				$val = '1';
			}
			 $library->is_active = $val;
			 $library->save();

			return Redirect::to('library');

       }
       public function duplicate($id)
		{
			$user_id = Auth::id();
			$library_org = Tbllibrary::find($id);
			$library = new Tbllibrary();	
			$library->title = $library_org->title;
			$library->is_active = $library_org->is_active;
			$library->is_one_time = $library_org->is_one_time;
			$library->user_id = $user_id;
			$library->save();

			return Redirect::to('library')->with('success', 'Successfully Duplicated');

       }
       public function delete($id)
       {
	       	$post = Tblpost::where('library_id',$id);
	       	if(count ($post) > 0) {
	       		$post->delete();
	       	}
	       	$scheduler = Tblpostscheduler::where('library_id',$id);
	       	if(count ($scheduler) > 0) {
	       		$scheduler->delete();
	       	}
       		$lib = Tbllibrary::find($id);
       		$lib->delete();
       		return Redirect::to('library')->with('success', 'Successfully Deleted');

       }
}


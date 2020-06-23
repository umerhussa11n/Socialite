<div class="buttons-holder">
	<div class="post-details">
		Post type : <strong> &nbsp; <i class="fa fa-file-photo-o"></i> &nbsp;Photo</strong>
	</div>
	<?php 
		$message = "";
		if (isset($post->message)) {
			$message = $post->message;
		} else if(isset($post->name)) {
			$message = $post->name;
		}
		
		if (!strpos($post->id, '_'))
		{
			$post->id = $follow_page_id . '_' . $post->id;
		}
	 ?>  
	<a href="http://facebook.com/{{$post->id}}" target="_blank"><button class="btn btn-block btn-primary"><strong><i class="fa fa-external-link"></i></strong> &nbsp;View Post </button></a>
	<button class="btn btn-block btn-primary" data-post-id="{{$post->id}}" onclick="post_content('{{$post->id}}','photo')" data-status-message="{{$message}}" style="margin-top:4px"><strong><i class="fa fa-image"></i></strong> &nbsp;Post Photo</button>    	
</div>
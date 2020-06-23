<div class="buttons-holder">
	<div class="post-details">
		Post type : <strong> &nbsp; <i class="fa  fa-pencil"></i> &nbsp;Status</strong>
	</div>
	<a href="http://facebook.com/{{$post->id}}" target="_blank"><button class="btn btn-block btn-primary"><strong><i class="fa fa-external-link"></i></strong> &nbsp;View Post </button></a>    
	<?php 
		$message = "";
		if (isset($post->message)) {
			$message = $post->message;
		} else if(isset($post->name)) {
			$message = $post->name;
		}
	 ?>  
	<button class="btn btn-block btn-primary" data-post-id="{{$post->id}}" onclick="post_content('{{$post->id}}','status')" data-status-message="{{$message}}" style="margin-top:4px"><strong><i class="fa fa-external-link"></i></strong> &nbsp;Post Status </button>
</div>
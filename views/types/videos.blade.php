<div class="col-lg-4">        		
	@foreach ($partition[0] as $post)
	<?php $post_id = explode('_', $post->id);  ?>
	<div class="post-holder">		
    	<div class="fb-post" data-href="http://facebook.com/{{$post->from->id}}/posts/{{$post->id}}" data-width="300"></div>
    	<div class="on-top">  
    		@include('ontop.videos')
    	</div>
	</div>
	@endforeach
</div>    
<div class="col-lg-4">        	
	@foreach ($partition[1] as $post)		        	
	<?php $post_id = explode('_', $post->id);  ?>
	<div class="post-holder">		
    	<div class="fb-post" data-href="http://facebook.com/{{$post->from->id}}/posts/{{$post->id}}" data-width="300"></div>
    	<div class="on-top">  
    		@include('ontop.videos')
    	</div>
	</div>
	@endforeach
</div>
<div class="col-lg-4">      
	@foreach ($partition[2] as $post)	
	<?php $post_id = explode('_', $post->id);  ?>
	<div class="post-holder">		
    	<div class="fb-post" data-href="http://facebook.com/{{$post->from->id}}/posts/{{$post->id}}" data-width="300"></div>
    	<div class="on-top">  
    		@include('ontop.videos')
    	</div>
	</div>
	@endforeach
</div>
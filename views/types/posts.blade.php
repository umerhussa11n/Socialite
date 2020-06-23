
    @foreach ($partition[0] as $post)
	<?php 
  
		if (!isset($post->status_type) || strstr($post->status_type, 'status') || strstr($post->status_type, 'shared_story')) {
			continue;
		}
		$post_id = explode('_', $post->id); 
	?>
	<div class="post-holder grid-item" >
    	<div class="fb-post" data-href="http://facebook.com/{{$post_id[0]}}/posts/{{$post_id[1]}}" data-width="300"></div>
    	<div class="on-top">
    		<?php if($post->status_type == 'shared_story'){ ?>
            @include('ontop.links')
            <?php } else if($post->status_type == 'added_video'){ ?>
            @include('ontop.videos')
            <?php } else if($post->status_type == 'added_photos'){ ?>
            @include('ontop.photos')
            <?php } else if($post->status_type == 'mobile_status_update'){ ?>
            @include('ontop.statuses')
            <?php } ?>
    	</div>
        <div class="float: left; clear: both;"></div>
	</div>
	@endforeach

	@foreach ($partition[1] as $post)
	<?php

		if (!isset($post->status_type) || strstr($post->status_type, 'status') || strstr($post->status_type, 'shared_story')) {
			continue;
		}
		$post_id = explode('_', $post->id);
	?>
    <div class="post-holder grid-item" >
    	<div class="fb-post" data-href="http://facebook.com/{{$post_id[0]}}/posts/{{$post_id[1]}}" data-width="300"></div>
    	<div class="on-top">
            <?php if($post->status_type == 'shared_story'){ ?>
            @include('ontop.links')
            <?php } else if($post->status_type == 'added_video'){ ?>
            @include('ontop.videos')
            <?php } else if($post->status_type == 'added_photos'){ ?>
            @include('ontop.photos')
            <?php } else if($post->status_type == 'mobile_status_update'){ ?>
            @include('ontop.statuses')
            <?php } ?>
        </div>
        <div class="float: left; clear: both;"></div>
	</div>
	@endforeach

	@foreach ($partition[2] as $post)
	<?php

		if (!isset($post->status_type) || strstr($post->status_type, 'status') || strstr($post->status_type, 'shared_story')){
			continue;
		}
		$post_id = explode('_', $post->id);
	?>
    <div class="post-holder grid-item" >
    	<div class="fb-post" data-href="http://facebook.com/{{$post_id[0]}}/posts/{{$post_id[1]}}" data-width="300"></div>
    	<div class="on-top">
            <?php if($post->status_type == 'shared_story'){ ?>
            @include('ontop.links')
            <?php } else if($post->status_type == 'added_video'){ ?>
            @include('ontop.videos')            
            <?php } else if($post->status_type == 'added_photos'){ ?>
            @include('ontop.photos')            
            <?php } else if($post->status_type == 'mobile_status_update'){ ?>
            @include('ontop.statuses')            
            <?php } ?>
        </div>   
        <div class="float: left; clear: both;"></div> 		
	</div>
	@endforeach


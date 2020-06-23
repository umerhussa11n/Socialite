<div class="col-lg-4">			
	@foreach ($partition[0] as $post)
	<div class="post-holder">
	    <div class="fb-post" data-href="{{$post->link}}" data-width="300"></div>
	    <div class="on-top">  
	    	@include('ontop.photos')
	    </div>
	</div>	        		
	@endforeach
</div>
<div class="col-lg-4">	
	@foreach ($partition[1] as $post)		        		
	<div class="post-holder">
	    <div class="fb-post" data-href="{{$post->link}}" data-width="300"></div>
	    <div class="on-top">  
	    	@include('ontop.photos')
	    </div>
	</div>	        	
	@endforeach
</div>
<div class="col-lg-4">			
	@foreach ($partition[2] as $post)		
	<div class="post-holder">
	    <div class="fb-post" data-href="{{$post->link}}" data-width="300"></div>
	    <div class="on-top">  
	    	@include('ontop.photos')
	    </div>
	</div>	        	
	@endforeach
</div>
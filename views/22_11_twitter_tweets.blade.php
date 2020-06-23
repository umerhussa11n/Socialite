@if(empty($partition))
	<div class="col-lg-12">					
		<div class="alert alert-warning">
		  <strong>Something Wrong!</strong> Looks like this page is not published or No Available Posts.
		</div>
	</div>
@else 
	@foreach ($partition as $posts)
	<div class="col-lg-4">	        		
		@foreach ($posts as $post)
		<div class="post-holder">
			<div class="fb-post"><?php echo $post['embed'] ?></div>
			<div class="on-top">
				<div class="buttons-holder">
					<div class="post-details">
						Post type : <strong> &nbsp; <i class="fa fa-external-link"></i> &nbsp; {{ ucfirst($post['type']) }}</strong>
					</div>
					<a href="{{ $post['link'] }}" target="_blank"><button class="btn btn-block btn-primary"><strong><i class="fa fa-external-link"></i></strong> &nbsp;View Post </button></a>  		
					<button class="btn btn-block btn-primary" data-post-id="{{ $post['id'] }}" onclick="post_content('{{ $post['id'] }}','{{ $post['type'] }}')" data-status-message="{{ $post['text'] }}" style="margin-top:4px"><strong><i class="fa fa-external-link"></i></strong> &nbsp;Post {{ ucfirst($post['type']) }}</button>			   
				</div>
			</div>   		
		</div>
		@endforeach
	</div>
	@endforeach
@endif
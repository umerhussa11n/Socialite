@if($unpublished)
	<div class="col-lg-12">					
		<div class="alert alert-warning">
          <strong>Something Wrong!</strong> Looks like this page is not published or No Available Posts.
        </div>
	</div>
@else 
	@if ($type == 'posts')
		@include ('types.posts')
	@endif

	@if ($type == 'photos')
		@include ('types.photos')
	@endif

	@if ($type == 'statuses')
		@include ('types.statuses')
	@endif

	@if ($type == 'videos')
		@include ('types.videos')
	@endif

	@if ($type == 'links')
		@include ('types.links')
	@endif
@endif
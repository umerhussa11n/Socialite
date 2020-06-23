@extends('app')

@section('title', 'Post List')


@if(Session::has('success'))
	<div class="alert-box success">
		<h2>{{ Session::get('success') }}</h2>
	</div>
@endif

@section('main_content')
	<div class ="row">
		<div class="col-md-12 btn-row">
			<a href="{{url('library/'.$lid.'/post/create')}}" class="btn btn-primary">Add post</a>
		</div>
	</div><!-- /.row -->
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')
				
					<div class="row">
						<div class="col-md-3">
								<div class="card">
									<h5 class="card-header">{{$lib_title}}</h5>
									<div class="card-body">
										<p>
											<strong>Posts: </strong> <?php echo count($post); ?>
										</p>
									</div>
								</div>
						</div>
						<div class="col-md-9" id="posts_list">

							@foreach($post as $pk=>$pv)
								<div class="post-excerpt">
									@if($pv->type == 'video')
										<div class="media-left responsive-video">
											
											<video width="200" height="160" controls>
												<source src="{{$pv->video_url}}" type="video/mp4">
												Your browser does not support the video.
											</video>
										</div>
									@elseif($pv->type == 'photo')
										<div class="media-left">
											<img src="{{$pv->photo_url}}" width="200px" height="160px" alt="...">
										</div>
									@else
										<div class="media-left">
											
										</div>
									@endif
									
									<div class="media-body" style="overflow:visible;">
										<div class="dropdown pull-right dropdown-bars">
											<input type="hidden" class="post_order" value="{{$pv->id}}" />
										
											<button class="btn btn-primary" type="button" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i></button>
												<ul class="dropdown-menu menu-edited">


													<!-- <li><a href="{{url('library/'.$lid.'/post/'.$pv->id.'/edit')}}">Edit</a></li> -->

														<li><a href="{{url('library/'.$lid.'/post/'.$pv->id.'/delete')}}"  onclick="return confirm_delete()">Delete</a></li>
												</ul>
										</div>
										<p>
											{{urldecode($pv->message)}}
										</p>
									</div>
								</div>
							@endforeach
							
						</div>
					</div>


			</div>
		</div>
	</div>
@endsection

<script type="text/javascript">
	
	function doOrdering()
	{

		var lib_id = <?php echo "$lid"; ?>;
		var post_ids = Array();
		$('.post_order').each(function(k,v){
			post_ids.push($(v).val());

		});
		// console.log(post_ids);

		var url = '{{url("library/".$lid."/post/reorderpost")}}';
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				library_id: lib_id,
				post_ids: post_ids,
			},
			success: function (res) {

			}
		});

	}
	function confirm_delete() {
		return confirm('Are you sure want to delete?');
	}

</script>

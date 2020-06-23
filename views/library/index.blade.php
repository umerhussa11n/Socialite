@extends('app')



@section('title', 'Library List')





@if(Session::has('success'))

	<div class="alert-box success">

		<h2>{{ Session::get('success') }}</h2>

	</div>

@endif



@section('main_content')

	<div class ="row">

		<div class="col-md-12 btn-row">

			<a href="{{url('/library/add')}}" class="btn btn-primary">Add Library</a>

		</div>

	</div><!-- /.row -->

	<div class="box box-block bg-white">

		<div class="row">

			<div class="col-md-12">

				@include('message')

				<?php	

				if(count($res) <= 0)

				{

					?>

					<div class="library-block col-md-12">

						<div class="classWithPad">

							<div class="library-block-row">

								No Library Found

							</div>

						</div>

					</div>

					<?php

				}

				?>



				@foreach($res as $lv)

				<div class="library-block col-md-12">

					<div class="row">
							<div class="classWithPad" style="border-left: 30px solid <?php echo $lv['color_code'] ?>">

								<a class="delete_top_right" href="{{url('library/delete/')}}/{{$lv['id']}}" onclick="return confirm_delete()">X</a>
							<div class="row">
								<div class="col-md-6">
									<div class="library-block-text lib-col">
										<span class="library-title"><strong><a href="{{url('library/'.$lv['id'].'/post')}}">{{$lv['title']}}</a></strong></span>
										<p>{{$lv['post_count']}}&nbsp; Posts</p>
									</div>
								</div><!-- /col-md-6 -->
								
								<div class="col-md-6">
									<div class="library-block-btn">
										@if($lv['is_active'] == '1')

										<a href="{{url('library/playpausetoggle/')}}/{{$lv['id']}}" class="btn btn-danger "><i class="fa fa-pause" aria-hidden="true"></i>&nbsp;&nbsp;Pause</a>

										@else

										<a href="{{url('library/playpausetoggle/')}}/{{$lv['id']}}" class="btn btn-danger "><i class="fa fa-play" aria-hidden="true"></i>&nbsp;&nbsp;Play</a>

										@endif

										<a href="{{url('library/duplicate/')}}/{{$lv['id']}}" class="btn btn-info "><i class="fa fa-copy" aria-hidden="true"></i>&nbsp;&nbsp;Duplicate</a>


										<a href="{{url('library/edit/')}}/{{$lv['id']}}" class="btn btn-success "><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;Edit</a>

									</div><!-- /.library-block-btn -->
								</div><!-- /.col-md-6 -->
							</div><!-- /.row -->
							</div>
							
					</div>

					
				</div>	<!-- /library-block col-md-4 col-sm-6 col-xs-12 -->

			@endforeach

			</div> <!-- col-md-12 -->

		</div> <!-- /.row -->

	</div><!-- /.box-block bg-wite -->

@endsection



<script type="text/javascript">

	function confirm_delete() {

		return confirm('Are you sure want to delete?');

	}

</script>
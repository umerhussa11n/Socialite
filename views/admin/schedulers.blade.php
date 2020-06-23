@extends('app')

@section('title', 'Scheduled Posts List')

@section('main_content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')

				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
                                                        <th>Social Media</th>
							<th>Post Type</th>
							<th>Post ID</th>
                                                        <th>Status</th>
                                                        <th>Schedule Time</th>
							<th>Created Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($list as $user)
						<tr>
							<th scope="row">{{ $user->id }}</th>
                                                        <td>{{ $user->media_type_ }}</td>
							<td>{{ $user->post_type_ }}</td>
                                                        
                                                        <td>{{ $user->scheduled_ }}</td>
                                                        <td>{{ $user->status_ }}</td>
                                                        <td>
                                                            <?php
                                                            echo date("Y-m-d h:i:s", $user->sch_time_);
                                                            ?>
                                                        </td>
							<td>{{ $user->created_at }}</td>
							<td>
                                                            <a href="/schedules/delete/{{ $user->scheduled_ }}">
                                                                <button class="btn btn-xs btn-warning">Delete</button>
                                                            </a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				
				{{ $list->links() }}
			</div>
		</div>
	</div>
@endsection
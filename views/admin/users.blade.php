@extends('app')

@section('title', 'Users List')

@section('main_content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')
				<div style="margin-bottom: 10px">
					<a href="/admin/add-user">
						<button class="btn btn-primary">Add User</button>
					</a>
				</div>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>User Name</th>
							<th>Email</th>
							<th>App ID</th>
							<th>App Secret</th>
							<th>Chrome Key</th>
							<th>Time Zone</th>
							<th>Created Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr>
							<th scope="row">{{ $user->id }}</th>
							<td>{{ $user->name }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->app_id }}</td>
							<td>{{ $user->app_secret }}</td>
							<td>{{ $user->chrome_secret_key }}</td>
							<td>{{ $user->timezone }}</td>
							<td>{{ $user->created_at }}</td>
							<td>
								<a href="/admin/edit-user/{{ $user->id }}">
									<button class="btn btn-xs btn-warning">Modify</button>
								</a>
								&nbsp;
								@if($user->id != Auth::id())
								<a href="/admin/delete-user/{{ $user->id }}" onclick="return confirm('Are you sure you want to delete this user: {{ $user->name }} ?');">
									<button class="btn btn-xs btn-danger">Delete</button>
								</a>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				
				{{ $users->links() }}
			</div>
		</div>
	</div>
@endsection
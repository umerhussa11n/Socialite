@extends('app')

@section('title', 'Levels Settings')

@section('main_content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12">
				@include('message')
                
                <form class="" method="POST" action="{{ url('/admin/levels') }}">
                
                <div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							Level Platinum
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">
		                		<input class="form-control" type="text" style="max-width:350px" value="{{$level_platinum}}" id="level_platinum" name="level_platinum" />
		                	</div>
		                </div>
					</div>
				</div>
                
				<div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							Level Gold
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">
		                		<input class="form-control" type="text" style="max-width:350px" value="{{$level_gold}}" id="level_gold" name="level_gold" />
		                	</div>
		                </div>
					</div>
				</div>
                
                <div class="row">
					<div class="col-lg-2">
						<div class="form-group" style="text-align:center">
							
						</div>
					</div>					
					<div class="col-lg-10">						
		                <div class="settings-holder">
		                	<div class="form-group">                	
		                		<button type="submit" class="btn btn-primary">Save</button>
		                	</div>
		                </div>
					</div>
				</div>
                
                </form>

			</div>
		</div>
	</div>
@endsection

@section('js')
<script>	
jQuery(document).ready(function($) {
	
});
@endsection
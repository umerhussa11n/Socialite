@extends('app')

@section('title', 'Post List')


@if(Session::has('success'))
	<div class="alert-box success">
		<h2>{{ Session::get('success') }}</h2>
	</div>
@endif

@section('main_content')
<!--	<div class ="row">
		<div class="col-md-12 btn-row">
			<a href="#" class="btn btn-primary">Add </a>
		</div>
	</div> /.row -->
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						<h5>Add Timeslots</h5>
					</div>
					<div class="card-body">

						@include('postschedule.form')
					</div>
				</div>
			</div>
			<div class="col-md-8">
                            <div class="pages-list-holder">
                                <ul>
                                    <li class="page" style="background-image: url('{{$page_meta['cover']}}');background-size:cover">
                                            <div class="icon-holder">
                                                    <img src="{{$page_meta['profile_image']}}" alt="">
                                            </div>
                                            <div class="page-details">
                                                    <div class="page-name">{{$page_meta['name']}} {{ $page_meta['screen_name'] }}</div>
                                                    <div class="page-description">
                                                            <?php echo substr($page_meta['description'], 0,80); ?><?php echo (trim($page_meta['description']) != '')?'...':''; ?>
                                                    </div>
                                            </div>
                                    </li>
                                </ul>
                            </div>
                            
                            <table style="float: right;">
                                <tr>
                                    <td style="padding-right: 40px;">
                                        <input name="type_chooser" checked type="radio" value="w" />
                                        Weekly
                                    </td>
                                    <td>
                                        <input name="type_chooser" type="radio" value="m" />
                                        Monthly
                                    </td>
                                </tr>
                            </table>
				
                            <table id="will_appear_here" class="calendar">
                                <caption>Schedules</caption>
                                <tr class="weekdays" style="grid-template-columns: auto;">
                                    <th style="width: 100%;">
                                        Schedules will appear here....
                                    </th>
                                </tr>
                            </table>
					<!-- table starts -->

					<table id="calendar" class="calendar weekly_calendar">
						<tr>
							<td style="min-height: auto;display: table-cell; background-color:green; color: white; text-align:center;font-weight:bold;" colspan="7">
								Weekly
							</td>
    					</tr>
						<tr class="weekdays">
							<th scope="col">Sunday</th>
							<th scope="col">Monday</th>
							<th scope="col">Tuesday</th>
							<th scope="col">Wednesday</th>
							<th scope="col">Thursday</th>
							<th scope="col">Friday</th>
							<th scope="col">Saturday</th>
						</tr>

						<tr class="days">
							
							<td class="day Sunday">
								
							</td>

							<td class="day Monday">
								
							</td>

							<td class="day Tuesday">
								
							</td>

							<td class="day Wednesday">
								
							</td>

							<td class="day Thursday">
								
							</td>

							<td class="day Friday">
								
							</td>

							<td class="day Saturday">
								
							</td>
							
						</tr>


						</table>


					<!-- table ends -->
                                        @include('postschedule.monthtable')

			</div>
		</div>
	</div>
@endsection

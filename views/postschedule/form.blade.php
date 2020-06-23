<form method="POST" action="{{url("postschedule")}}" accept-charset="UTF-8" class="form" onsubmit="return validateSLForm();">    
    
    <input type="hidden" name="s_media" value="<?php echo $page_meta['s_media']; ?>" />
    <input type="hidden" name="page_id" value="<?php echo $page_meta['page_id']; ?>" />
    <input type="hidden" name="lib_id" value="<?php echo $library_id; ?>" />
    
    <?php
    if(Session::has('errorx'))
    {
        ?>
        <div class="form-group" style="background-color: #f2838f; border: 2px dashed red; padding: 5px; color: #000;">
            {{ Session::get('errorx') }}
        </div>
        <?php
    }
    ?>
    
    
    <div class="form-group">
            {{ Form::label('library_id', 'Library Title') }}
            {{ Form::select('library_id',['-1' => 'All Libraries'] + $library,$library_id,array('required','autofocus','class'=>'form-control', 'onchange' => 'refreshPageSchedule();')) }}
            @if ($errors->has('library_id'))
            <span class="alert-danger">
              <strong>{{ $errors->first('library_id') }}</strong>
            </span>
            @endif
      </div>

         <div class="form-group ohidden frequency">
            {{ Form::label('frequency', 'Frequency') }}
            {{ Form::select('frequency', array('d' => 'Daily', 'w' => 'Weekly','m' => 'Monthly'),null,array('required','autofocus','class'=>'form-control')) }}
            @if ($errors->has('frequency'))
            <span class="alert-danger">
              <strong>{{ $errors->first('frequency') }}</strong>
            </span>
            @endif
      </div>


     <div class="form-group ohidden" style="display: none;">
            {{ Form::label('days', 'Week Days') }}
            {{ Form::select('days', array('1' => 'Sunday', '2' => 'Monday','3' => 'Tuesday','4' => 'Wednesday','5' => 'Thursday','6' => 'Friday','7' => 'Saturday',),null,array('class'=>'form-control')) }}
            @if ($errors->has('days'))
            <span class="alert-danger">
              <strong>{{ $errors->first('days') }}</strong>
            </span>
            @endif
      </div>


     <div class="form-group ohidden" style="display: none;">
            {{ Form::label('day', 'Day') }}
            {{ Form::select('day', range(1,31),null,array('class'=>'form-control')) }}
            @if ($errors->has('day'))
            <span class="alert-danger">
              <strong>{{ $errors->first('day') }}</strong>
            </span>
            @endif
      </div>

    <div class="form-group ohidden time_">
            {{ Form::label('time', 'Time') }}
            <select class="form-control" id="time" name="time"><?php echo get_times(); ?></select>
      </div>

    <div style="display: none;" class="form-group">
              {{ Form::checkbox('social_media_flag[]','F',$page_meta['is_facebook']) }}
              {{ Form::label('f', 'Facebook') }}
              &nbsp;&nbsp;
              {{ Form::checkbox('social_media_flag[]','T',$page_meta['is_twitter']) }}
              {{ Form::label('t', 'Twitter') }}
              &nbsp;&nbsp;
              {{ Form::checkbox('social_media_flag[]','I',$page_meta['is_instagram']) }}
              {{ Form::label('i', 'Instagram') }}
    </div>



    {{ Form::submit('Save',array('class'=>'btn btn-sm btn-primary ohidden btns_')) }}
            
    <a href="{{url("pages-list")}}" class="btn btn-sm btn-danger ohidden btns_">Cancel</a>
    

{{ Form::close() }}
<?php

function get_times( $default = '19:00', $interval = '+15 minutes' ) {

    $output = '';

    $current = strtotime( '00:00' );
    $end = strtotime( '23:59' );

    while( $current <= $end ) {
        $time = date( 'H:i', $current );
        $sel = ( $time == $default ) ? ' selected' : '';

        $output .= "<option value=\"{$time}\"{$sel}>" . date( 'H.i', $current ) .'</option>';
        $current = strtotime( $interval, $current );
    }
    // $output .= "<option value='12:00'>12:00 PM</option>";

    return $output;
}


$weekdays = array(
    '1' => 'Sunday',
    '2' => 'Monday',
    '3' => 'Tuesday',
    '4' => 'Wednesday',
    '5' => 'Thursday',
    '6' => 'Friday',
    '7' => 'Saturday'
);
?>


<script type="text/javascript">

function refreshPageSchedule()
{
  if($('#library_id').val() != '-1')
  {
    
    document.location = '{{url("postschedule")}}?<?php echo $page_meta['s_media'].'='.$page_meta['page_id']; ?>&lib_id='+$('#library_id').val();
  }
  else
  {
      document.location = '{{url("postschedule")}}?<?php echo $page_meta['s_media'].'='.$page_meta['page_id']; ?>';
  }
  
}

    $(document).ready(function(){
        <?php
        if(count($schedules) > 0)
        {
            ?>
                 $('#will_appear_here').hide();
                 $('#calendar').show();
                 $('#calendar_monthly').css('visibility', 'hidden');
                 $('.frequency, .time_, .media_, .btns_').show();
            <?php
        }
        else
        {
            ?>
                 $('#will_appear_here').show();
                 $('#calendar').hide();
                 $('#calendar_monthly').css('visibility', 'hidden');
            <?php
        }
        
        if((isset($library_id) && $library_id == '-1'))
        {
            ?>
                    $('.frequency, .time_, .media_, .btns_').hide();
            <?php
        }
        else
        {
            ?>
                    $('.frequency, .time_, .media_, .btns_').show();
                    <?php
        }
        
        foreach($schedules as $s)
        {
//            echo '<pre>';
//            print_r($s);
            ?>
                    attachEventHTML('<?php echo $s->id; ?>', '<?php echo $s->frequency; ?>', $('#library_id option[value="<?php echo $s->library_id; ?>"]').text(), '<?php echo $s->schedule_time; ?>', '<?php echo $weekdays[$s->week_day]; ?>', '<?php //echo $s->social_media_flag; ?>', '<?php echo $s->month_day; ?>', '<?php echo $s->color_code; ?>');
                    <?php
        }
        ?>
                <?php
                if(isset($_REQUEST['cal_type']))
                {
                    ?>
                    toggleCalendar('<?php echo $_REQUEST['cal_type']; ?>');
                    <?php
                }
                ?>
                
    });
    
    
    
function deleteSchedule(sch_id, frequency)
{

  if( confirm('Are you sure want to delete?'))
  {

    document.location = '{{url("postschedule")}}?<?php echo $page_meta['s_media'].'='.$page_meta['page_id']; ?>&cal_type='+frequency+'&lib_id='+$('#library_id').val()+'&del_sch_id='+sch_id;
  }
}    
</script>
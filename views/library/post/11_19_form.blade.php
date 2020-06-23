<?php
//echo '<pre>';
//print_r($library);
?>

<div class="form-group">
          {{ Form::label('social_media', 'Adding to : ') }}
          <b><?php echo $library->title; ?></b>
          {{ Form::hidden('social_media','GVSDS',array('required','autofocus','class'=>'form-control')) }}
          {{ Form::hidden('library_id',$library->id,array('required','autofocus','class'=>'form-control')) }}
          {{ Form::hidden('type','custom',array('required','autofocus','class'=>'form-control')) }}
  </div>
  
   <div class="form-group">
          {{ Form::label('message', 'Message') }}
          {{ Form::textarea('message',null,array('autofocus','class'=>'form-control')) }}
          @if ($errors->has('message'))
          <span class="alert-danger">
              <strong>{{ $errors->first('message') }}</strong>
          </span>
          @endif
  </div>
  <div class="form-group">
          {{ Form::label('media_url', 'Media URL') }}
          {{ Form::text('media_url',null,array('required','autofocus','class'=>'form-control')) }}
          @if ($errors->has('media_url'))
          <span class="alert-danger">
              <strong>{{ $errors->first('media_url') }}</strong>
          </span>
          @endif
  </div>

  
 

    {{ Form::submit('Save',array('class'=>'btn btn-sm btn-primary')) }}
            
    {{ Form::button('Cancel',array('class'=>'btn btn-sm btn-danger', 'onclick' => 'history.go(-1)')) }}
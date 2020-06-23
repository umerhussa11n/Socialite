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
          {{ Form::label('photo_url', 'Photo URL') }}
          {{ Form::text('photo_url',null,array('class'=>'form-control')) }}
          @if ($errors->has('photo_url'))
          <span class="alert-danger">
              <strong>{{ $errors->first('photo_url') }}</strong>
          </span>
          @endif
  </div>

  @if( $post->type != 'custom')

      <div class="form-group">
        {{ Form::label('link_url', 'Link URL') }}
        {{ Form::text('link_url',null,array('class'=>'form-control')) }}
        @if ($errors->has('link_url'))
        <span class="alert-danger">
          <strong>{{ $errors->first('link_url') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group">
        {{ Form::label('video_url', 'Video URL') }}
        {{ Form::text('video_url',null,array('class'=>'form-control')) }}
        @if ($errors->has('video_url'))
        <span class="alert-danger">
          <strong>{{ $errors->first('video_url') }}</strong>
        </span>
        @endif
      </div>

  @endif


    {{ Form::submit('Save',array('class'=>'btn btn-sm btn-primary')) }}
            
    {{ Form::button('Cancel',array('class'=>'btn btn-sm btn-danger', 'onclick' => 'history.go(-1)')) }}
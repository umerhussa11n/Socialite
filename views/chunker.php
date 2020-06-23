<?php 
$video_url = 'http://social.softsengine.com/ffmpeg/test.mp4';

//$arr = _get_video_attributes($video_url);
//echo '<pre>';
//print_r($arr);
//
//$total_secs = $arr['mins'] * 60 + $arr['secs'];
//$max_secs = 30;
//echo $total_secs;



//echo shell_exec('ffmpeg  -y -i "/home/reviews/public_html/social-app/ffmpeg/ee.mp4" \
//        -ss 00:00:03 -t 00:00:08 -r 20 -s 640x360 -vb 400k \
//        -acodec aac -strict experimental -ac 1 -ar 8000 -ab 24k \
//        "/home/reviews/public_html/social-app/ffmpeg/new11_small.mp4"');
//exit();	
		
//echo shell_exec('ffmpeg -i "/home/reviews/public_html/social-app/ffmpeg/ee.mp4" -ss 00:00:03 -t 00:00:08 -async 1 -c copy cut.mp4 ');


    ?>


 
<script>



    var myPlayer, len;
    var error_ = true;




    
    function checkForError(len)
    {
        if(len > max_video_time_limit)
        {
            error_ = true;
            $('#len_erx').html('Video Cannot be more than '+max_video_time_limit+' seconds for '+social_media);
        }
        else
        {
            error_ = false;
            $('#len_erx').html('');
        }
    }
    
    
  $( function() {



    m_slider = $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: -1,
      values: [ 0, 30 ],
      slide: function( event, ui ) {
        $( "#from_" ).html(ui.values[ 0 ]);
        $( "#to_" ).html(ui.values[ 1 ]);
        len = ui.values[ 1 ] - ui.values[ 0 ];
        $( "#total_" ).html(len);
        
        checkForError(len);
        
        myPlayer.currentTime(ui.values[ 0 ]);
      }
    });
    
    $( "#from_" ).html($( "#slider-range" ).slider( "values", 0 ));
        $( "#to_" ).html($( "#slider-range" ).slider( "values", 1 ));
        $( "#total_" ).html($( "#slider-range" ).slider( "values", 1 ) - $( "#slider-range" ).slider( "values", 0 ));
        
        
        
    videojs("my-video").ready(function(){
            myPlayer = this;

            // EXAMPLE: Start playing the video.
            //myPlayer.play();
            
            //videojs.options.autoplay = true;
            
            myPlayer.currentTime(0);
            
//            
//            
//            myPlayer.timeOffset({
//                    start: 3,
//                    end: 10
//                  });


          });
        
    
    });
  </script>

  <div id="mainx" >
            <h1 id="fetch_notice">Fetching Video...Please Wait..</h1>
            <video id="my-video" class="video-js cutter-palette" controls preload="auto" width="640" height="264">
                <source src="<?php echo $video_url; ?>" type='video/mp4'>
                <p class="vjs-no-js">
                  To view this video please enable JavaScript, and consider upgrading to a web browser that
                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
            </video>  
            <table class="cutter-palette">
                <tr>
                    <td style="visibility: hidden;">From : </td><td style="visibility: hidden;" id="from_"></td>
                    <td style="visibility: hidden;">To : </td><td style="visibility: hidden;" id="to_"></td>
                    <td>Length : </td><td><span id="total_"></span> secs</td>
                </tr>
            </table>
            <div class="cutter-palette" id="slider-range"></div>
            <span class="cutter-palette" style="color: red;" id="len_erx"></span>
            <span style="color: blue;" id="link_msg" class="cutter-palette"></span>
            
                <table width="50%" style="width: 50%;">
                    <tr>
                        <td>
                            <button class="cutter-palette cancel_button" type="button" onclick="cancelCutter();" id="btn_cancel">CANCEL</button>
                        </td>
                        <td>
                            <button class="cutter-palette confirm_button" type="button" onclick="startCutting();" id="btn_cut">DONE</button>
                        </td>
                    </tr>
                </table>
            <div style="float: left; clear: both;"></div>
  </div>
  
  <style type="text/css">
      
      .confirm_button, .cancel_button {
            background-color: #8cd4f5;
            color: white;
            border: 0;
            box-shadow: none;
            font-size: 17px;
            font-weight: 500;
            -webkit-border-radius: 4px;
            border-radius: 5px;
            padding: 10px 32px;
            margin: 26px 5px 0 5px;
            cursor: pointer;
        }
        
        .cancel_button{
            background-color: #c1c1c1;
        }
      
      #len_erx{padding-top: 13px; padding-bottom: 0px; float: left;}
      #mainx{
             margin: auto;
    width: 480px;
    font-size: 12px;
    display: none;
    position: fixed;
    z-index: 999999999999;
    background-color: #fff;
    padding: 10px;
    left: 30%;
    top: 20%;
    
    border-radius: 6px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
      }
      .video-js{width: 100% !important; height: auto !important;}
      #mainx td#total_{padding-right: 0px;}
      #slider-range{float: left; width: 100%;}
      .my-video-dimensions{width: auto !important;}
      #mainx table{float: right; clear: both; width: 50%;}
      #mainx td{font-size: 12px; padding-right: 0px; padding-top: 10px; padding-bottom: 10px;}
      #btn_cut{
              display: block;
    float: right;
      }
      #link_msg{color: blue;
    display: block;
    padding-top: 5px;
    float: left; clear: both;}
      
      #my-video{height: 260px !important;}
      
      .preloader{
          background-color: #000 !important;
          opacity: 0.4;
      }
      .post-holder{float: left; margin: 10px;}
   
     
  </style>
  
  
  
  <script type="text/javascript">
      
      var cut_video_url = '';
      
      function cancelCutter()
      {
        $('.preloader, .sweet-overlay').hide();
        $('#mainx').hide();
      }
      
      function startCutting()
      {
          
          if(error_)
          {
              $('#link_msg').html("Please fix the error...");
              return;
          }
          
          x_from = $('#from_').html();
          x_to = $('#to_').html();
          x_len = $('#total_').html();
          v_url = current_v_url;
          
          $('#link_msg').html("Processing...Please wait....");
          
          $.ajax({
                method: "POST",
                url: cutter_base_url,
                data: { x_from: x_from, x_to: x_to, x_len: x_len, v_url: v_url }
              })
                .done(function( msg ) {
                    cut_video_url = msg;
                  //$('#link_msg').html(msg);
                  
                  cancelCutter();
                  initiateVideoPosting(v_idx, x_len);
                });
          
      }
      
      
      
      
    $(window).resize(adjustLayout);
    function adjustLayout(){
        $('#mainx').css({
            xposition:'absolute',
            left: ($(window).width() - $('#mainx').outerWidth())/2,
            top: ($(window).height())/2  - ($('#mainx').outerHeight() + 50)
        });

    }
      
      
      
      
      
      
      
  </script>
  
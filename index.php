<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AI Based Room Monitoring</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="loader.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aws-sdk/2.490.0/aws-sdk.min.js"></script>
        <link rel="icon" type="image/png" href="favicon.ico">

        <style>
            .switch {
              position: relative;
              display: inline-block;
              width: 60px;
              height: 34px;
            }
            .slider {
              position: absolute;
              cursor: pointer;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              background-color: #ccc;
              -webkit-transition: .4s;
              transition: .4s;
            }
            .slider:before {
              position: absolute;
              content: "";
              height: 26px;
              width: 26px;
              left: 4px;
              bottom: 4px;
              background-color: white;
              -webkit-transition: .4s;
              transition: .4s;
            }
            input:checked + .slider {
              background-color: black;
            }
            input:checked + .slider:before {
              -webkit-transform: translateX(26px);
              -ms-transform: translateX(26px);
              transform: translateX(26px);
            }
            /* Rounded sliders */
            .slider.round {
              border-radius: 34px;
            }

            .slider.round:before {
              border-radius: 50%;
            }
            #playerContainer,
            .player {
                width: 100%;
                height: auto;
                min-height: 400px;
                background-color: black;
                outline: none;
            }
            .vjs-big-play-button {
                display: none !important;
            }
            * {
                box-sizing: border-box;
            }

            body {
              font-family: Arial, Helvetica, sans-serif;
            }

            /* Style the header */
            header {
              background-color: #00CCCC;
              padding: 1px;
              text-align: center;
              font-size: 25px;
              color: white;
            }

            /* Create two columns/boxes that floats next to each other */

            nav {
              float: left;
              width: 30%;
              height: 500px;
               background:  #99CCFF;
              padding: 20px;
            }

            /* Style the list inside the menu */
            nav ul {
              list-style-type: none;
              padding: 10px;
            }

            article {
              float: left;
              padding: 20px;
              width: 70%;
              background-color: #CCFFE5;
              height: 500px; /* only for demonstration, should be removed */
            }

            /* Clear floats after the columns */
            section:after {
              content: "";
              display: table;
              clear: both;  
            }
            /* Responsive layout - makes the two columns/boxes stack on top of each other instead of next to each other, on small screens */
            @media (max-width: 600px) {
              nav, article {
                width: 100%;
                height: auto;
              }
            }
  </style>
  </head>
  <body>

    <div class="container mb-3">
            <div class="row loader"></div>
            <div class="main">
                <div class="row">
                    <div class="col-md-4" hidden>
                        <!--  -->
                        <div class="form-group">
                            <label>Player</label>
                            <select id="player" class="form-control form-control-sm">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Region</label>
                            <select id="region" class="form-control form-control-sm">
                                <option>ap-east-1</option>
                                <option>ap-northeast-1</option>
                                <option>ap-northeast-2</option>
                                <option>ap-south-1</option>
                                <option>ap-southeast-1</option>
                                <option>ap-southeast-2</option>
                                <option>ca-central-1</option>
                                <option>eu-central-1</option>
                                <option>eu-west-1</option>
                                <option>eu-west-2</option>
                                <option>eu-west-3</option>
                                <option>sa-east-1</option>
                                <option selected>us-east-1</option>
                                <option>us-east-2</option>
                                <option>us-west-2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>AWS Access Key</label>
                            <input id="accessKeyId" type="password" class="form-control form-control-sm"/>
                        </div>
                        <div class="form-group">
                            <label>AWS Secret Key</label>
                            <input id="secretAccessKey" type="password" class="form-control form-control-sm"/>
                        </div>
                        <div class="form-group">
                            <label>AWS Session Token (Optional)</label>
                            <input id="sessionToken" type="password" class="form-control form-control-sm" />
                        </div>
                        <div class="form-group">
                            <label>Endpoint (Optional)</label>
                            <input id="endpoint" type="text" class="form-control form-control-sm" />
                        </div>
                        <div class="form-group">
                            <label>Stream name</label>
                            <input id="streamName" type="text" class="form-control form-control-sm"/>
                        </div>
                        <div class="form-group">
                            <label>Playback Mode</label>
                            <select id="playbackMode" class="form-control form-control-sm">
                                <option selected>LIVE</option>
                                <option>LIVE_REPLAY</option>
                                <option>ON_DEMAND</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Start Timestamp</label>
                            <input id="startTimestamp" type="datetime-local" class="form-control form-control-sm"/>
                        </div>
                        <div class="form-group">
                            <label>End Timestamp</label>
                            <input id="endTimestamp" type="datetime-local" class="form-control form-control-sm"/>
                        </div>
                        <div class="form-group">
                            <label>Fragment Selector Type</label>
                            <select id="fragmentSelectorType" class="form-control form-control-sm">
                                <option >SERVER_TIMESTAMP</option>
                                <option selected>PRODUCER_TIMESTAMP</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Container Format</label>
                            <select id="containerFormat" class="form-control form-control-sm">
                                <option selected="">FRAGMENTED_MP4</option>
                                <option>MPEG_TS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Discontinuity Mode</label>
                            <select id="discontinuityMode" class="form-control form-control-sm">
                                <option selected>ALWAYS</option>
                                <option>NEVER</option>
                                <option>ON_DISCONTINUITY</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Display Fragment Timestamp</label>
                            <select id="displayFragmentTimestamp" class="form-control form-control-sm">
                                <option selected>ALWAYS</option>
                                <option>NEVER</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Display Fragment Number</label>
                            <select id="displayFragmentNumber" class="form-control form-control-sm">
                                <option>ALWAYS</option>
                                <option selected>NEVER</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Max Manifest/Playlist Fragment Results</label>
                            <input id="maxResults" type="text" class="form-control form-control-sm"/>
                        </div>
                        <div class="form-group">
                            <label>Expires (seconds)</label>
                            <input id="expires" type="text" class="form-control form-control-sm"/>
                        </div>
                    </div>
                </div>
          </div>
    </div>
<!-- heading -->
    <header>
      <center>
        <th> <I> <h1 style="color:white;font-size:100%;text-align:center">AI Based Room Monitoring</h1></I></th>
      </center>
    </header>
    <!-- date and day -->
    <BR>
    <div style="border: 1px solid black"<br> <h2><center><b>
      <div id="para1"></div>
    <script>
      document.getElementById("para1").innerHTML = formatAMPM();
      function formatAMPM(){
        var d = new Date(),
        minutes = d.getMinutes().toString().length == 1 ? '0'+d.getMinutes() : d.getMinutes(),
        hours = d.getHours().toString().length == 1 ? '0'+d.getHours() : d.getHours(),
        ampm = d.getHours() >= 12 ? 'pm' : 'am',
        months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        return days[d.getDay()]+' '+months[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear();
      }
    </script>
    </center></h2></div>
    <BR>
    <!--temp chart,gallery-->
    <article>
    <ul>
      <li><a href="graph.php">Temperature Chart</a></li>
      <!-- <li><a href="#">Gallery</a></li> -->
    </ul>
    <!-- live video -->
      <title>Live Streaming</title>
      <div class="col-md-8">
          <div id="playerContainer">
          <!-- HLS.js elements -->
          <video id="hlsjs" class="player" controls autoplay ></video>
          <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
     
      </div>
      <!-- <h3 style="margin-top: 20px;">Logs</h3> -->
      <div class="card bg-light mb-3">
        <button id="start" type="submit" class="btn btn-primary">Start Playback</button>
        <!-- <pre id="logs" class="card-body text-monospace" style="font-family: monospace; white-space: pre-wrap;"></pre> -->
      </div>
    <script>
            //var DASH_PLAYERS = ['DASH.js', 'Shaka Player'];
            var HLS_PLAYERS = ['HLS.js', 'Shaka Player', 'VideoJS'];

            $('#start').click(function() {
                var protocol = 'HLS';
                var streamName = 'pi-stream01';

                // Step 1: Configure SDK Clients
                var options = {
                    accessKeyId: 'AKIAXLBOL5Y6ALFE5WKM',
                    secretAccessKey: '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',
                    //sessionToken: $('#sessionToken').val() || undefined,
                    region: 'us-east-1',
                    //endpoint: $('#endpoint').val() || undefined
                }
                var kinesisVideo = new AWS.KinesisVideo(options);
                var kinesisVideoArchivedContent = new AWS.KinesisVideoArchivedMedia(options);

                // Step 2: Get a data endpoint for the stream
                console.log('Fetching data endpoint');
                kinesisVideo.getDataEndpoint({
                    StreamName: streamName,
                    APIName: protocol === 'DASH' ? "GET_DASH_STREAMING_SESSION_URL" : "GET_HLS_STREAMING_SESSION_URL"
                }, function(err, response) {
                    if (err) { return console.error(err); }
                    console.log('Data endpoint: ' + response.DataEndpoint);
                    kinesisVideoArchivedContent.endpoint = new AWS.Endpoint(response.DataEndpoint);

                    // Step 3: Get a Streaming Session URL
                    var consoleInfo = 'Fetching ' + protocol + ' Streaming Session URL';
                    console.log(consoleInfo);
                    
                    //hls
                    kinesisVideoArchivedContent.getHLSStreamingSessionURL({
                            StreamName: streamName,
                            PlaybackMode: $('#playbackMode').val(),
                            HLSFragmentSelector: {
                                FragmentSelectorType: $('#fragmentSelectorType').val(),
                                TimestampRange: $('#playbackMode').val() === "LIVE" ? undefined : {
                                    StartTimestamp: new Date($('#startTimestamp').val()),
                                    EndTimestamp: new Date($('#endTimestamp').val())
                                }
                            },
                            ContainerFormat: $('#containerFormat').val(),
                            DiscontinuityMode: $('#discontinuityMode').val(),
                            DisplayFragmentTimestamp: $('#displayFragmentTimestamp').val(),
                            MaxMediaPlaylistFragmentResults: parseInt($('#maxResults').val()),
                            Expires: parseInt($('#expires').val())
                        }, function(err, response) {
                            if (err) { return console.error(err); }
                            console.log('HLS Streaming Session URL: ' + response.HLSStreamingSessionURL);

                            // Step 4: Give the URL to the video player.
                            var playerName = $('#player').val();
                            if (playerName == 'HLS.js') {
                                var playerElement = $('#hlsjs');
                                playerElement.show();
                                var player = new Hls();
                                console.log('Created HLS.js Player');
                                player.loadSource(response.HLSStreamingSessionURL);
                                player.attachMedia(playerElement[0]);
                                console.log('Set player source');
                                player.on(Hls.Events.MANIFEST_PARSED, function() {
                                    video.play();
                                    console.log('Starting playback');
                                });
                            } else if (playerName === 'VideoJS') {
                                var playerElement = $('#videojs');
                                playerElement.show();
                                var player = videojs('videojs');
                                console.log('Created VideoJS Player');
                                player.src({
                                    src: response.HLSStreamingSessionURL,
                                    type: 'application/x-mpegURL'
                                });
                                console.log('Set player source');
                                player.play();
                                console.log('Starting playback');
                            } else if (playerName === 'Shaka Player') {
                                var playerElement = $('#shaka');
                                playerElement.show();
                                var player = new shaka.Player(playerElement[0]);
                                console.log('Created Shaka Player');
                                player.load(response.HLSStreamingSessionURL).then(function() {
                                    console.log('Starting playback');
                                });
                                console.log('Set player source');
                            }
                        }); 
                });

                $('.player').hide();
            });
        </script>
        <script src="ui.js"></script>
  </article>


<!-- time,temp,humid,-->
      <section >
        <nav>
        <body bgcolor="#ffffff">
        <table style="width:100%">
      <!-- PHP CODE TO FETCH DATA FROM ROWS-->
            <?php  
            // LOOP TILL END OF DATA
            include("db_connect.php");
			$sql1 = "SELECT * FROM  onoff ORDER BY ID DESC LIMIT 1;";
            $result1 = mysqli_query($conn,$sql1);
			 
                while($rows1=$result1->fetch_assoc())
                {
					$light =  $rows1['light'];
					//echo $light;
					$fan =  $rows1['fan'];		
					//echo $fan;
				}
				
            $sql = "SELECT * FROM  temperature ORDER BY ID DESC LIMIT 1;";
            $result = mysqli_query($conn,$sql);
                while($rows=$result->fetch_assoc())
                {
             ?>
             <br><br> 
    <tr>
       <th style="text-align:center">Logging Time </th>
    </tr>
    <tr>
       <td style="text-align:center;color:white"><?php echo $rows['id'];?></td>
    </tr>

</table> 
  <table style="width:100%">
    <tr>
      <th style="text-align:center;">Temperature</th>
      <th style="text-align:center;">Humidity</th> 
    </tr>
    <tr>
      <td style="text-align:center;" ><?php echo $rows['temp'];?><span> &#8451;</span></td>
      <td style="text-align:center" ><?php echo $rows['hum'];?> %</td>
    </tr>
    <?php } ?>
  
  </table>
  <!-- fan,light-->
  <br>
  <BR>
    <table   style="width:100%"; bgcolor="white" >
  <tr>
    <th><H1>Fan<H1></th>
    <th><H1 >Light<H1></th>
  </tr> 
  <tr>
    <div id = "send data">
      <form method="get" action="send.php">
    <td>
      <label class="switch">
      <input type="checkbox" name = "fan" <?php echo ($fan==1 ? 'checked' : '');?>>
      <span class="slider"></span>
      </label> <BR></td><BR>
    <td> 
      <label class="switch">
      <input type="checkbox" name = "light" <?php echo ($light==1 ? 'checked' : '');?>> 
      <span class="slider"></span>
    </label>
     <BR></td>
    </tr>
    <tr>
	
    <td  colspan="2"> <center><input type="submit" value = "Send Command" ></button>
	</center>
  </td></tr>
  </form>
</div>
 
  </nav>
  </section>
</body>
</html>
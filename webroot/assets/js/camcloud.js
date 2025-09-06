
const video = {
	 
	 /**
	  *
	  * Get a live stream
	  *
	  */
	 
	 curr: null,
	 timestamp: 0,
	 clipUrl: null,
	 accessToken: null,
	 ticket: null,
	 
	 stream: function (camera, player) {

		  console.log ('streaming...');
		  console.log (camera);

		  video.curr = camera;
		  
		  let url = '/videos/stream/' + camera.alt_device_id;
		  
		  $.ajax ({type: "GET",  // ask the server for the stream url
					  url: url,
					  success: function (data) {
							
							result = JSON.parse (data);
							
							console.log (result);

							if (result.status == 0) {
								 
								 camera ['stream_url'] = result.url;

								 player = document.getElementById ('player_' + player);
								 
								 if (Hls.isSupported ()) {
									  
									  camera ['hls'] = new Hls ({ // start the stream
											xhrSetup: xhr => { }
									  });
									  
									  camera ['hls'].loadSource (camera ['stream_url']);
									  camera ['hls'].attachMedia (player);
									  
									  camera ['hls'].on (Hls.Events.ERROR, function (event, data) {
											
											if (data.fatal) {
												 
												 switch (data.type) {
													  
												 case Hls.ErrorTypes.NETWORK_ERROR:
													  
													  console.error ("HLS fatal network error encountered, try to recover");
													  camera ['hls'].startLoad ();
													  break;
													  
												 case Hls.ErrorTypes.MEDIA_ERROR:
													  
													  console.error ("HLS fatal media error encountered, try to recover");
													  camera ['hls'].recoverMediaError ();
													  break;
													  
												 default:
													  
													  console.error ("HLS atal error encountered, cannot recover");
													  camera ['hls'].destroy ();
													  break;
												 }
											}
									  });
								 }
								 else {
									  
									  alert ('Stream not available');
								 }
							}
					  },
					  fail: function () {
							
							console.error ('get stream fail...');
					  },
					  always: function () {
							
							console.error ('get stream always...');
					  }
					 });
	 },
	 
	 clip: function () {

		  console.log ('clip... ' + $('#start_date').val () + ' ' + $('#start_time').val ());
		  console.log ('clip timestamp... ' + video.timestamp);
		  console.log (video.curr);
		  console.log (video.clipUrl);
		  
		  if (video.curr == null) {

				aler ('Please select a camera');
				return;
		  }
		  
		  camera = video.curr;
		  
		  if ($('#start_date').val () && $('#start_time').val ()) {
				
				timestamp = new Date ($('#start_date').val () + ' ' + $('#start_time').val ()).getTime () / 1000;
		  }
		  else if (video.timestamp > 0) {

				timestamp = video.timestamp;
		  }
		  else {
				
				alert ('Please select a date and time');
				return;
		  }
		  
		  player = document.getElementById ('player');
		  camera = video.curr;
		  
		  if (Hls.isSupported ()) {

				camera ['hls'] = new Hls ({
					 xhrSetup: xhr => {
						  xhr.setRequestHeader('Authorization', 'Bearer ' + video.accessToken);
					 }
				});
				
				let duration = 120;
				
				console.log (video.clipUrl);
				console.log ("access token... " + video.accessToken);
				
				camera ['hls'].loadSource (video.clipUrl);
				camera ['hls'].attachMedia (player);
				
				camera ['hls'].on (Hls.Events.ERROR, function (event, data) {
					 
					 if (data.fatal) {
						  
						  switch (data.type) {
								
						  case Hls.ErrorTypes.NETWORK_ERROR:
								
								console.error ("HLS fatal network error encountered, try to recover");
								camera ['hls'].startLoad ();
								break;
								
						  case Hls.ErrorTypes.MEDIA_ERROR:
								
								console.error ("HLS fatal media error encountered, try to recover");
								camera ['hls'].recoverMediaError ();
								break;
								
						  default:
								
								console.error ("HLS fatal error encountered, cannot recover");
								camera ['hls'].destroy ();
								break;
						  }
					 }
				});
		  }
	 },

	 edit: function (buID, id, type) {
		  
		  $.ajax ({type: "GET",
					  url: `/videos/edit/${buID}/${id}/amcrest`,
					  success: function (data) {
							
							data = JSON.parse (data);
							
							if (data.status == 0) {
								 
								 $('#edit_camera_content').html (data.html);
							}
							else {
								 
								 alert ('Camera edit failed');
							}
					  },
					  fail: function () {
							
							console.error ('add camera fail...');
					  },
					  always: function () {
							
							console.error ('add camera always...');
					  }
					 });
	 },

	 del: function (id, name) {

		  if (confirm (`Delete camera ${name}?`)) {

				console.log ('delete cam... ' + id);
		  }
	 }
};

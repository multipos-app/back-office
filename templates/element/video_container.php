<?php

$this->debug ("ticket... " . $ticket ['pos_no']);

switch ($merchant ['video_provider']) {
		  
	 case 'camcloud':
		  
?>
	 <script>
	  
	  video.timestamp = <?php echo $timestamp ?>;
	  video.curr = <?php echo json_encode ($camera) ?>;
	  video.clipUrl = '<?php echo $clipUrl ?>';
	  video.accessToken = '<?php echo $accessToken ?>';
	  video.ticket = <?php echo json_encode ($ticket) ?>;
	  
	  video.clip ();
	  
	 </script>
	 
	 <table class="table" style="border:solid 1px #fff;">

		  <tbody>

				<tr>
					 <td align="right">
		  				  <i class="bx bx-rewind icon-xlg" onclick="control ('ticket_backward')"></i>
					 </td>
					 
					 <td align="center" class="align-middle">
						  <h5><?= __ ('Previous/Next Transaction') ?></h5>
					 </td>
					 
					 <td align="left">
		  				  <i class="bx bx-fast-forward icon-xlg" onclick="control ('ticket_forward')"></i>
					 </td>
				</tr>

				<tr>
					 <td align="center" colspan="3">
						  
						  <video id="player"
									src="' + <?= $clipUrl ?> + '"
									muted="true"
									controls="true"
									preload="true"
									autoplay="true"
									muted style="backgrount: #fff;">
						  </video>
						  
					 </td>
				</tr>

				<tr>

					 <td align="right">
		  				  <i class="bx bx-rewind icon-xlg" onclick="clip ('clip_backward')"></i>
					 </td>
					 
					 <td align="center" class="align-middle">
						  <h5><?= __ ('Previous/Next Clip') ?></h5>
					 </td>
					 
					 <td align="left">
		  				  <i class="bx bx-fast-forward icon-xlg" onclick="clip ('clip_forward')"></i>
					 </td>
				</tr>
				
		  </tbody>
	 </table>
	 
	 <?php
	 
	 break;
	 default:
	 
	 }  // end of switch video provider
	 
	 ?>
	 <script>

	  function control (action) {

			let ticketID = video.ticket.id;
			let buID = video.ticket.business_unit_id;
			let posNo = video.ticket.pos_no;
			
			console.log ('control action... ' + action + ' ' + posNo);

			switch (action) {

				 case 'ticket_forward':
				 case 'ticket_backward':
					  
					  $.ajax ({
							url: `/tickets/nav/${action}/${ticketID}/${buID}/${posNo}`,
							type: "GET",
							success: function (data) {
								 
								 data = JSON.parse (data);
								 
								 $('#ticket_content').html (data.html)
							}
					  });

					  break;
			}
	  }

	  function clip (action) {
			
			console.log ('ts... ' + video.timestamp)
			switch (action) {
					  
				 case 'clip_forward':

					  video.timestamp += 2 * 60;
					  break;
					  
				 case 'clip_backward':
					  video.timestamp -= 2 * 60;
					  break;
			}

			console.log ('ts... ' + video.timestamp)
			
			video.clip ();
	  }
	  
	 </script>

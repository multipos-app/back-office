
<style>
 
 .ticket-grid {
	  
	  font-size: .8rem;
	  display: grid;
     width: 100%;
	  grid-template-columns:;
	  grid-column-gap: 20px;
	  align-items: top;
	  margin-top: 5px;
 }
 
 .controls-grid {
	  
	  font-size: .8rem;
	  display: grid;
     width: 100%;
	  grid-template-columns: 1fr 5fr;
 }

 .video-grid {
	  
	  display: grid;
     width: 100%;
	  grid-template-columns: .1fr 1fr .1fr;
	  grid-column-gap: 0px;
	  margin-top: 0px;
	  /* padding: 5px; */
	  /* margin: 5px; */
 }

 .details-grid {
 	  display: grid;
     width: 100%;
	  grid-template-columns: 1fr 1fr;
	  grid-column-gap: 0px;
	  padding: 10px !important;
	  margin-top: 5px;
 	  grid-row-gap: 0px;
 }

 .detail-btn {

	  color: #999;
	  padding-top: 3px;
 }
 
 .header-grid {
	  
	  grid-template-columns: 1fr 1fr;
 }
 
 .tender-grid {
	  
	  grid-template-columns: repeat(4, 1fr);
 	  margin-top: 0px;
 }
 
 .video-box {

	  /* height: 320px; */
	  /* widht: 640px; */
	  /* background-size: cover; */
     /* margin-right: 10px; */
 }
 
 .grid-span-3 {
	  grid-column: 1/4;
 }
 
 video {
     width: 100%;
     height: auto;
     max-height: 100%;
 }
 
 .ticket-text {
	  
	  margin-top: 10px;
 }
 
 .void-item {
	  
	  text-decoration: line-through;
 }
</style>

<div id="ticket" class="ticket-grid">
	 
	 <div class="controls-grid grid-span-all">
		  <div>
				<i class="fa fa-square-xmark fa-x-large text-center detail-btn" onclick="detailClose ()"></i>
		  </div>
		  <div>
				<?= $this->Form->select ('flag', $flag, ['id' => 'flag', 'onchange' => 'flag ()', 'class' => 'custom-dropdown', 'label' => false, 'value' => false]) ?>
		  </div>
	 </div>
	 
	 <?php if ($hasVideo) { ?>
		  <div class="grid-cell grid-cell-center top30">
				<div id="clip" class="video-box"/>
		  </div>
	 <?php } ?>
	 
	 <div class="grid-cell grid-cell-center">
		  
		  <div class="details-grid header-grid">
				
				<?php
				foreach ($ticket ['details'] as $detail) {
				?>
					 
					 <div class="grid-cell grid-cell-left">
						  <?= $detail ['desc'] ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $detail ['value'] ?>
					 </div>
				<?php
				}
				?>
				
				<div class="grid-cell grid-cell-center grid-cell-separator grid-span-all">
					 <?= __ ('Items') ?>
				</div>
				
				<?php
				foreach ($ticket ['ticket_items'] as $ti) {

					 $strikeThrough = '';
					 if ($ti ['state'] == 1) {

						  $strikeThrough = ' void-item';
					 }
				?>
					 
					 <div class="grid-cell grid-cell-left<?= $strikeThrough ?>">
						  <?= sprintf ('%0d %s', $ti ['quantity'], $ti ['item_desc']) ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right<?= $strikeThrough ?>">
						  <?= $this->moneyFormat ($ti ['quantity'] * $ti ['amount']) ?>
					 </div>
					 
					 <?php
					 foreach ($ti ['ticket_item_addons'] as $tia) {
						  
					 ?>
						  <div class="grid-cell grid-cell-left">
								<?= sprintf ('%0d %s', $tia ['addon_quantity'], $tia ['addon_description']) ?>
						  </div>
						  
						  <div class="grid-cell grid-cell-right">
								<?= $this->moneyFormat ($tia ['addon_quantity'] * $tia ['addon_amount']) ?>
						  </div>
					 <?php
					 }
					 }
					 ?>
					 <?php
					 if ($ticket ['tip'] > 0) {
					 ?>
						  <div class="grid-cell grid-cell-left">
								<?= __ ('Tip') ?>
						  </div>
						  
						  <div class="grid-cell grid-cell-right">
								<?= $this->moneyFormat ($ticket ['tip']) ?>
						  </div>

					 <?php
					 }
					 ?>
					 <?php
					 if (count ($ticket ['ticket_taxes']) > 0) {
					 ?>
						  
						  <div class="grid-cell grid-cell-center grid-cell-separator grid-span-all">
								<?= __ ('Taxes') ?>
						  </div>
						  
						  <?php
						  foreach ($ticket ['ticket_taxes'] as $tt) {
						  ?>
								
								<div class="grid-cell grid-cell-left">
									 <?= $tt ['short_desc'] ?>
								</div>
								
								<div class="grid-cell grid-cell-right">
									 <?= $this->moneyFormat ($tt ['tax_amount']) ?>
								</div>
								
						  <?php
						  }
						  }
						  ?>
		  </div>
		  
		  <div class="details-grid tender-grid">
				
				<div class="grid-cell grid-cell-left grid-cell-separator">
					 <?= __ ('Tender type') ?>
				</div>

				<div class="grid-cell grid-cell-right grid-cell-separator">
					 <?= __ ('Amount') ?>
				</div>
				
				<div class="grid-cell grid-cell-right grid-cell-separator">
					 <?= __ ('Returned') ?>
				</div>
				
				<div class="grid-cell grid-cell-right grid-cell-separator">
					 <?= __ ('Balance') ?>
				</div>
				
				<div class="grid-cell grid-cell-left even-cell grid-span-3">
					 <?= __ ('TOTAL') ?>
				</div>
				
				<div class="grid-cell grid-cell-right even-cell">
					 <?= $this->moneyFormat ($ticket ['total']) ?>
				</div>
				
				<?php
				
				$balance = $ticket ['total'];
			
				foreach ($ticket ['ticket_tenders'] as $tt) {

					 $balance -= floatval ($tt ['tendered_amount']) - floatval ($tt ['returned_amount']);
					 $balance = round ($balance, 2);

					 $tenderType = __ ('Unknown');
					 if ($tt ['tender_type'] !== null) {
						  
						  $tenderType = __ (strtoupper ($tt ['tender_type']));
					 }
				?>
				
				<div class="grid-cell grid-cell-left">
					 <?= $tenderType ?>
				</div>
				
				<div class="grid-cell grid-cell-right">
					 <?= $this->moneyFormat ($tt ['tendered_amount']) ?>
				</div>
				
				<div class="grid-cell grid-cell-right">
					 <?= $this->moneyFormat ($tt ['returned_amount']) ?>
				</div>
				
				<div class="grid-cell grid-cell-right">
					 <?= $this->moneyFormat ($balance) ?>
				</div>
				
				<?php
				switch ($tt ['tender_type']) {
						  
					 case 'CARD':
					 case 'CREDIT':
					 case 'DEBIT':
						  
						  $card = json_decode ($tt ['data_capture'], true);

						  foreach (['card_brand' => 'CARD BRAND', 'cardholder_name' => 'CARDHOLDER', 'last_4' => 'LAST 4 DIGITS'] as $key => $value) {

								$desc = '';
								if (isset ($card [$key])) {
									 
									 $desc = $card [$key];
									 
									 switch ($key) {
												
										  case 'cardholder_name':

												$desc = str_replace ("/", " ", $desc);
												break;
												
										  case 'last_4':
												
												$desc = '*' . $desc;
												break;
									 }
									 
				?>
				
				<div class="grid-cell grid-cell-left">
					 <?= __ ($value) ?>
				</div>
				<div></div>
				<div></div>
				<div class="grid-cell grid-cell-right">
					 <?= $desc ?>
				</div>
					 <?php
					 }
					 }
					 }
					 }
					 ?>

					 <div class="grid-cell grid-cell-left grid-span-3">
						  <?= __ ('BALANCE') ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $this->moneyFormat ($balance) ?>
					 </div>
		  </div>
	 </div>
	 
	 <div class="grid-cell grid-cell-center grid-cell-separator grid-span-all">
		  <?= __ ('Printed receipt') ?>
	 </div>
	 
	 <div class="grid-cell grid-cell-center ticket-text">
		  <pre >
				<?= $ticket ['ticket_text']?>
		  </pre>
	 </div>
</div>

<?php 

if ($_SERVER ['SERVER_NAME'] == 'dev.myvideoregister.com') {

	 echo '<pre>' . $ticket ['data_capture'] . '</pre>';
	 foreach ($ticket ['ticket_tenders'] as $tender) {

		  if (strlen ($tender ['data_capture']) > 0) {

				echo '<pre>' . $tender ['data_capture'] . '</pre>';
		  }
	 }
}
?>
<script>

 videoID = <?= $videoID ?>;
 homeVideoID = <?= $videoID ?>;
 homeVideoClip = <?= '"' . $clip . '"' ?>;
 videoClip = homeVideoClip;
 homeOffset = <?= $offset ?>;
 offset = homeOffset;

 console.log ('detail... ' + videoClip + ' ' + videoID + " " + offset + " " + $('#start_date').val ());
 
 clip ();
 function clip () {

	  console.log ('start video... ' + videoClip + ' ' + offset);

	  $('#clip').html ('<div class="video-grid">' +
							 
							 '<div class="grid-cell grid-cell-left">' +
							 '<a onclick="ticket (<?= ($ticket ['id'] - 1) ?>)"><i class="fa fa-arrow-left fa-large"></i></a>' +
							 '</div>' +
							 
							 '<div class="grid-cell grid-cell-center">' +
							 '<?= __ ('Previous/Next transaction') ?>' +
							 '</div>' +

							 '<div class="grid-cell grid-cell-right">' +
							 '<a onclick="ticket (<?= ($ticket ['id'] + 1) ?>)"><i class="fa fa-arrow-right fa-large"></i></a>' +
							 '</div>' +

							 '<div class="grid-cell grid-cell-center grid-span-all">' +
							 '<video id="player" width=520 height=400 src=' + videoClip + '#t=' + offset + ' controls="true" preload="true" autoplay="true"></video>' + 
							 '</div>' +
							 
							 '<div class="grid-cell grid-cell-left">' +
							 '<a onclick="video (\'prev\')"><i class="fa fa-arrow-left fa-large"></i></a>' +
							 '</div>' +
							 
							 '<div class="grid-cell grid-cell-center">' +
							 '<?= __ ('Previous/Next video clip') ?>' +
							 '</div>' +
							 
							 '<div class="grid-cell grid-cell-right">' +
							 '<a onclick="video (\'next\')"><i class="fa fa-arrow-right fa-large"></i></a>' +
							 '</div>' +
							 
							 '</div>');

	  let player = $('#player');
	  if (player.fastSeek) {
			
			player.fastSeek (offset);
	  }
	  else {

			player.currentTime = offset;
	  }
 }

 function video (dir) {

	  var data = {};
	  
	  data ['videoID'] = videoID;
	  data ['pos_no'] = <?= $ticket ['pos_no'];?>;
	  data ['dir'] = dir;

	  console.log ('send...');
	  console.log (data);

	  $.ajax ({
			url: "/tickets/video/" + videoID + '/' + dir,
			type: "GET",
			success: function (data) {
				 
				 data = JSON.parse (data);
				 console.log (data);

				 if (data.status == 0) {

					  videoID = data ['video_id'];
					  videoClip = data ['clip'];
					  offset = 0;
					  
					  clip ();
				 }
			}
	  });
 }
 
 function home () {

	  if (ticketID > 0) {
			
			$('#ticket_' + ticketID).removeClass ('detail-current');
			$('#ticket_' + ticketID).addClass ('detail-current');
			ticketID = homeVideoClip;
	  }

	  videoID = homeVideoID;
	  videoClip = homeVideoClip;
	  offset = homeOffset;
	  clip ();
 }

 function ticket (tid) {

	  console.log ('ticket... ' + tid);

	  if (ticketID > 0) {
			
			$('#ticket_' + ticketID).removeClass ('detail-current');
			ticketID = tid;
			$('#ticket_' + tid).addClass ('detail-current');
	  }
	  
 	  $.ajax ({type: "GET",
				  url: '/tickets/detail/' + tid,
				  success: function (data) {
                  
						data = JSON.parse (data);						
						$('#ticket').html (data.html);
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });}

</script>

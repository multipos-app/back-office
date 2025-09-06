<?php $this->debug ($videos); ?>

<style>

 .clips-main-grid {

	  display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 1fr;
 }

 .video-search-grid {

	  display: grid;
	  margin-top: 25px;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: .5fr 1fr 1fr 4fr;
	  grid-column-gap: 10px;
 }
 
 .video-clips-grid {

	  display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 1fr 4fr 1fr;
 }
 
 .video-player {

	  width: 100%;
 }
 
 .video-player-content {

	  margin-top: 25px;
	  padding: 5px;
 }
 
</style>

<script>

 var videos = <?php echo json_encode ($videos); ?>;
 console.log (videos);
 
</script>

<div class="clips-main-grid">

	 <div>
		  
		  <div class="form-grid video-search-grid">
				
				<div class="vr-back-arrow">
					 <i id="vr_back" class="fa fa-arrow-left fa-large vr-back-arrow-icon"></i>
				</div>

				<div class="form-cell form-control-cell">
					 <input type="text" id="start_date" name="start_date" class="form-control datetimepicker-input start-date" autocomplete="off" placeholder="<?= __ ('Start Date') ?>"/>
				</div>
				
				<div class="grid-cell">
					 <i class="fa fa-magnifying-glass fa-large action-icon" onclick="javascript:search ()"></i>
				</div>
				
				<div class="grid-cell grid-cell-right">
					 
				</div>

				<div class="grid-cell grid-cell-center grid-span-all">
					 <div id="pages" class="pagination"></div>	  
				</div>
				
		  </div>

		  <div class="video-clips-grid">
				
	 			<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Square receipt') ?></div>
	 			<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Date/Time') ?></div>
	 			<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Comment') ?></div>
	 			<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Status') ?></div>
		  </div>

		  <?php
		  $index = 0;
		  foreach ($videos as $video) {

				$action = '';
		  ?>
				
				<div class="video-clips-grid">
					 <div class="grid-row-wrapper" onclick="video (<?= $index ?>)">
						  
						  <div class="grid-cell grid-cell-left ticket-no"><?= $video ['id'] ?></div>
						  <div class="grid-cell grid-cell-left"><?= $video ['clip_time'] ?></div>
						  <div class="grid-cell grid-cell-left"><?= $video ['comment'] ?></div>
						  <div class="grid-cell grid-cell-right"><?= $video ['status'] ?></div>
					 </div>	  
				</div>
				
		  <?php
		  $index ++;
		  }
		  ?>

	 </div>

	 <div id ="player" class="video-player-content">
	 </div>

</div>

<script>
 
 function video (index) {

	  console.log (videos [index]);
	  let video = videos [index];
	  
	  $('#player').html ('<video src="' + video ['file_name'] + '" controls=true autoplay=true class="video-player"></video>');

 }
 
 backButton ();
</script>

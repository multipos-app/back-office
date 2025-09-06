
<script>

 var accessToken = '<?php echo $accessToken; ?>';
 var locations = <?php echo json_encode ($locations); ?>;
 
</script>
<style>

 sup {
	  cursor: pointer;
     vertical-align: super;
     font-size: 50%;
 }

 .video-grid-1 {

	  display: grid;
	  grid-template-rows: auto;
	  grid-template-columns: repeat(1, 1fr) !important;
	  grid-column-gap: 10px;
	  grid-row-gap: 10px;
 }
 
 .video-grid-2 {

	  display: grid;
	  grid-template-rows: auto;
	  grid-template-columns: repeat(2, 1fr) !important;
	  grid-column-gap: 10px;
	  grid-row-gap: 10px;
 }
 .video-grid-3 {

	  display: grid;
	  grid-template-rows: auto;
	  grid-template-columns: repeat(3, 1fr) !important;
	  grid-column-gap: 10px;
	  grid-row-gap: 10px;
 }
 
 .video-grid-4 {

	  display: grid;
	  grid-template-rows: auto;
	  grid-template-columns: repeat(4, 1fr) !important;
	  grid-column-gap: 10px;
	  grid-row-gap: 10px;
 }
 
</style>

<div class="row mb-3">
	 <div id="players" class="col-sm-12 text-center"></div>
</div>

<?php

// load the appropriate video javascript

echo $this->element ('video');

?>

<div class="row mb-3">
	 
	 <div class="col-sm-2 text-end d-flex align-items-center">
		  <h5><?= __ ('Seek to') ?></h5>
	 </div>
	 
	 <label for="start_date" class="col-sm-2 col-form-label text-end">Start date</label>
	 <div class="col-sm-2">
		  <input type="date" id="start_date" name="start_date" class="form-control" value="2025-08-02">
	 </div>
	 
	 <label for="start_time" class="col-sm-2 col-form-label text-end">Start time</label>
	 <div class="col-sm-2">
		  <input type="time" id="start_time" name="start_time"  value="13:00" class="form-control">
	 </div>

	 <div class="col-sm-2 text-start d-flex align-items-center">
		  <i class="bx bxs-right-arrow icon-lg" onclick="video.clip ()"></i>
	 </div>

</div>

<div class="row mt-3">
	 <div class="col-sm-3 text-start">
		  <h5><?= __ ('Play selected cameras') ?></h5>
	 </div>
	 <div class="col-sm-1 text-start d-flex align-items-center">
		  <i class="bx bxs-right-arrow icon-lg" onclick="play ()"></i>
	 </div>
</div>

<div class="row mt-3">
	 <div class="col-sm-2 text-start">
		  <h5><?= __ ('Locations') ?></h5>
	 </div>
</div>

<div class="row mt-3">
	 <div class="form-check form-switch col-sm-12 text-start ms-3">
		  <label class="form-label" for="select_all"><?= __ ('Select all cameras') ?></label>
		  <input type="checkbox" class="form-check-input" id="select_all" name="select_all">
	 </div>
</div>

<table class="table table-hover mt-3">
	 
	 <tbody>
		  
		  <?php
		  
		  $index = 0;
		  
		  foreach ($locations as $location) {
		  ?>

				<tr>
					 
					 <td><?= $location ['location_name'] . ' ' . $location ['addr'] ?></td>
					 <td align="left"></td>
					 
					 <td class="text-end">
						  <button type="submit"
									 id="add_camera"
									 class="btn btn-primary"
									 data-bs-toggle="modal"
									 data-bs-target="#edit_camera_modal"
									 onclick="video.edit (<?= $location ['business_unit_id'] ?>, 0, 'amcrest')"><?= __ ('Add Camera') ?>
						  </button>
					 </td>
				</tr>
				
				<?php
				
				if (count ($location ['cameras']) > 0) {

					 foreach ($location ['cameras'] as $camera) {	 
				?>
					 <tr>

						  <td colspan="2">
								<div class="form-check form-switch ms-5">
									 <label class="form-label" for="camera_select_<?= $index ?>"><?= $camera ['device_name'] ?></label>
									 <input type="checkbox" class="form-check-input camera-select" id="camera_select_<?= $index ?>">
								</div>
						  </td>
						  
						  <td align="right">
								<i class="bx bxs-pencil icon-lg ms-3"
											 data-bs-toggle="modal"
											 data-bs-target="#edit_camera_modal"
											 onclick="video.edit (<?= $location ['business_unit_id'] ?>, <?= $camera ['id'] ?>, 'amcrest')"></i>
						  </td>
					 </tr>
					 <?php

					 $index ++;
					 }
					 }
					 }
					 ?>
	 </tbody>
</table>

<div class="modal fade" id="edit_camera_modal" tabindex="-1">
	 <div class="modal-dialog modal-dialog-scrollable">
		  <div class="modal-content">
				<div class="modal-header">
					 <h5 class="modal-title"><?= __ ('Add/edit camera') ?></h5>
					 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="edit_camera_content" class="modal-body">
				</div>
		  </div>
	 </div>
</div>

<script>

 let cameras = [];
 i = 0;
 $.each (locations, function (key, location) {

	  $.each (location.cameras, function (index, camera) {
			
			cameras.push (camera);
	  });
 });

 /*
  * select camers if there are only a couple
  */
 
 if (cameras.length < 3) {

	  $("input:checkbox.camera-select").prop ("checked", true);
 }

 $('#select_all').change (function () {
	  
     if ($(this).is (":checked")) {

			$("input:checkbox.camera-select").prop ("checked", true);
	  }
	  else {
			
			$("input:checkbox.camera-select").prop ("checked", false);
	  }
 });

 function play () {

	  $('#players').html ('');
	  let checked = [];

	  $.each (cameras, function (index, camera) {
			
			if ($(`#camera_select_${index}`).is (":checked")) {

				 checked.push (camera);
			}
	  });

	  if (checked.length == 0) {

			alert ('Please select one or more cameras');
	  }
	  
	  let cols = 0;
	  switch (checked.length) {
				 
			case 1:
				 
				 cols = 1;
				 break;

			case 2:
			case 3:
			case 4:
				 
				 cols = 2;
				 break;
				 
			case 5:
			case 6:
			case 7:
				 
				 cols = 3;
				 break;
				 
			default:
				 
				 cols = 4;
				 break;
	  }
	  
	  let html = `<div class="video-grid-${cols}">`;
	  $.each (checked, function (index, camera) {
			
 			html +=
				 `<div>` +
				 `<div><video id="player_${index}" controls autoplay></video></div>` +
				 `<div><h6>${camera ['device_name']}</h6></div>` +
				 `</div>`;
	  });
	  
	  html += `</div>`;
	  
	  $('#players').html (html);
	  
 	  $.each (checked, function (index, camera) {

			video.stream (camera, i ++);
	  });
 }
 
</script>

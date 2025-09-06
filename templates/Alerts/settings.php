<style>
 
 .slidecontainer {
	  width: 100%; /* Width of the outside container */
 }

 .slider {
	  -webkit-appearance: none;
	  width: 100%;
	  height: 32px;
	  border-radius: 5px;  
	  background: #d3d3d3;
	  outline: none;
	  opacity: 0.7;
	  -webkit-transition: .2s;
	  transition: opacity .2s;
 }

 .slider::-webkit-slider-thumb {
	  -webkit-appearance: none;
	  appearance: none;
	  width: 25px;
	  height: 25px;
	  border-radius: 50%; 
	  background: #05AfaD;
	  cursor: pointer;
 }

 .slider::-moz-range-thumb {
	  width: 25px;
	  height: 25px;
	  border-radius: 50%;
	  background: #04AA6D;
	  cursor: pointer;
 }

 .alert-control {
	  
	  padding-top: 7px;
 }

 .alert-input {

	  border: 0;
	  -webkit-appearance: none
 }
 
 .alert-text {

	  padding-top: 25px;
 	  padding-bottom: 15px;
 
</style>

<div>
	 <i class="fa fa-square-xmark fa-x-large text-center detail-btn" onclick="settingsClose ()"></i>
</div>

<form id="alerts_edit" name="alerts_edit">
	 
	 <div class="alert-controls-grid">
		  
		  <?php

		  $i = 0;
		  foreach ($alerts as $alert) {
				
				$params = $alert ['params'];
				$range = $alert ['params'] ['range'];

				$shortDesc = '';
				$longDesc = '';
				
				switch ($alert ['alert_name']) {

					 case 'refunds_amount':

						  $shortDesc = __ ('Refund ') .
											'<input id="' . $alert ['alert_name'] . '_factor" name="alerts[' . $i . '][factor]"  minlength="1" maxlength="2" size="2" dir="rtl" class="alert-input">' .
											__ (' times average:');
						  $longDesc = __ ('Alert triggers when the refund is times greater than average refund. Zero will disable all refund alerts.');
						  break;
						  
					 case 'discounts_amount':

						  $shortDesc = __ ('Discount ') .
											'<input id="' . $alert ['alert_name'] . '_factor" name="alerts[' . $i . '][factor]"  minlength="1" maxlength="2" size="2" dir="rtl" class="alert-input">' .
											__ (' times average:');
						  $longDesc = __ ('Alert triggers when the discount is X times greater than average discount. Zero will disable all discount alerts.');
						  break;

					 case 'no_sales_quantity':
						  
						  $shortDesc = __ ('No sales are ') .
											'<input id="' . $alert ['alert_name'] . '_factor" name="alerts[' . $i . '][factor]"  minlength="1" maxlength="2" size="2" dir="rtl" class="alert-input">' .
											__ (' times average:');
						  $longDesc = __ ('Alert triggers when the number of no sales is X times greater than average number of no sales. Zero will disable all no sale alerts.');
						  break;
				}
				
		  ?>

		  <input type="hidden" name="alerts[<?= $i ?>][id]" value="<?= $alert ['id'] ?>">
		  <input type="hidden" name="alerts[<?= $i ?>][name]" value="<?= $alert ['alert_name'] ?>">

		  <div class="row g-1 mb-3">
				<div class="col-sm-12">
					 <h6><?= $alert ['alert_text'] ?></h6>
				</div>
		  </div>
	  
		  <div class="row g-1 mb-3">
				<div class="col-sm-12"><?= $longDesc ?></div>
		  </div>
	 	  
		  <div class="row g-1 mb-3">
				
				<div class="col-sm-8"><?= $shortDesc ?></div>
		  
				<div class="col-sm-4 slidecontainer alert-control">
					 <input type="range" min="<?= $range ['min'] ?>" max="<?= $range ['max'] ?>" value="<?= $params['factor'] ?>" step=".1" class="slider" id="<?= $alert ['alert_name'] ?>_slider">
				</div>
		  </div>

	 <?php
	 $i ++;
	 }
	 ?>
	 </div>

	 <div class="row g-3 mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
 		  <div class="col-smb-3 d-grid text-center">
				<button type="submit" class="btn btn-primary" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
		  </div>
	 </div>
</form>

<script>
 
 var alerts = <?php echo json_encode ($alerts, true); ?>;
 console.log (alerts);

 $(alerts).each (function (i, alert) {

	  alert ['slider'] = document.getElementById (alert.alert_name + "_slider");
	  alert ['val'] = $('#' +  alert.alert_name + "_factor");

	  alert ['val'].val (alert ['slider'].value);
	  
	  alert ['slider'].oninput = function () {
			
			alert ['val'].val (alert ['slider'].value);
	  }
 });

 
 $('#alerts_update').on ('click', function (e) {
	  
	  e.preventDefault ();

	  var form = document.querySelector ('form');
	  if (!form.checkValidity()) {

			form.reportValidity ();
			return;
	  }

	  let url = '/alerts/settings';

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#alerts_edit').serialize (),
				  success: function (data) {

						data = JSON.parse (data);
						console.log (data);
						
						if (data.status == 0) {
							 
							 settingsClose ();
							 controller ('alerts', false);
						}
						else {

							 $('#status_text').html (data.status_text);
						}
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });
 
</script>

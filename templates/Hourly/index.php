<?php
/**
 *
 * Copyright (C) 2017 posAppliance, LLC
 * <http://www.posAppliance.com>
 *
 * All rights are reserved. No part of the work covered by the copyright
 * hereon may be reproduced or used in any form or by any means graphic,
 * electronic, or mechanical, including photocopying, recording, taping,
 * or information storage and retrieval systems -- without written
 * permission signed by an officer of posappliance, LLC.
 *
 */

?>

<style>

 .search-grid {
	  
	  margin-top: 25px;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 120px 1fr 6fr;
	  grid-column-gap: 10px;
 }
 
 .form-controls-grid {
     grid-template-columns: 1fr 5fr !important;
 }

</style>

<form id="hourly">
	 <div class="form-grid search-grid">
		  
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>

	 <div class="form-cell form-control-cell">
				<input type="text" id="start_date" name="start_date" class="form-control datetimepicker-input start-date" autocomplete="off" onchange="searchDate ()" placeholder="<?= __ ('Search Start Date') ?>"/>
		  </div>
		  
		  <div class="grid-cell"></div>
	 </div>
</form>

<div id="page_export" class="grid-container-<?= $len ?>">
	 
	 <div class="grid-cell grid-cell-right grid-span-all">
		  
		  <a onclick="javascript:skip(<?= $prev ?>, 'hourly');" class="action-icon"><i class="far fa-arrow-left fa-large action-icon"></i></a>		
		  <a onclick="javascript:skip(<?= $next ?>, 'hourly');" class="action-icon"><i class="far fa-arrow-right fa-large action-icon"></i></a>		  
		  <a href="#" onclick="javascript:exporter('hourly', <?= $len + 2 ?>);"><i class="far fa-download fa-large action-icon"></i></a>

	 </div>
	 
	 <?php 
	 
	 $report = [];
	 $dayTotals = array_fill (0, 8, 0);

	 for ($hour = 0; $hour < $hourly ['hid']; $hour ++) {

		  $report [$hour] = array ($hourly ['times'] [$hour]);
		  $total = 0;

		  for ($day = 0; $day < 7; $day ++) {
				
				array_push ($report [$hour], $hourly [$day] [$hour]);
		  }
		  array_push ($report [$hour], $total);
	 }

	 for ($day = 0; $day < 7; $day ++) {
		  
		  for ($hour = 0; $hour < $hourly ['hid']; $hour ++) {

				$dayTotals [$day] += $hourly [$day] [$hour] ['amount'];
		  }
		  
		  $dayTotals [7] += $dayTotals [$day];
	 }
	 
	 echo '<div class="grid-cell grid-cell-separator data-cell">' .  __ ('Time') . '</div>';
	 foreach ($periods as $period) {
		  
		  echo '<div class="grid-cell grid-cell-separator grid-cell-right data-cell">'  .  $period ['period']  .  '</div>';
	 }
	 
	 echo '<div class="grid-cell grid-cell-separator grid-cell-right data-cell">' .  __ ('Total') . '</div>';

	 $start = $dow;
	 $end = $dow + 1;
	 $start = 0;
	 $end = 9;
	 $oneHour = 60 * 60;
	 
	 for ($hour = 0; $hour < $hourly ['hid']; $hour ++) {
	 		  
		  echo '<div class="grid-row-wrapper">';
		  $dayTotal = 0;
		  
		  for ($day = $start; $day < $end; $day ++) {

				switch ($day) {

					 case 0:
						  echo '<div class="grid-cell grid-cell-left data-cell">' . substr ($report [$hour] [$day], 11, 5) . '</div>';
						  break;

					 case 8:
						  
						  echo '<div class="grid-cell grid-cell-right data-cell">' . $this->moneyFormat ($dayTotal) . '</div>';
						  break;

					 default:

						  $action = $this->moneyFormat ($report [$hour] [$day] ['amount']);
						  
						  if (isset ($report [$hour] [$day] ['link'])) {

								$startHour = $report [$hour] [$day] ['link'] ['start'];
								$endHour = $report [$hour] [$day] ['link'] ['end'];
								$buID = $report [$hour] [$day] ['link'] ['business_unit_id'];
								
								$url = "tickets/index/start_hour/$startHour/end_hour/$endHour/business_unit_id/$buID";
								$action = '<a class="report-link" onclick="controller (\'' .$url . '\', false)"' . '>' . $this->moneyFormat ($report [$hour] [$day] ['amount']) . '</a>';

								echo '<div class="grid-cell grid-cell-right data-cell">' . $action . '</div>';
								$dayTotal += $report [$hour] [$day] ['amount'];
						  }
						  else {

								echo '<div class="grid-cell grid-cell-right data-cell">' . $this->moneyFormat ($report [$hour] [$day] ['amount']) . '</div>';
						  }
						  						  
						  break;
				}
		  }
		  echo '</div>';
	 }

	 echo '<div class="grid-cell grid-cell-left grid-cell-separator data-cell">'.__ ('Total', true).'</div>';
	 for ($day = 0; $day < 8; $day ++) { echo '<div class="grid-cell grid-cell-right grid-cell-separator data-cell">' . $this->moneyFormat ($dayTotals [$day]) . '</div>'; }
	 ?>
	 
</div>

<script>
 
 function search () {

	  let url = '/hourly/index';
	  
	  if ($('#start_date').val ().length > 0) {

			url += '/start_date/' + $('#start_date').val () + ' 00:00:00';
	  }
	  else if ($('#ytd').val () > 0) {

			url += '/ytd/' + $('#ytd').val ();
	  }

	  controller (url, true);
 }
 
 $('#start_date').datepicker ({
	  altFormat: 'yy-mm-dd',
	  altField: '#start_date'});
 
 function searchDate () {
	  
	  controller ('/hourly/index/start_date/' + $('#start_date').val (), true);
 }

</script>

<?= $this->Html->script ('exporter.js') ?>

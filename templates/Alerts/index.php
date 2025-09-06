
<style>

 .alert-amount-grid {
	  
	  display: grid;
	  width: 30%;
	  grid-template-columns: 1fr 1fr 1fr;
 }
 
 .alert-quantity-grid {
	  
	  display: grid;
	  width: 30%;
	  grid-template-columns: 1fr 1fr;
 }

 .alert-tickets {
	  
	  border-radius: 5px;
	  border: 1px solid #eee;
	  box-shadow: rgba(100, 100, 111, 0.2) 2px 5px 10px 0px;
 }
 
 .alert-link {

	  cursor: pointer;
 }
 
 hr {
	  
     height: 2px;
     color: #ed1d61;
     background: #ed1d61;
     font-size: 0;
     border: 0;
 }
 
</style>

<?php switch ($merchant ['role']) {
		  
	 case'videoregister': 
	 case'admin':
?>
	 
<div class="row g-1 m-3">
	 <div class="col-md-11"></div>
	 <div class="col-md-1 text-center">
		  <i class="bx bxs-cog icon-lg" data-bs-toggle="modal" data-bs-target="#alert_settings_modal" onclick="settings ()"></i>
	 </div>
</div>
	 
<?php 
}

foreach ($alerts as $alert) {
	 
	 $grid = 'alert-' . $alert ['params'] ['col'] . '-grid';
	 
	 
?>
	 <h3><?= $alert ['alert_text'] ?></h3>

	 <div><canvas id="<?= $alert ['alert_name']?>_chart" width=720 height=80></canvas></div>

	 <?php
	 
	 if (count ($alert ['exceptions']) > 0) {

	 ?>
		  <div class="alert-<?= $alert ['params'] ['col'] ?>-grid">
				<div class="grid-cell grid-cell-left"><h5><?= __ ('Median') ?></h5></div>
				<?php
				
				if ($alert ['params'] ['col'] == 'amount') {
				?>
					 <div class="grid-cell grid-cell-right"></div>
				<?php
				}
				?>
				<div class="grid-cell grid-cell-right"><h5><?= abs ($alert ['median']) ?></h5></div>
		  </div>

		  <div class="alert-<?= $alert ['params'] ['col'] ?>-grid">
				
				<div class="grid-cell grid-cell-left grid-cell-separator"><h5><?= __ ('Date') ?></h5></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><h5><?= __ ('Quantity') ?></h5></div>

				<?php

				if ($alert ['params'] ['col'] == 'amount') {
				?>
					 <div class="grid-cell grid-cell-right grid-cell-separator"><h5><?= __ ('Amount') ?></h5></div>
				<?php
				}
				?>
		  </div>
		  
		  <?php

		  $i = 0;
		  foreach ($alert ['exceptions'] as $exception) {

		  ?>
				<div class="alert-<?= $alert ['params'] ['col'] ?>-grid grid-row-wrapper" onclick="tickets (<?= $exception ['timestamp'] ?>,'<?= $alert ['params'] ['ticket_key'] ?>',<?= $i ?>)">
					 <div class="grid-cell grid-cell-left" ><?= $exception ['date'] ?></div>
					 <div class="grid-cell grid-cell-right"><?= abs ($exception ['quantity']) ?></div>
					 <?php

					 if ($alert ['params'] ['col'] == 'amount') {
					 ?>
						  <div class="grid-cell grid-cell-right"><?= sprintf ("%.2f", abs ($exception ['amount'])) ?></div>
					 <?php
					 }
					 ?>
				</div>
				<div id="<?= $alert ['params'] ['ticket_key'] ?>_<?= $i ?>"></div>
				<?php

				$i ++;
				}
				?>
				<hr>
				
		  <?php
		  }
		  else {

				echo '<br/><div style="text-align:center;"><h3>' . __ ('No Alerts') . '</h3></div><br/>';
		  }
		  }
		  ?>

		  <div class="container">

				<div id="ticket_detail"></div>

		  </div>
		  
		  <div class="modal fade" id="alert_settings_modal" tabindex="-1">
				<div class="modal-dialog modal-dialog-scrollable">
					 <div class="modal-content">
						  <div class="modal-header">
								<h5 class="modal-title"><?= __ ('Alerts settings') ?></h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						  </div>
						  <div id="alert_settings_content" class="modal-body">
						  </div>
					 </div>
				</div>
		  </div>
		  
		  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		  <script>
			
			var COLORS = ["rgba(253, 98, 94, 0.65)",
							  "rgba(138, 212, 235, 0.65)",
							  "rgba(242, 200, 15, 0.65)",
							  "rgba(95, 107, 109, 0.65)",
							  "rgba(254, 150, 102, 0.65)",
							  "rgba(163, 103, 150, 0.65)",
							  "rgba(254, 150, 102, 0.65)",
							  "rgba(1, 184, 170, 0.65)",
							  "rgba(51, 54, 51, 0.65)",
							  "rgba(1, 184, 170, 0.65)",
							  "rgba(253, 98, 94, 0.65)",
							  "rgba(51, 54, 51, 0.65)",
							  "rgba(138, 212, 235, 0.65)",
							  "rgba(242, 200, 15, 0.65)",
							  "rgba(95, 107, 109, 0.65)",
							  "rgba(163, 103, 150, 0.65)"];
			
			var dates = <?php echo json_encode ($dates, true); ?>;
			var alerts = <?php echo json_encode ($alerts, true); ?>;
			var totals = <?php echo json_encode ($totals, true); ?>;

			totals ['quantity'] = [];
			totals ['amount'] = [];
			
			$(totals.totals).each (function (i, total) {

				 totals ['quantity'].push (total.quantity / 10);
				 totals ['amount'].push (total.amount / 10);
			});
			
			$(alerts).each (function (i, alert) {

				 col = alert.params.col;
				 alert [col] = [];
				 
				 $(alert.totals).each (function (i, total) {
					  
					  alert [col].push (total [col]);
				 });
				 
				 new Chart (document.getElementById (alert.alert_name + '_chart').getContext ('2d'), {
					  type: 'bar',
					  data: {
							datasets: [{data: totals [alert.params ['col']],
											label: totals.alert_text + ' x 10',
											borderColor: 'rgba(1, 184, 170, 0.65)',
											backgroundColor: 'rgba(1, 184, 170, 0.65)'},
										  {data: alert [alert.params ['col']],
											label: alert.alert_text,
											borderColor: 'rgba(255, 0, 0, 0.65)',
											backgroundColor: 'rgba(255, 0, 0, 0.65)'}],
							labels: dates,
							cubicInterpolationMode: 'default',
							tension: 10,
							fill: false
					  },
					  options: {
							responsive: true,
							scales: {
								 x: {
									  stacked: true,
								 }
							}
					  }
				 });

				 var sorted = totals [alert.params ['col']].slice ().sort ((a, b) => a - b);
				 
				 var median = sorted [Math.floor (sorted.length / 2)];

			});
			

			/**
			 *
			 * get the tickets for this alert period (ajax)
			 *
			 */
			
			function tickets (timestamp, ticketType, i) {

				 $('.alert-tickets').each (function () {

					  $(this).html ('');
				 });

				 let url = '/alerts/tickets/' + timestamp + '/' + ticketType + '/' + i;

				 $.ajax ({type: "GET",
							 url: url,
							 success: function (data) {
								  
								  data = JSON.parse (data);

								  if (data.status == 0) {

										$('#' + data.ticket_type + '_' + i).html (data.html);
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
				 }); }

			function settingsClose () {

				 $('#alert_settings').toggleClass ('on');
			}

			function settings () {
				 
				 let url = '/alerts/settings';
				 $.ajax ({type: "GET",
							 url: url,
							 success: function (data) {
								  
								  data = JSON.parse (data);
								  $('#alert_settings_content').html (data.html);
							 },
							 fail: function () {

								  console.log ('fail...');
							 },
							 always: function () {

								  console.log ('always...');
							 }
				 });
			}

		  </script>


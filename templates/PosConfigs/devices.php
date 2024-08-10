<style>
 
 .main-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 2fr 2fr;
	  grid-gap: 15px;
	  font-size: 13px;
 }

 .grid-label {
	  margin-left: 5px;
 }
 
 .grid-label:hover {
	  cursor: pointer;
 }

 .desc-bottom {
	  margin-bottom: 10px;
 }
 
</style>

<?= $this->Form->create ('pos_configs') ?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border">Devices</legend>
				
	 <div class="container">
		  
		  <div class="main-grid">

				<div class="grid-cell grid-cell-right grid-lable"><?= __('POS Device Type') ?></div>		  
				<div class="grid-cell grid-cell-left">
					 <?php echo $this->Form->select ('device_type',
																['generic' => __ ('Generic Tablet'),
																 'elo_paypoint' => __ ('Elo Paypoint'),
																 'elo_i_series' => __ ('Elo I-Series'),
																 /* 'carbon' => __ ('Carbon 8/10'),*/
																 'hp_engage_one_prime' => 'HP Engage One Prime'],
																['class' => 'custom-dropdown',
																 'label' => false,
																 'value' => $deviceType,
																 'onchange' => "deviceSelect(this)"]); ?>
				</div>
				<div class="grid-cell"></div>		  
	
				<div class="grid-cell grid-cell-right desc-bottom top15"><?= __('Scanner') ?></div>
				<div class="grid-cell grid-cell-left top15">
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="scanners" id="scanners" type="checkbox"<?php if ($scanners) echo ' checked'; ?>>
						  <label class="grid-label" for="scanners"><?= __ ('USB keyboard scanner or Bluetooth in keyboard mode') ?></label>
					 </div>
				</div>
				<div class="grid-cell"></div>		  

				<div class="grid-cell grid-cell-right top10"><?= __('Receipt Printer') ?></div>
				<div class="grid-cell grid-cell-left top10">
					 <?php echo $this->Form->select ('printers.receipt.model',
																['none' => __ ('None'),
																 'star_tsp100' => __ ('Star Micronics TSP100'),
																 'star_mcp2' => __ ('Star Micronics MC Print2'),
																 'star_mcp3' => __ ('Star Micronics MC Print3'),
																 'elo_paypoint' => __ ('Elo Paypoint Internal'),
																 /* 'carbon_10_print' => __ ('Verifone Carbon 8/10'),*/
																 'citizen_e651' => __ ('Citizen E651'),
																 'hp_engage_one' => __ ('HP Engage One')],
																['value' => $printerModel,
																 'class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
				<div class="grid-cell grid-cell-left top10">
					 <?php echo $this->Form->select ('printers.receipt.port',
                                                ['none' => __ ('Connection'),
                                                 'TCP:' => __ ('LAN/WiFi'),
                                                 'BT:' => __ ('Blue Tooth'),
                                                 'USB:' => __ ('USB')],
                                                ['value' => $printerPort,
                                                 'class' => 'custom-dropdown',
                                                 'label' => false]); ?>
				</div>

				<div class="grid-cell grid-cell-right top10"><?= __('Payment') ?></div>
				<div class="grid-cell grid-cell-left top10">
					 
					 <?php echo $this->Form->select ('payment',
																['none' => __ ('None (External Credit)'),
																 'verifone' => __ ('Verfone Carbon/P400'),
																 'sumup' => __ ('SumUp')],
																['value' => $payment,
																 'class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
				<div class="grid-cell grid-cell-left"></div>		  
		  </div>
	 </div>
	 
	 <div class="container">
		  
		  <div class="row top30">
				<div class="col-sm-4 text-right"></div>
				<div class="col-sm-2">
					 <?php echo $this->Form->submit ('Save', ['class' => 'btn btn-success btn-block']); ?>
				</div>
				<div class="col-sm-2">
					 <button class="btn btn-warning btn-block" onclick="javascript:window.location.href ='<?= $this->request->getAttribute ('webroot'); ?>pos-configs'"><?= __ ('Cancel') ?></button>
				</div>
		  </div>
	 </div>
</fieldset>

<script>

 function deviceSelect ($sel) {

	  console.log ('deviceSelect... ' + $('[name="device_type"]').val ());

	  switch ($('[name="device_type"]').val ()) {

			case 'elo_paypoint':
				 				 
				 $('[name="printers[receipt][model]"]').val ("elo_paypoint");
				 $('[name="scanners"]').val ("keyboard");

	 break;
	 
			case 'carbon':
				 				 
				 $('[name="printers[receipt][model]"]').val ("carbon_10_print");

				 break;	  }
 }
 
</script>

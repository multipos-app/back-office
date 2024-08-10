
<?= $this->Form->create ($config) ?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border">Settings</legend>
				
	 <div class="container edit">
		  
		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('POS Device Type') ?></div>		  
				<div class="col-sm-3">
					 <?php echo $this->Form->select ('device_type',
																['generic' => __ ('Generic Tablet'),
																 'elo_paypoint' => __ ('Elo Paypoint'),
																 'elo_i_series' => __ ('Elo I-Series'),
																 'carbon' => __ ('Carbon 8/10'),
																 'hp_engage_one_prime' => 'HP Engage One Prime'],
																['class' => 'custom-dropdown',
																 'label' => false,
																 'value' => $scanner,
																 'onchange' => "deviceSelect(this)"]); ?>
				</div>
				
				<div class="col-sm-1"></div>		  
				<div class="col-sm-3">		  
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="prompt_open_amount" id="prompt_open_amount" type="checkbox"<?php if ($params ['prompt_open_amount']) echo ' checked'; ?>>
						  <label for="prompt_open_amount"><?= __ ('Prompt open amount') ?></label>
						  </div>
				</div>
		  </div>
						  
		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Scanner') ?></div>		  
				<div class="col-sm-3">
					 <?php echo $this->Form->select ('scanners',
																['none' => __ ('None'),
																 'keyboard' => __ ('Keyboard'),
																 'zebra_ds2778_usb' => __ ('Zebra DS 2778 USB'),
																 'zebra_ds2778_bt' => __ ('Zebra DS 2778 BlueTooth')],
																['class' => 'custom-dropdown',
																 'label' => false,
																 'value' => $scanner]); ?>
				</div>
				<div class="col-sm-1"></div>		  
				<div class="col-sm-3">		  
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="print_receipt" id="print_receipt" type="checkbox"<?php if ($params ['print_receipt']) echo ' checked'; ?>>
						  <label for="print_receipt"><?= __ ('Always print receipt') ?></label>
					 </div>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Receipt Printer') ?></div>
				<div class="col-sm-3">
					 <?php echo $this->Form->select ('printers.receipt.model',
																['none' => __ ('None'),
																 'star_tsp100' => __ ('Star Micronics TSP100'),
																 'star_mcp2' => __ ('Star Micronics MC Print2'),
																 'star_mcp3' => __ ('Star Micronics MC Print3'),
																 'elo_paypoint' => __ ('Elo Paypoint Internal'),
																 'carbon_10_print' => __ ('Verifone Carbon 8/10'),
																 'hp_engage_one' => __ ('HP Engage One')],
																['value' => $printerModel,
																 'class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
				<div class="col-sm-1"></div>		  
				<div class="col-sm-3">		  
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="enter_clerk" id="enter_clerk" type="checkbox"<?php if ($params ['enter_clerk']) echo ' checked'; ?>>
						  <label for="enter_clerk"><?= __ ('Enter clerk at start of sale') ?></label>
					 </div>
				</div>
		  </div>

		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Receipt Printer Connection') ?></div>
				<div class="col-sm-3">

					 <?php echo $this->Form->select ('printers.receipt.port',
																['none' => __ ('None'),
																 'TCP:' => __ ('LAN/WiFi'),
																 'BT:' => __ ('Blue Tooth'),
																 'USB:' => __ ('USB')],
																['value' => $printerPort,
																 'class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
				<div class="col-sm-1"></div>		  
				<div class="col-sm-3">		  
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="show_previous_receipt" id="show_previous_receipt" type="checkbox"<?php if ($params ['show_previous_receipt']) echo ' checked'; ?>>
						  <label for="show_previous_receipt"><?= __ ('Display last receipt') ?></label>
					 </div>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Label Printer') ?></div>
				<div class="col-sm-3">
					 
					 <?php echo $this->Form->select ('printers.label.model',
																['none' => __ ('None'),
																 'zebra_zd500_300_dpi' => __ ('Zebra ZD 500 300 DPI'),
																 'zebra_zd500_203_dpi' => __ ('Zebra ZD 500 203 DPI'),
																 'zebra_zd410' => __ ('Zebra ZD 410'),
																 'other' => __ ('Other')],
																['class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
				<div class="col-sm-1"></div>		  
				<div class="col-sm-3">		  
					 <div class="checkbox checkbox-primary">						  
						  <input type="checkbox" class="styled" name="confirm_tender" id="confirm_tender" type="checkbox"<?php if ($params ['confirm_tender']) echo ' checked'; ?>>
						  <label for="confirm_tender"><?= __ ('Confirm tender') ?></label>
					 </div>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Label Printer Connection') ?></div>
				<div class="col-sm-3">

					 <?php echo $this->Form->select ('printers.label.port',
																['none' => __ ('None'),
																 'TCP:' => __ ('LAN/WiFi'),
																 'BT:' => __ ('Blue Tooth'),
																 'USB:' => __ ('USB')],
																['class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
				<div class="col-sm-1"></div>		  
				<div class="col-sm-3">		  
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="surveillance" id="surveillance" type="checkbox"<?php if ($params ['surveillance']) echo ' checked'; ?>>
						  <label for="surveillance"><?= __ ('Enable surveillancer') ?></label>
					 </div>
				</div>
		  </div>

		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Kitchen Printer') ?></div>
				<div class="col-sm-3">
					 
					 <?php echo $this->Form->select ('printers.kitchen.model',
																['none' => __ ('None'),
																 'zebra_zd500_300_dpi' => __ ('Zebra ZD 500 300 DPI'),
																 'zebra_zd500_203_dpi' => __ ('Zebra ZD 500 203 DPI'),
																 'zebra_zd410' => __ ('Zebra ZD 410'),
																 'other' => __ ('Other')],
																['class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-3 text-right"><?= __('Kitchen Printer Connection') ?></div>
				<div class="col-sm-3">

					 <?php echo $this->Form->select ('printers.kitchen.port',
																['none' => __ ('None'),
																 'TCP:' => __ ('LAN/WiFi'),
																 'BT:' => __ ('Blue Tooth'),
																 'USB:' => __ ('USB')],
																['class' => 'custom-dropdown',
																 'label' => false]); ?>
				</div>
		  </div>


		  
		  <div class="row top30">
				<div class="col-sm-4 text-right"></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->submit ('Save', ['class' => 'btn btn-success btn-block']); ?>
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

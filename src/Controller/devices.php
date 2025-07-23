<?php

/**
 *
 * supported devices
 *
 */

$devices = [
	 'pos' =>
		  ['desc' => __ ('POS'),
			'selected' => null,
			'options' =>
				 ['generic' =>
					  ['name' => 'generic',
						'desc' => __ ('Generic android tablet'),
						'devices' => []],
				  'elo_13' =>
						['name' => 'elo_13',
						 'desc' => __ ('ELO Paypoint 13 inch'),
						 'devices' => ['receipt_printer' => 'elo_13_printer',
											'customer_display' => 'elo_cd',
											'payment' => null]],
				  'elo_15' =>
						['name' => 'elo_15',
						 'desc' => __ ('ELO Paypoint 15 inch'),
						 'devices' => ['receipt_printer' => 'star_micronics',
											'customer_display' => 'elo_cd',
											'payment' => null]],
				  'e700' =>
						['name' => 'e700',
						 'desc' => __ ('PAX E700'),
						 'devices' => ['receipt_printer' => 'pax_e700_printer',
											'payment' => 'pax_q_series',
											'customer_display' => 'generic_android_display']],
				  'e800' =>
						['name' => 'e800',
						 'desc' => __ ('PAX E800'),
						 'devices' => ['receipt_printer' => 'pax_e800_printer',
											'payment' => 'pax_q_series',
											'customer_display' => 'generic_android_display']],
				  'a920' =>
						['name' => 'a920',
						 'desc' => __ ('PAX A920'),
	 					 'devices' => ['receipt_printer' => 'pax_a920_printer',
											'payment' => 'pax_q_series']]]],
	 
	 'receipt_printer' =>
		  ['desc' => __ ('Receipt printer'),
			'selected' => null,
			'options' =>
				 ['star_micronics' =>
					  ['name' => 'star_micronics',
						'desc' => __ ('StarMicronics'),
						'class' => 'cloud.multipos.pos.devices.StarPrinter',
						'params' => []],
				  'citizen' =>
						['name' => 'citizen',
						 'desc' => __ ('Citizen'),
						 'class' => 'cloud.multipos.pos.devices.StarPrinter',
						 'params' => []],
				  'elo_13_printer'  =>
						['name' => 'elo_13_printer',
						 'desc' => __ ('ELO 13 all in one'),
						 'class' => 'cloud.multipos.pos.devices.EloRefreshPrinter'],
				  
				  'elo_15_printer'  =>
						['name' => 'elo_15_printer',
						 'desc' => __ ('ELO 15 all in one'),
						 'class' => 'cloud.multipos.pos.devices.StarPrinter'],
				  'pax_e700_printer'  =>
						['name' => 'pax_e700_printer',
						 'desc' => __ ('PAX E700 printer'),
						 'class' => 'cloud.multipos.pos.devices.PaxE700Printer'],
				  'pax_e800_printer'  =>
						['name' => 'pax_e800_printer',
						 'desc' => __ ('PAX E800 printer'),
						 'class' => 'cloud.multipos.pos.devices.PaxE800Printer'],
				  'pax_a920_printer'  =>
						['name' => 'pax_a920_printer',
						 'desc' => __ ('PAX A920 printer'),
						 'class' => 'cloud.multipos.pos.devices.PaxA920Printer']]],
	 
	 'customer_display' =>
		  ['desc' => __ ('Customer display'),
			'selected' => null,
			'options' =>
				 ['elo_cd' =>
					  ['name' => 'elo_cd',
						'desc' => __ ('ELO LCD Display'),
						'class' => 'cloud.multipos.pos.devices.EloCustomerDisplay'],
				  'android_display' => ['name' => 'android_display',
												'desc' => __ ('Generic tablet'),
												'class' => 'cloud.multipos.pos.views.CustomerFacingDisplay']]],
	 
	 'scanner' =>
		  ['desc' => __ ('Barcode scaner'),
			'selected' => null,
			'options' =>
				 ['keyboard' =>
					  ['name' => 'keyboard',
						'desc' => __ ('Keyboard/USB'),
						'class' => 'cloud.multipos.pos.devices.KeyboardScanner',
						'params' => []]]],
	 'scales' =>
		  ['desc' => __ ('Scales'),
			'selected' => null,
			'options' =>
				 ['scales' =>
					  ['name' => 'scales',
						'desc' => __ ('Star scales'),
						'class' => 'cloud.multipos.pos.devices.StarScales',
						'params' => []]]],
	 
	 'payment' =>
		  ['desc' => __ ('Payment'),
			'selected' => null,
			'options' =>
				 ['pax_q_series' =>
					  ['name' => 'pax_q_series',
						'desc' => __ ('PAX Q Series'),
						'class' => 'cloud.multipos.pos.devices.PaxPayment',
						'params' => []],
				  'pax_s300' =>
						['name' => 'pax_s300',
						 'desc' => __ ('PAX S300'),
						 'class' => 'cloud.multipos.pos.devices.PaxPayment',
						 'params' => []]]
		  ]
];
?>

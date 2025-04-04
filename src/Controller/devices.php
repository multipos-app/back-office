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
						'desc' => __ ('Generic android tablet')],
				  'elo_13' =>
						['name' => 'elo_13',
						 'desc' => __ ('ELO Paypoint 13 inch'),
						 'devices' => ['receipt_printer' => ['name' => '',
																		 'desc' => __ (''),
																		 'class' => 'cloud.multipos.pos.devices.EloRefreshPrinter'],
											'customer_display' => ['name' => '',
																		  'desc' => __ (''),
																		  'class' => 'cloud.multipos.pos.devices.EloCustomerDisplay']]],
				  'elo_15' =>
						['name' => 'elo_15',
						 'desc' => __ ('ELO Paypoint 15 inch'),
						 'devices' => ['receipt_printer' => ['name' => '',
																		 'desc' => __ (''),
																		 'class' => 'cloud.multipos.pos.devices.StarPrinter'],
											'customer_display' => ['name' => '',
																		  'desc' => __ (''),
																		  'class' => 'cloud.multipos.pos.devices.EloCustomerDisplay']]],
				  'e700' =>
						['name' => 'e700',
						 'desc' => __ ('PAX E700'),
						 'devices' => ['receipt_printer' => ['name' => '',
																		 'desc' => __ (''),
																		 'class' => 'cloud.multipos.pos.devices.PaxE700Printer'],
											'customer_display' => ['name' => '',
																		  'desc' => __ (''),
																		  'class' => 'cloud.multipos.pos.views.CustomerFacingDisplay'],
											'payment' => ['name' => '',
															  'desc' => __ (''),
															  'class' => 'cloud.multipos.pos.devices.PaxPayment']]],
				  'e800' =>
						['name' => 'e800',
						 'desc' => __ ('PAX E800'),
						 'devices' => ['receipt_printer' => ['name' => '',
																		 'desc' => __ (''),
																		 'class' => 'cloud.multipos.pos.devices.PaxE800Printer'],
											'customer_display' => ['name' => '',
																		  'desc' => __ (''),
																		  'class' => 'cloud.multipos.pos.views.CustomerFacingDisplay'],
											'payment' => ['name' => '',
															  'desc' => __ (''),
															  'class' => 'cloud.multipos.pos.devices.PaxPayment']]],
				  'a920' =>
						['name' => 'a920',
						 'desc' => __ ('PAX A920'),
	 					 'devices' => ['receipt_printer' => ['name' => '',
																		 'desc' => __ (''),
																		 'class' => 'cloud.multipos.pos.devices.PaxA920Printer'],
											'payment' => ['name' => '',
															  'desc' => __ (''),
															  'class' => 'cloud.multipos.pos.devices.PaxPayment']]]]],
	 
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
				  'epson' =>
						['name' => 'epson',
						 'desc' => __ ('Epson'),
						 'class' => 'cloud.multipos.pos.devices.StarPrinter',
						 'params' => []]]],

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
				 ['keyboard' =>
					  ['name' => 'scales',
						'desc' => __ ('Star scales'),
						'class' => 'cloud.multipos.pos.devices.StarScales',
						'params' => []]]],
	 	 
	 'payment' =>
		  ['desc' => __ ('Payment'),
			'selected' => null,
			'options' =>
				 ['pax' =>
					  ['name' => 'pax',
						'desc' => __ ('PAX/EPX'),
						'class' => 'cloud.multipos.pos.devices.PaxPayment',
						'params' => []]]
		  ]
];
?>

<?php 

$menus = [['type' => 'menu',
			  'controller' => 'sales',
			  'icon' => 'fa-dollar-sign',
			  'text' => __ ('Sales Summary')],
			 ['type' => 'menu',
			  'controller' => 'item-history',
			  'icon' => 'fa-rectangle-vertical-history',
			  'text' => __ ('Item History')],
			 ['type' => 'menu',
			  'controller' => 'hourly',
			  'icon' => 'fa-clock',
			  'text' => __ ('Hourly Sales')],
			 ['type' => 'menu',
			  'controller' => 'tickets',
			  'icon' => 'fa-receipt',
			  'text' => __ ('Transactions')],
			 ['type' => 'submenu',
			  'text' => __ ('Store Data'),
			  'icon' => 'fa-database',
			  'submenu' => [['type' => 'menu',
								  'controller'=> 'items',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Items')],
								 ['type' => 'menu',
								  'controller'=> 'departments',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Departments')],
								 ['type' => 'menu',
								  'controller'=> 'discounts',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Discounts')],
								 ['type' => 'menu',
								  'controller'=> 'inventory',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Inventory')],
								 ['type' => 'menu',
								  'controller'=> 'tax-groups',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Tax')],
								 ['type' => 'menu',
								  'controller'=> 'employees',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Employees')],
								 ['type' => 'menu',
								  'controller'=> 'profiles',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Employee Profiles')],
								 ['type' => 'menu',
								  'controller'=> 'customers',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Customers')],
								 ['type' => 'menu',
								  'controller'=> 'suppliers',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Suppliers')]]],
								 /* ['type' => 'menu',
									 'controller'=> 'import',
									 'icon' => 'fa-arrow-right',
									 'text' => __ ('Import')],
									 ['type' => 'menu',
									 'controller'=> 'import',
									 'icon' => 'fa-arrow-right',
									 'text' => __ ('Import')]]],*/
	 		 ['type' => 'submenu',
			  'text' => __ ('Store Operations'),
			  'icon' => 'fa-wrench',
			  'submenu' => [['type' => 'menu',
								  'controller'=> 'business-units/batches',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Batches')],
								 ['type' => 'menu',
								  'controller'=> 'business-units/receipts',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('Receipt Setup')],
								 /* ['type' => 'menu',
									 'controller'=> 'videos',
									 'icon' => 'fa-arrow-right',
									 'text' => __ ('Surveilence Video')],*/
								 ['type' => 'menu',
								  'controller'=> 'business-units/pos',
								  'icon' => 'fa-arrow-right',
								  'text' => __ ('POS')]]],
			 ['type' => 'menu',
			  'controller' => 'pos-configs',
			  'icon' => 'fa-cash-register',
			  'text' => __ ('POS Builder')],
			 ['type' => 'menu',
			  'controller'=> 'business-units',
			  'icon' => 'fa-file-user',
			  'text' => __ ('Account')]];

?>

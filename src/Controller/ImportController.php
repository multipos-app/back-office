<?php
/**
 *
 * Copyright (C) 2022 multiPos, LLC
 * <http://www.posAppliance.com>
 * <http://www.multiPos.cloud>
 *
 * All rights are reserved. No part of the work covered by the copyright
 * hereon may be reproduced or used in any form or by any means graphic,
 * electronic, or mechanical, including photocopying, recording, taping,
 * or information storage and retrieval systems -- without written
 * permission signed by an officer of multiPos, LLC.
 *
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Controller\PosAppController;
use Cake\Datasource\ConnectionManager;

class ImportController extends PosAppController {
    
    public function initialize (): void  {
        
        parent::initialize ();
    }
    
    function index (...$params) {
		  
		  if (!empty ($this->request->getData ())) {

				$this->debug ($this->request->getData ());
				$this->debug ($_FILES);

				switch ($this->request->getData () ['import_action']) {

					 case 'items_csv':

						  $handle = fopen ($_FILES ['import_file'] ['tmp_name'], "r");
						  $this->itemsCSV ($handle);
						  break;
						  
					 case 'items_json':

						  $this->itemsJSON ($_FILES ['import_file'] ['tmp_name'], $this->request->getData ());
						  break;
				}

				return $this->redirect ('/imports');
		  }
		  
        $imports = [null => __ ('Select import type'),
                    'items_csv' => __ ('CSV file'),
                    'items_json' => __ ('JSON file'),
                    'customers' => __ ('Customers')];
        
		  $departments = [null => __ ('Department'),
								0 => __('Auto')];
		  
        $query = TableRegistry::get ('Departments')
										->find ()
										->order (['department_desc' => 'asc']);
        
        foreach ($query as $d) {
            
            $departments [$d ['id']] = $d ['department_desc'];
        }
		  
		  $query = TableRegistry::get ('TaxGroups')->find ()->contain (['Taxes']);
        $taxGroups = [null => __ ('Tax'),
							 0 => __ ('Tax Exempt')];
		  
        foreach ($query as $taxGroup) {
            
            $taxGroups [$taxGroup ['id']] = $taxGroup ['short_desc'];
        }
		  
		  $suppliers = [0 => 'Suppliers'];
        $query = TableRegistry::get ('Suppliers')
										->find ()
										->order (['supplier_name' => 'asc']);
        
        foreach ($query as $d) {
            
            $suppliers [$d ['id']] = $d ['supplier_name'];
        }
		  
		  $this->set (['imports' => $imports,
							'departments' => $departments,
							'taxGroups' => $taxGroups,
							'suppliers' => $suppliers]);
	 }
    
    private function itemsCSV ($handle) {
		  
        while (($csv = fgetcsv ($handle, 1000, ",")) !== false) {

            $this->debug ($csv);
            
            if (strlen ($csv [0])) {

					 switch ($csv [0]) {

						  case 'H':

								continue;  // skip header
								
						  case 'I';
								$dept = $csv [5];
								$departmentID = 0;
								$departmentsTable = TableRegistry::get ('Departments');
								$department = $departmentsTable->find ()
																		 ->where (['department_desc' => $dept])
																		 ->first ();
								if (!$department) {

									 $this->debug ("add department... $dept");
									 
									 $department = $departmentsTable->newEntity (['department_desc' => $dept]);
									 $department = $departmentsTable->save ($department);
									 $departmentID = $department ['id'];
								}
								else {

									 $departmentID = $department ['id'];
									 $this->debug ("department... $dept $departmentID");
								}
								
								$sup = trim ($csv [7]);
								$supplierID = 0;
								$suppliersTable = TableRegistry::get ('Suppliers');
								$supplier = $suppliersTable->find ()
																	->where (['supplier_name' => $sup])
																	->first ();
								if (!$supplier) {

									 $this->debug ("add supplier... $sup");
									 $supplier = $suppliersTable->newEntity (['supplier_desc' => $sup]);
									 $supplier = $suppliersTable->save ($supplier);

									 $this->debug ('sup... ' . gettype ($supplier));
									 // $supplierID = $supplier ['id'];
								}
								else {
									 
									 $supplierID = $supplier ['id'];
								}

								$this->debug ("adds... $departmentID $supplierID");
								
								$import = ['item_desc' => strtoupper (preg_replace ('/\s+/', ' ', preg_replace ('/\'|%/', '', $csv [1]))),  // clean up description
											  'sku' => trim ($csv [2]),
											  'department_id' => $departmentID,
											  'tax_group_id' => 0,
											  'supplier_id' => $supplierID,
											  'price' => str_replace ('$', '', $csv [3]),
											  'cost' => str_replace ('$', '', $csv [3]),
											  'image' => null,
											  'meta_data' => '{}'];
								
								$this->importItem ($import, ['items' => TableRegistry::get ('Items'),
																	  'item_prices' =>TableRegistry::get ('ItemPrices'),
																	  'inv_items' => TableRegistry::get ('InvItems')]);
								break;  // import item
					 }
				}
		  }
	 }
	 
	 private function itemsJSON ($fname, $request) {
		  
		  $results = '';
		  $added = 0;
		  $updated = 0;
		  $fail = 0;
		  $this->set ('results', $results);
		  

		  $fields = null;
		  $delim = ',';
		  $lineNo = 0;

		  $json = json_decode (file_get_contents ($fname), true);

		  $tables = ['items' => TableRegistry::get ('Items'),
						 'item_prices' =>TableRegistry::get ('ItemPrices'),
						 'inv_items' => TableRegistry::get ('InvItems')];
		  
		  foreach ($json as $j) {

				$lineNo ++;

				if ($lineNo == 2)  {

					 // break;
				}
				
				$import = ['item_desc' => $j ['item_desc'],
							  'sku' => strtoupper ($j ['sku']),
							  'department_id' => $request ['department_id'],
							  'tax_group_id' => $request ['tax_group_id'],
							  'supplier_id' => $request ['supplier_id'],
							  'price' => $j ['price'],
							  'image' => $j ['image'],
							  'meta_data' => $j ['meta_data']];
				
				$this->importItem ($import, $tables);
		  }
	 }

	 private function importItem ($import, $tables) {

		  $this->debug ($import);
		  
		  $price = $import ['price'];
		  $image = $import ['image'];
		  $supplierID = $import ['supplier_id'];
		  unset ($import ['price']);
		  unset ($import ['image']);
		  unset ($import ['supplier_id']);

		  $item = $tables ['items']->find ()
											->where (['sku' => $import ['sku']])
											->first ();
		  if ($item) {
				
				$item ['item_desc'] = $import ['item_desc'];
				$item ['department_id'] = $import ['department_id'];
				$item ['meta_data'] = json_encode ($import ['meta_data']);
				$tables ['items']->save ($item);

				$itemPrice = $tables ['item_prices']->find ()
																->where (['item_id' => $item ['id']])
																->first ();
				
				if ($itemPrice) {
					 
					 $itemPrice ['item_id'] = $item ['id'];
					 $itemPrice ['tax_inclusive'] = 0;
					 $itemPrice ['price'] = $price;
					 $itemPrice ['cost'] = 0;
					 $itemPrice ['pricing'] = '{}';
					 $itemPrice ['metric_typ'] = 5;
					 
					 $this->save ('ItemPrices', $itemPrice);
				}
		  }
		  else {

				$import ['uuid'] = $this->uuid ();
				$import ['meta_data'] = json_encode ($import ['meta_data']);
				$item = $tables ['items']->save ($tables ['items']->newEntity ($import));
				
				$tables ['item_prices']->save ($tables ['item_prices']->newEntity (['item_id' => $item ['id'],
																										  'tax_group_id' => $import ['tax_group_id'],
																										  'business_unit_id' => $this->merchant ['bu_id'],
																										  'tax_inclusive' => 0,
																										  'tax_exempt' => 0,
																										  'price' => $price,
																										  'cost' => 0,
																										  'class' => 'standard',
																										  'pricing' => '{}',
																										  'metric_type' => 5]));
				
				$tables ['inv_items']->save ($tables ['inv_items']->newEntity (['item_id' => $item ['id'],
																									 'business_unit_id' => $this->merchant ['bu_id'],
																									 'supplier_id' => $supplierID]));
		  }

		  // $this->image ($item ['sku'], $image);  // get the image
		  
		  $this->debug ($item);
	 }
	 
	 private function createSKU ($len, $itemsTable) {
		  
		  $sku = str_pad ('', $len, '0');
		  
		  $item = $itemsTable->find ()
									->where (["length(sku) = $len"])
									->order ('id desc')
									->limit (1)
									->first ();
		  
		  if ($item) {

				$sku = strval (intval ($item ['sku']) + 1);
		  }
		  
		  return $sku;
	 }

	 /**
	  *
	  * get and save an image
	  *
	  **/
	 
	 private function image ($sku, $url) {

		  $merchantID = $this->merchant ['merchant_id'];
		  $tmpfname = tempnam ("/tmp", "img-");
		  $fp = fopen ($tmpfname, 'wb');
		  
		  $ch = curl_init ($url);
		  curl_setopt ($ch, CURLOPT_FILE, $fp);
		  curl_setopt ($ch, CURLOPT_HEADER, 0);
		  $result = curl_exec ($ch);
		  
		  curl_close ($ch);
		  fclose ($fp);
		  
		  $dir = "/data/images/$merchantID/";
		  
		  if (!file_exists ($dir)) {
				
				mkdir ($dir, 0755, true);
		  }
		  
		  $cmd = "/usr/bin/convert $tmpfname -resize \"150x200!\" /data/images/$merchantID/$sku.png";
		  $result = exec ($cmd);
	 }
}

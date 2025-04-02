<?php
/**
 * Copyright (C) 2023 multiPos, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Number;
use App\Controller\PosAppController;
use Cake\Datasource\ConnectionManager;

class ItemsController extends PosAppController {

    public $paginate = ['limit' => 25];

    public function initialize (): void {
		  
        parent::initialize ();
    }
    
    public function index (...$args) {
		  
		  $where = [];
		  $join = [];
        $q = false;
        $itemsTable = TableRegistry::get ('Items');

		  $where []  =['enabled' => '1'];
		  
        /* if ($this->merchant ['bu_index'] > 0) {
			  
			  $join [] = ['table' => 'item_prices',
			  'type' => 'right',
			  'conditions' => ['item_prices.business_unit_id' => $this->merchant ['bu_id'],
			  'Items.id = item_prices.item_id']];
			  }*/
		  
        if (count ($args) > 0) {
            
            switch ($args [0]) {
                    
					 case 'id':

						  $where [] = ['id' => $args [1]];
						  break;
						  
					 case 'item_desc':

						  $search = $args [1];
                    $where [] = ["item_desc like '%$search%' or sku like '%$search%'"];
						  break;
						  
					 case 'department_id':
						  
                    $where [] = ["Items.department_id = '".$args [1]."'"];
						  break;

					 case 'pricing':

						  /* $join ['conditions'] [] = ['item_prices.class' => $args [1]];
							*/
						  
						  $join [] = ['table' => 'item_prices',
										  'type' => 'right',
										  'conditions' => ['item_prices.class' => $args [1],
																 'Items.id = item_prices.item_id']];
						  break;
            }
				
				$q = $itemsTable->find ()
									 ->distinct ()
                            ->where ($where)
 									 ->join ($join)
                            ->contain (['ItemPrices', 'InvItems']);
		  }
        else {

            $q = $itemsTable
               ->find ()
					->where ($where)
					->join ($join)
					->order (['sku asc'])
               ->contain (['ItemPrices', 'InvItems']);
        }
		  
        $items = $this->paginate ($q);
		  
        $departments = [null => __ ('Department')];
        $query = TableRegistry::get ('Departments')
										->find ()
										->order (['department_desc' => 'asc']);
        
        foreach ($query as $d) {
            
            $departments [$d ['id']] = $d ['department_desc'];
        }
        
        $suppliers = [0 => ''];
        $query = TableRegistry::get ('Suppliers')
										->find ()
										->order (['supplier_name' => 'asc']);
        
        foreach ($query as $d) {
            
            $suppliers [$d ['id']] = $d ['supplier_name'];
        }
        
        $pricingOptions = ['none' => __ ('Add Item'),
									'standard' => 'Standard pricing, one price per item',
									'variant' => 'Variant pricing (size, color...)',
									'open' => 'Open/enter price, prompt for price',
									'metric' => 'Price by volume/weight, prompt for value'];

		  $this->set (['departments' => $departments,
							'suppliers' => $suppliers,
							'pricingOptions' => $pricingOptions,
							'items' => $items]);
    }
	 
    /**
     *
     *
     *
     */
    
    public function edit ($id, $pricing = 'standard') {

		  $item = null;

		  $this->debug ($this->request->getData ());
		  
		  if (!empty ($this->request->getData ())) {
				
				$update = $this->update ($id, $this->request->getData ());

				$this->ajax (['status' => 0,
								  'item' => $update]);
				return;
		  }

		  if ($id == 0) {

				$item = $this->$pricing ();
		  }
		  else {

				$itemsTable = TableRegistry::get ('Items');
				
            $item = $itemsTable->find ()
										 ->where (['id' => $id])
										 ->contain (['ItemPrices', 'ItemLinks', 'InvItems'])
										 ->first ();
            if (!$item) {
					 
                $this->log ('invalid item in edit... ' . $id, 'error');
                return $this->redirect ('/items');
            }

				if (count ($item ['item_links']) > 0) {

					 foreach ($item ['item_links'] as &$link) {

						  $itemLink = $itemsTable->find ()
														 ->where (['id' => $link ['item_link_id']])
														 ->first ();

						  if ($itemLink) {
								
								$link ['name'] = $itemLink ['sku'] . ' - ' . $itemLink ['item_desc'];
						  }
					 }
				}
				
				$pricing = $item ['item_prices'] [$this->merchant ['bu_index']] ['class'];

				$item = $this->$pricing ($item);
        }
        
        // setup for view

		  $buID = $this->merchant ['business_units'] [$this->merchant ['bu_index']];
		  $buIndex = $this->merchant ['bu_index'];
        $query = TableRegistry::get ('TaxGroups')->find ()->contain (['Taxes']);
        $taxGroups = [0 => __ ('Tax Exempt')];
		  
        foreach ($query as $taxGroup) {
            
            $taxGroups [$taxGroup ['id']] = $taxGroup ['short_desc'];
        }
		  
        $departments = ['' => ''];
        $query = TableRegistry::get ('Departments')
										->find ()
										->order (['department_desc' => 'asc']);
        
        foreach ($query as $d) {
            
            $departments [$d ['id']] = $d ['department_desc'];
        }
        
        $suppliers = [0 => __ ('Suppliers')];
        $query = TableRegistry::get ('Suppliers')
										->find ();
        
        foreach ($query as $s) {
            $suppliers [$s ['id']] = $s ['supplier_name'];
        }
        
        $linkTypes = [0 => __ ('Deposit'),
                      1 => __ ('Package'),
                      2 => __ ('Free')];
		  
        $measures = [null => __ ('Volume measure'),
							'lb' => __ ('Price per pound'),
                     'liter' => __ ('Price per liter'),
                     'oz' => __ ('Price per ounce'),
							'gram' => 'Price per gram'];
		  
        $decimalPlaces = [null => __ ('Decimal places'),
								  '0' => __ ('None'),
								  '1' => __ ('1'),
 								  '2' => __ ('2'),
								  '3' => __ ('3')];

 		  $this->set (['item' => $item,
							'departments' => $departments,
							'taxGroups' => $taxGroups,
							'linkTypes' => $linkTypes,
							'suppliers' => $suppliers,
							'measures' => $measures,
							'decimalPlaces' => $decimalPlaces]);

		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Items')
								->setTemplate ($item ['template']);

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }
    
	 /**
     *
     * update item
     *
     */
    
    public function update ($id, $item) {
		  
		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';
		  
		  $itemsTable = TableRegistry::get ('Items');
		  $itemPricesTable = TableRegistry::get ('ItemPrices');
		  $invItemsTable = TableRegistry::get ('InvItems');
		  $status = 1;
		  
		  if ($id == 0) {
				
				$itemPrice = $item ['item_price'];
				$invItem =  $item ['inv_item'];
				unset ($item ['item_price']);
				unset ($item ['inv_item']);
				
				$newItem = $itemsTable->newEmptyEntity ();
				$newItem ['sku'] = $item ['sku'];
				$newItem ['item_desc'] = strtoupper ($item ['item_desc']);
				$newItem ['department_id'] = $item ['department_id'];
				
				if ($itemsTable->save ($newItem)) {

					 $status = 0;
				}

				$id = $newItem ['id'];

				$pricing = [];
				switch ($itemPrice ['class']) {

					 case 'standard':
					 case 'open':

						  // no pricing parameters
						  break;
						  
					 case 'metric':

						  $pricing = ['price' => $itemPrice ['price'],
										  'cost' => $itemPrice ['cost'],
										  'metric' => $itemPrice ['metric'],
										  'decimal_places' => $itemPrice ['decimal_places']];
						  break;
						  
					 case 'variant':

						  $pricing = $itemPrice ['pricing'];
						  break;
				}
				
				foreach ($this->merchant ['business_units'] as $bu) {
					 
					 if ($bu ['business_type'] == BU_LOCATION) {
						  
						  $itemPrice = ['item_id' => $id,
											 'business_unit_id' => $bu ['id'],
											 'class' => $itemPrice ['class'],
											 'pricing' => json_encode ($pricing),
											 'price' => isset ($itemPrice ['price']) ? $itemPrice ['price'] : 0,
											 'cost' => isset ($itemPrice ['cost']) ? $itemPrice ['cost'] : 0,
											 'tax_group_id' => $itemPrice ['tax_group_id']];
						  
						  $itemPrice = $itemPricesTable->newEntity ($itemPrice);
						  $itemPricesTable->save ($itemPrice);

						  $invItem = ['item_id' => $id,
										  'business_unit_id' => $bu ['id'],
										  'package_quantity' => $invItem ['package_quantity'],
										  'on_hand_req' => $invItem ['on_hand_req'],
										  'on_hand_count' => $invItem ['on_hand_count'],
										  'supplier_id' => $invItem ['supplier_id']];
						  
						  $invItem = $invItemsTable->newEntity ($invItem);
						  $invItemsTable->save ($invItem);
					 }
				}
				
				// get an image
				
				$this->image ($item);
				
				$this->batch ($newItem ['id']);
				return $newItem;
		  }
		  else {
				
				$itemsTable->updateAll (['sku' => $item ['sku'],
												 'item_desc' => strtoupper ($item ['item_desc']),
												 'department_id' => $item ['department_id'],
												 'locked' => 0,
												 'enabled' => isset ($item ['enabled']) ? 1 : 0],
												['id' => $id]);
		  		
				$ip = [];
				
				switch ($item ['item_price'] ['class']) {

					 case 'standard':

						  $ip = ['class' => $item ['item_price'] ['class'],
									'price' => $item ['item_price'] ['price'],
									'cost' => $item ['item_price'] ['cost'],
									'pricing' => '{}',
									'tax_group_id' => $item ['item_price'] ['tax_group_id'],
									'tax_exempt' => $item ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
									'tax_inclusive' => isset ($item ['tax_inclusive']) ? 1 : 0];
						  break;

					 case 'variant':

						  foreach ($item ['item_price'] ['pricing'] ['variants'] as &$variant) {

								$variant ['desc'] = strtoupper ($variant ['desc']);
						  }
						  
						  $ip = ['class' => $item ['item_price'] ['class'],
									'pricing' => json_encode ($item ['item_price'] ['pricing']),
									'tax_group_id' => $item ['item_price'] ['tax_group_id'],
									'tax_exempt' => $item ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
									'tax_inclusive' => isset ($item ['item_price'] ['tax_inclusive']) ? 1 : 0];
						  break;
						  
					 case 'metric':

						  $ip = ['class' => $item ['item_price'] ['class'],
									'price' => $item ['item_price'] ['price'],
									'cost' => $item ['item_price'] ['cost'],
									'pricing' => json_encode ($item ['item_price']),
									'tax_group_id' => $item ['item_price'] ['tax_group_id'],
									'tax_exempt' => $item ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
									'tax_inclusive' => isset ($item ['item_price'] ['tax_inclusive']) ? 1 : 0];
						  break;
						  
					 case 'open':
						  
						  $ip = ['class' => $item ['item_price'] ['class'],
									'price' => 0,
									'cost' => 0,
									'pricing' => '{}',
									'tax_group_id' => $item ['item_price'] ['tax_group_id'],
									'tax_exempt' => $item ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
									'tax_inclusive' => isset ($item ['item_price'] ['tax_inclusive']) ? 1 : 0];
						  break;

				}
				
				$where = ['item_id' => $item ['id']];  // update all locations

				if ($this->merchant ['bu_index'] > 0) {  // update location only

					 $where ['business_unit_id'] = $this->merchant ['bu_id'];
				}

				
				$itemPricesTable->updateAll ($ip, $where);
				
				// update inventory only if a specific location is selected

				if ($this->merchant ['bu_index'] > 0) {

					 $invItem = $invItemsTable->find ()
													  ->where (["item_id = $id"])
													  ->first ();
					 
					 if ($invItem) {
						  
						  $invItemsTable->updateAll (['package_quantity' => $item ['inv_item'] ['package_quantity'],
																'on_hand_req' => $item ['inv_item'] ['on_hand_req'],
																'on_hand_count' => $item ['inv_item'] ['on_hand_count'],
																'supplier_id' => $item ['inv_item'] ['supplier_id']],
															  [$where]);
					 }
					 else {

						  // create the inventory items
						  
						  foreach ($this->merchant ['business_units'] as $bu) {
								
								if ($bu ['business_type'] == BU_LOCATION) {
									 
									 $invItem = ['item_id' => $id,
													 'business_unit_id' => $bu ['id'],
													 'package_quantity' => $item ['inv_item'] ['package_quantity'],
													 'on_hand_req' => $item ['inv_item'] ['on_hand_req'],
													 'on_hand_count' => $item ['inv_item'] ['on_hand_count'],
													 'supplier_id' => $item ['inv_item'] ['supplier_id']];
									 
									 $invItem = $invItemsTable->newEntity ($invItem);
									 $invItemsTable->save ($invItem);
								}
						  }
					 }
				}
				
				$itemLinksTable = TableRegistry::get ('ItemLinks');
				$itemLinksTable->deleteAll (['item_id' => $id]);
				
				if (isset ($item ['item_links']) && (count ($item ['item_links'])) > 0) {
					 
					 foreach ($item ['item_links'] as $itemLink) {

						  $il = ['item_id' => $id,
									'item_link_id' => $itemLink ['item_link_id'],
									'link_type' =>$itemLink ['link_type']];
						  
						  $link = $itemLinksTable->newEntity ($il);
						  $itemLinksTable->save ($link);
					 }
				}

				// get an image
				
				$this->image ($item);

				$this->batch ($item ['id']);
				return $item;
        }
    }
    
    /**
     *
     * delete an item
     *
     */
    
    public function disable ($id) {
        
        $status = 1;
		  
        if ($id > 0) {
				
            TableRegistry::get ('Items')->updateAll (['enabled' => 0],
																	  ['id' => $id]);
				$this->batch ($id);
				$status = 0;
		  }
		  
		  $this->ajax (['status' => $status]);
	 }
	 
    /**
     *
     * edit a menu item
     *
     */
    
    public function menuItem ($sku) {

        $itemsTable = TableRegistry::get ('Items');
		  $item = TableRegistry::get ('Items')
									  ->find ()
									  ->where (['sku' => $sku])
									  ->first ();

		  return $this->edit ($item ['id'], null, false);
	 }

	 /**
     *
     * gather items for type ahead/drop down select
     *
     */
    
    public function itemTypeahead ($desc) {
		  
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];

        if (strlen ($desc) > 0) {

            $where = ["or" => ["item_desc like '" . $desc."%'", "sku like '" . $desc."%'"]];
            
            $query = TableRegistry::get ('Items')
											 ->find ()
											 ->where ($where)
											 ->limit (5);

            $response = [];
            foreach ($query as $item) {
                
                $response [] = ['id' => $item ['id'],
                                'sku' => $item ['sku'],
                                'name' => $item ['item_desc'] . ' - ' . $item ['sku']];
            }
        }
		  
        $this->set (['response' => $response]);
    }
	 
    /**
     *
     * create linked item
     *
     **/

    function addLink ($itemID, $linkID, $linkType) {
		  
        $link = TableRegistry::get ('Items')
									  ->find ()
									  ->where (['id' => $linkID])
									  ->first ();

        $link ['link_type'] = $linkType;
		  
        $this->set (['response' => ['status' => 0,
                                    'link' => $link]]);
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
    }
    
    /**
     *
     * remove link 
     *
     **/

    function delLink ($linkID) {
		  
        $itemLinksTable = TableRegistry::get ('ItemLinks');
        $itemLink = $itemLinksTable->find ()->where (['id' => $linkID])->first ();
        if ($itemLinksTable->delete ($itemLink)) {
        }

        $this->set (['response' => ['status' => 0]]);
    }
	 
    /**
     *
     * return linked items
     *
     **/

    public function getLinks ($desc) {
        
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];

        if (strlen ($desc) > 0) {
				
            $query = TableRegistry::get ('Items')
											 ->find ()
											 ->where (["item_desc like '".$desc."%'"])
											 ->contain ('ItemPrices')
											 ->limit (5);

            $response = [];
            foreach ($query as $item) {
                $response [] = ['id' => $item ['id'],
                                'name' => $item ['item_desc']];
            }
        }
		  
        $this->set (['response' => $response]);
    }
    
	 /**
     *
     * create unique 5 digit sku
     *
     **/
	 
    public function autoSku ($len) {

        $itemsTable = TableRegistry::get ('Items');
		  
		  $sku = str_pad ('', $len, '0');
		  
        $item = $itemsTable
			  ->find ()
			  ->where (["length(sku) = $len"])
			  ->order ('id desc')
			  ->limit (1)
			  ->first ();

		  if ($item) {

				$sku = strval (intval ($item ['sku']) + 1);
		  }
		  
        $this->set (['response' => ['status' => 0,
												'sku' => $sku]]);
    }


	 /**
     *
     * utility methods, queue updates to the POS
     *
     **/

    private function batch ($id) {
        
		  $this->addBatch ('items', $id);
		  $this->notifyPos ();
    }
	 
	 /**
	  *
	  * format a new variant row and send it back to the item variant edit dialog
	  *
	  **/
	 
    public function addVariant () {
		  
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Items')
								->setTemplate ('add_variant');
		  
		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }
    /**
     *
     * Save an update record for the POS
     *
     */

    private function addBatch ($table, $id) {
		  
        $batchEntriesTable = TableRegistry::get ('BatchEntries');
        $batchEntry = $batchEntriesTable->newEntity (['business_unit_id' => $this->merchant ['bu_id'],
                                                      'update_table' => $table,
                                                      'update_id' => $id,
                                                      'update_action' => 0,
                                                      'execution_time' => time ()]);
        $batchEntriesTable->save ($batchEntry);
    }
	 
	 private function standard ($item = false) {

		  if (!$item) {
				
				$item = ['id' => 0,
							'sku' => '',
							'item_desc' => '',
							'department_id' => 0,
							'locked' => false,
							'enabled' => true,
							'item_price' => ['class' => 'standard',
												  'tax_group_id' => 0,
												  'price' => '',
												  'cost' => '',
												  'pricing' => '{"price":"0.00","cost":"0.00"}'],
							'item_links' => [],
							'inv_item' => ['supplier_id' => 0,
												'package_quantity' => 0,
												'on_hand_req' => 0,
												'on_hand_count' => 0]];
		  }
		  else {
				
				$item ['item_price'] = $item ['item_prices'] [0];
 				$item ['inv_item'] = $item ['inv_items'] [0];
				unset ($item ['item_prices']);
				unset ($item ['inv_items']);
		  }
		  
		  $item ['template'] = 'standard_pricing';
		  return $item;
	 }
	 
	 private function metric ($item = false) {
		  
		  if (!$item) {
				
				$item = ['id' => 0,
							'sku' => '',
							'item_desc' => '',
							'department_id' => 0,
							'locked' => false,
							'enabled' => true,
							'item_price' => ['class' => 'metric',
												  'tax_group_id' => 0,
												  'price' => '',
												  'cost' => '',
												  'pricing' => ['metric' => '',
																	 'decimal_places' => '']],
							'item_links' => [],
							'inv_item' => ['supplier_id' => 0,
												'package_quantity' => 0,
												'on_hand_req' => 0,
												'on_hand_count' => 0]];
		  }
		  else {
				$item ['item_price'] = $item ['item_prices'] [0];
				$item ['item_price'] ['pricing'] = json_decode ($item ['item_price'] ['pricing'], true);
				
				if (count ($item ['inv_items'])) {
					 
 					 $item ['inv_item'] = $item ['inv_items'] [0];
				}
				else {
					 
 					 $item ['inv_item'] = ['package_quantity' => 0,
												  'on_hand_req' => 0,
												  'on_hand_count' => 0,
												  'supplier_id' => 0];
				}
				
				unset ($item ['item_prices']);
				unset ($item ['inv_items']);
		  }

		  $item ['template'] = 'metric_pricing';
		  return $item;
	 }

	 private function open ($item = null) {


		  if (!$item) {
				
				$item = ['id' => 0,
							'sku' => '',
							'item_desc' => '',
							'department_id' => 0,
							'locked' => false,
							'enabled' => true,
							'item_price' => ['class' => 'open',
												  'tax_group_id' => 0,
												  'price' => 0,
												  'cost' => 0,
												  'pricing' => '{}'],
							'item_links' => [],
							'inv_item' => ['supplier_id' => 0,
												'package_quantity' => 0,
												'on_hand_req' => 0,
												'on_hand_count' => 0]];
		  }
		  else {
				$item ['item_price'] = $item ['item_prices'] [0];
				$item ['item_price'] ['pricing'] = json_decode ($item ['item_price'] ['pricing'], true);
				
				if (count ($item ['inv_items'])) {
					 
 					 $item ['inv_item'] = $item ['inv_items'] [0];
				}
				else {
					 
 					 $item ['inv_item'] = ['package_quantity' => 0,
												  'on_hand_req' => 0,
												  'on_hand_count' => 0,
												  'supplier_id' => 0];
				}
				
				unset ($item ['item_prices']);
				unset ($item ['inv_items']);
		  }

		  $item ['template'] = 'open_pricing';
		  return $item;
	 }
	 
	 private function variant ($item = null) {
		  
		  if (!$item) {
				
				$item = ['id' => 0,
							'sku' => '',
							'item_desc' => '',
							'department_id' => 0,
							'locked' => false,
							'enabled' => true,
							'item_price' => ['class' => 'variant',
												  'tax_group_id' => 0,
												  'price' => 0,
												  'cost' => 0,
												  'pricing' => ['variants' => []]],
							'item_links' => [],
							'inv_item' => ['supplier_id' => 0,
												'package_quantity' => 0,
												'on_hand_req' => 0,
												'on_hand_count' => 0]];
		  }
		  else {
				
				$item ['item_price'] = $item ['item_prices'] [0];
				$item ['item_price'] ['pricing'] = json_decode ($item ['item_price'] ['pricing'], true);
				
				if (count ($item ['inv_items'])) {
					 
 					 $item ['inv_item'] = $item ['inv_items'] [0];
				}
				else {
					 
 					 $item ['inv_item'] = ['package_quantity' => 0,
												  'on_hand_req' => 0,
												  'on_hand_count' => 0,
												  'supplier_id' => 0];
				}
				
				unset ($item ['item_prices']);
				unset ($item ['inv_items']);
		  }

		  $item ['template'] = 'variant_pricing';
		  return $item;
	 }
	 
	 /**
	  *
	  * get and save an image
	  *
	  **/
	 
	 private function image ($item) {

		  $this->debug ('image...');
		  $this->debug ($item);
		  
	 	  if (isset ($item ['item_url']) && (strlen ($item ['item_url']) > 0)) {

				$merchantID = $this->merchant ['merchant_id'];
				$sku = $item ['sku'];
				$tmpfname = tempnam ("/tmp", "img-");
				$fp = fopen ($tmpfname, 'wb');
				
				$ch = curl_init ($item ['item_url']);
				curl_setopt ($ch, CURLOPT_FILE, $fp);
				curl_setopt ($ch, CURLOPT_HEADER, 0);
				$result = curl_exec ($ch);
				
				$this->debug ("image load... $result");
				
				curl_close ($ch);
				fclose ($fp);
				
				$dir = "/data/images/$merchantID/";
				
				if (!file_exists ($dir)) {
					 
					 mkdir ($dir, 0755, true);
				}
				
				$cmd = "/usr/bin/convert $tmpfname -resize \"150x200!\" /data/images/$merchantID/$sku.png";
				$result = exec ($cmd);
				
				$this->debug ("$cmd... $result");
		  }
	 }
}
?>

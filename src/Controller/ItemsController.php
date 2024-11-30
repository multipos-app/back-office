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

    public $paginate = ['limit' => 25,
                        'order' => ['Items.sku asc']];

    public function initialize (): void {
		  
        parent::initialize ();
        
        // if ($this->merchant ['role'] == 'reports') {
        
        //     return $this->redirect (['controller' => 'sales', 'action' => 'index']);
        // }
    }

    /**
     *
     *
     *
     */
    
    public function index (...$params) {
		  
        $where = [];
        $q = false;
        $itemsTable = TableRegistry::get ('Items');
        
        if (count ($params) > 0) {
            
            switch ($params [0]) {
                    
					 case 'id':
                    
						  $q = TableRegistry::get ('Items')->find ()
                                      ->where (['id' => $params [1]])
                                      ->contain (['ItemPrices', 'InvItems']);
						  break;
						  
					 case 'item_desc':

						  $q = TableRegistry::get ('Items')->find ()
                                      ->where (["item_desc like '" .  $params [1] . "%'"])
                                      ->contain (['ItemPrices', 'InvItems']);						  
						  break;
						  
					 case 'department_id':
						  
						  $q = TableRegistry::get ('Items')->find ()
                                      ->where (["Items.department_id = '".$params [1]."'"])
                                      ->contain (['ItemPrices', 'InvItems']);
						  break;
                    
					 case 'pricing_id':
                    
						  $q = TableRegistry::get ('Items')->find ()
                                      ->where (['item_prices.pricing_id' => $params [1]])
                                      ->contain (['ItemPrices', 'InvItems'])
                                      ->join (['table' => 'item_prices',
                                               'type' => 'left',
                                               'conditions' => 'Items.id = item_prices.item_id']);
						  break;

					 case 'supplier_id':
                    
						  $q = TableRegistry::get ('Items')->find ()
                                      ->where (["Items.supplier_id = '".$params [1]."'"])
                                      ->contain (['ItemPrices', 'InvItems']);
						  break;
            }
        }
        else {

            $q = $itemsTable
               ->find ()
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
        
        $pricingOptions = [null => __ ('Add Item'),
									'standard_pricing' => 'Standard pricing, one price per item',
									'size_pricing' => 'Variant pricing',
									'open_pricing' => 'Open/enter price',
									'metric_pricing' => 'Price by volume/weight'];

		  $data = ['departments' => $departments,
					  'suppliers' => $suppliers,
					  'pricingOptions' => $pricingOptions,
					  'items' => $items];
		  
        return ($this->response (__ ('Items'),
                                 'Items',
                                 'index',
                                 $data,
                                 true,
                                 'ajax',
                                 'items'));
    }
	 
    /**
     *
     *
     *
     */
    
    public function edit ($id, $template = 'standard_pricing', $controls = true) {

		  $this->debug ("edit item... $id $template $controls");
		  
        $item = null;
        $where = false;
        $title = __ ('Edit item');
        
        $id = intVal ($id);
        if ($id > 0) {
            
            $where = ['id' => $id];
        }
        
        if ($where) {
				
            $title = __ ('Edit item');
            $item = TableRegistry::get ('Items')
											->find ()
											->where ($where)
											->contain (['ItemPrices', 'ItemLinks', 'InvItems'])
											->first ();

            if ($item) {

					 $this->debug ($item);

					 $buIndex = 0;
					 if ($this->merchant ['bu_index'] > 0) {

						  $buIndex = $this->merchant ['bu_index'] - 1;
					 }
					 
					 $item ['item_price'] = $item ['item_prices'] [$buIndex];
					 $item ['item_price'] ['pricing'] = json_decode ($item ['item_price'] ['pricing'], true);

					 $template = $item ['item_price'] ['class'] . '_pricing';
					 
					 $item ['inv_item'] = ['supplier_id' => null,
												  'package_quantity' => 0,
												  'on_hand_count' => 0,
												  'on_hand_req' => 0];
					 
					 if (count ($item ['inv_items']) > 0) {

						  $item ['inv_item'] = ['supplier_id' => 0,
														'package_quantity' => 0,
														'on_hand_count' => 0,
														'on_hand_req' => 0];
					 }
					 
					 unset ($item ['item_prices']);
					 unset ($item ['item_links']);
					 unset ($item ['inv_items']);
            }
            else {

                $this->log ('invalid item in edit... ' . $id, 'error');
                return $this->redirect (['action' => 'index']);
            }
        }
        else {
            
            $title = __ ('Add item');
				
				$item = ['id' => 0,
							'sku' => '',
							'item_desc' => '',
							'department_id' => 0,
							'tax_group_id' => 0,
							'tax_inclusive' => 0,
							'enabled' => 1,
							'locked' => 0,
							'item_links' => []];
				
				switch ($template) {

					 case 'standard_pricing':
						  
						  $item ['item_price'] = ['id' => 0,
														  'tax_group_id' => 0,
														  'tax_inclusive' => 0,
														  'tax_exempt' => 0,
														  'class' => 'standard',
														  'price' => 0,
														  'cost' => 0,
														  'pricing' => [],
														  'supplier_id' => 0];
						  break;
						  
					 case 'size_pricing':
						  
						  $item ['item_price'] = ['id' => 0,
														  'tax_group_id' => 0,
														  'tax_inclusive' => 0,
														  'tax_exempt' => 0,
														  'class' => 'size',
														  'price' => 0,
														  'cost' => 0,
														  'pricing' => ['description' => '',
																			 'sizes' => []]];
						  break;
						  
					 case 'open_pricing':
						  
						  $item ['item_price'] = ['id' => 0,
														  'tax_group_id' => 0,
														  'tax_inclusive' => 0,
														  'tax_exempt' => 0,
														  'class' => 'open',
														  'pricing' => []];
						  break;

					 case 'metric_pricing':

						  $item ['item_price'] = ['id' => 0,
														  'tax_group_id' => 0,
														  'tax_inclusive' => 0,
														  'tax_exempt' => 0,
														  'class' => 'metric',
														  'price' => 0,
														  'cost' => 0,
														  'pricing' => ['metric' => null,
																			 'decimal_places' => 1]];
						  break;

				}
				
 				$item ['inv_item'] = ['id' => 0,
											 'package_quantity' => 0,
											 'on_hand_req' => 0,
											 'on_hand_count' => 0,
											 'supplier_id' => 0];
        }
        
        // setup for view

		  $buID = $this->merchant ['business_units'] [$this->merchant ['bu_index']];
		  $buIndex = $this->merchant ['bu_index'];
        $query = TableRegistry::get ('TaxGroups')->find ()->contain (['Taxes']);
        $taxGroups = ['' => '',
                      0 => __ ('Tax Exempt')];
		  
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
								  '1' => __ ('1'),
 								  '2' => __ ('2'),
								  '3' => __ ('3')];
		  
        return ($this->response ($item ['item_desc'],
                                 'Items',
                                 $template,
                                 compact ('item',
                                          'title',
														'buIndex',
                                          'departments',
                                          'suppliers',
                                          'taxGroups',
                                          'linkTypes',
                                          'measures',
														'decimalPlaces',
														'controls')));

    }
    
    /**
     *
     * add/update item
     *
     */
    
    public function item ($id) {
		  
		  $response = ['status' => -1];
		  
        if (!empty ($this->request->getData ())) {

				$this->debug ($this->request->getData ());
				
            if ($id == 0) {
					 
                $status = $this->add ($this->request->getData ());
					 if (isset ($this->request->getData () ['button'])) {
						  
						  $item = $this->request->getData () ['item'];
						  $button = $this->request->getData () ['button'];
						  
						  $button ['text'] = strtoupper ($item ['item_desc']);
						  $button ['params'] ['sku'] = strtoupper ($item ['sku']);

						  $response = ['status' => $status,
											'button' => $button];
						  
					 }
				}
            else {
					 
                $status = $this->update ($this->request->getData ());
					 $response = ['status' => $status];
				}
		  }

		  $this->set ('response', $response);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
	 }


 	 /**
     *
     * add item
     *
     */
    
    private function add ($item) {
		  
 		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';
        
        $itemsTable = TableRegistry::get ('Items');
        $itemPricesTable = TableRegistry::get ('ItemPrices');
        $invItemsTable = TableRegistry::get ('InvItems');
        
        $i = $itemsTable->newEntity (['sku' => $item ['item'] ['sku'],
                                      'item_desc' => strtoupper (str_replace ('"', "`", str_replace ("'", "`", $item ['item'] ['item_desc']))),
                                      'department_id' => intVal ($item ['item'] ['department_id']),
                                      'locked' => 0,
                                      'enabled' => 1]);
        $itemsTable->save ($i);
        $itemID = $i ['id'];
		  
		  foreach ($this->merchant ['business_units'] as $bu) {

				if ($bu ['business_type'] ==  BU_LOCATION) {
					 
					 switch ($item ['item'] ['item_price'] ['class']) {

						  case 'standard':
								
								$ip = ['item_id' => $itemID,
										 'business_unit_id' => $bu ['id'],
										 'price' => floatVal ($item ['item'] ['item_price'] ['price']),
										 'cost' => floatVal ($item ['item'] ['item_price'] ['cost']),
										 'class' => 'standard',
										 'pricing' => '{}',
										 'tax_group_id' => intVal ($item ['item'] ['item_price'] ['tax_group_id']),
										 'tax_exempt' => isset ($item ['item'] ['item_price'] ['tax_exempt']) ? 1 : 0,
										 'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 
								$itemPricesTable->save ($itemPricesTable->newEntity ($ip));
					 
								$ii = $invItemsTable->newEntity (['item_id' => $itemID,
																			 'business_unit_id' => $bu ['id'],
																			 'package_quantity' => intVal ($item ['item'] ['inv_item'] ['package_quantity']),
																			 'on_hand_req' => intVal ($item ['item'] ['inv_item'] ['on_hand_req']),
																			 'supplier_id' => intVal ($item ['item'] ['inv_item'] ['supplier_id'])]);
								$invItemsTable->save ($ii);
								break;

						  case 'size':

								$pricing = json_encode ($item ['item'] ['item_price'] ['pricing']);
								
								$ip = ['item_id' => $itemID,
										 'business_unit_id' => $bu ['id'],
										 'class' => 'size',
										 'pricing' => $pricing,
										 'tax_group_id' => intVal ($item ['item'] ['item_price'] ['tax_group_id']),
										 'tax_exempt' => isset ($item ['item'] ['item_price'] ['tax_exempt']) ? 1 : 0,
										 'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 
								$itemPricesTable->save ($itemPricesTable->newEntity ($ip));
								break;
								
						  case 'open':
								
								$ip = ['item_id' => $itemID,
										 'business_unit_id' => $bu ['id'],
										 'class' => 'open',
										 'pricing' => '{}',
										 'tax_group_id' => intVal ($item ['item'] ['item_price'] ['tax_group_id']),
										 'tax_exempt' => isset ($item ['item'] ['item_price'] ['tax_exempt']) ? 1 : 0,
										 'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 
								$itemPricesTable->save ($itemPricesTable->newEntity ($ip));
								break;
								
						  case 'metric':
								
								$ip = ['item_id' => $itemID,
										 'business_unit_id' => $bu ['id'],
										 'class' => 'metric',
										 'price' => floatVal ($item ['item'] ['item_price'] ['price']),
										 'cost' => floatVal ($item ['item'] ['item_price'] ['cost']),
										 'pricing' => json_encode ($item ['item'] ['item_price'] ['pricing']),
										 'tax_group_id' => intVal ($item ['item'] ['item_price'] ['tax_group_id']),
										 'tax_exempt' => isset ($item ['item'] ['item_price'] ['tax_exempt']) ? 1 : 0,
										 'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 
								$itemPricesTable->save ($itemPricesTable->newEntity ($ip));
								break;
					 }
				}
		  }
        
        if (isset ($item ['item'] ['item_links']) && (count ($item ['item'] ['item_links']) > 0)) {

            $itemLinksTable = TableRegistry::get ('ItemLinks');
            $itemLinksTable->deleteAll (['item_id' => $id]);

            foreach ($item ['item'] ['item_links'] as $itemLink) {

                $link = $itemLinksTable->newEntity (['item_id' => $itemID,
                                                     'item_link_id' => $itemLink ['item_link_id'],
                                                     'link_type' =>$itemLink ['link_type']]);
                $itemLinksTable->save ($link);
            }
        }
        
        $this->batch ($itemID);
        return 0;
    }

	 /**
     *
     * update item
     *
     */
    
    public function update ($item) {

		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';
        $status = -1;

        $itemsTable = TableRegistry::get ('Items');
        $itemPricesTable = TableRegistry::get ('ItemPrices');
        $invItemsTable = TableRegistry::get ('InvItems');
		  
        $itemsTable->updateAll (['sku' => $item ['item'] ['sku'],
                                 'item_desc' => strtoupper (str_replace ('"', "`", str_replace ("'", "`", $item ['item'] ['item_desc']))),
                                 'department_id' => $item ['item'] ['department_id'],
                                 'locked' => 0,
                                 'enabled' => 1],
                                ['id' => $item ['item'] ['id']]);
		  
		  // update pricing
		  
		  $ip = [];
		  
		  switch ($item ['item'] ['item_price'] ['class']) {

				case 'standard':

					 $ip = ['class' => $item ['item'] ['item_price'] ['class'],
							  'price' => $item ['item'] ['item_price'] ['price'],
							  'cost' => $item ['item'] ['item_price'] ['cost'],
							  'pricing' => '{}',
							  'tax_group_id' => $item ['item'] ['item_price'] ['tax_group_id'],
							  'tax_exempt' => $item ['item'] ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
							  'tax_inclusive' => isset ($item ['item'] ['tax_inclusive']) ? 1 : 0];
					 break;

				case 'size':
					 
					 $ip = ['class' => $item ['item'] ['item_price'] ['class'],
							  'pricing' => json_encode ($item ['item'] ['item_price'] ['pricing']),
							  'tax_group_id' => $item ['item'] ['item_price'] ['tax_group_id'],
							  'tax_exempt' => $item ['item'] ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
							  'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 break;
		  
				case 'metric':

					 $ip = ['class' => $item ['item'] ['item_price'] ['class'],
							  'price' => $item ['item'] ['item_price'] ['price'],
							  'cost' => $item ['item'] ['item_price'] ['cost'],
							  'pricing' => json_encode ($item ['item'] ['item_price'] ['pricing']),
							  'tax_group_id' => $item ['item'] ['item_price'] ['tax_group_id'],
							  'tax_exempt' => $item ['item'] ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
							  'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 break;
					 
				case 'open':
					 
					 $ip = ['class' => $item ['item'] ['item_price'] ['class'],
							  'price' => 0,
							  'cost' => 0,
							  'pricing' => '{}',
							  'tax_group_id' => $item ['item'] ['item_price'] ['tax_group_id'],
							  'tax_exempt' => $item ['item'] ['item_price'] ['tax_group_id'] == 0 ? 1 : 0,
							  'tax_inclusive' => isset ($item ['item'] ['item_price'] ['tax_inclusive']) ? 1 : 0];
					 break;

		  }
        
		  $where = ['item_id' => $item ['item'] ['id']];  // update all locations

		  if ($this->merchant ['business_units'] [$this->merchant ['bu_index']] ['business_type'] == BU_LOCATION) {  // update location only

				$where ['business_unit_id'] = $this->merchant ['business_units'] [$this->merchant ['bu_index']] ['id'];
		  }

		  $this->debug ($ip);
		  $this->debug ($where);
		  
        $itemPricesTable->updateAll ($ip, $where);
		  
		  // update inventory

		  if (isset ($item ['item'] ['inv_items'])) {

				$invItem = $invItemsTable->find ()
												 ->where (["item_id = $id"])
												 ->first ();
				
				if ($invItem) {
					 
					 $invItemsTable->updateAll (['package_quantity' => $item ['item'] ['inv_items'] ['package_quantity'],
														  'on_hand_req' => $item ['item'] ['inv_items'] ['on_hand_req'],
														  'on_hand_count' => $item ['item'] ['inv_items'] ['on_hand_count'],
														  'supplier_id' => $item ['item'] ['inv_items'] ['supplier_id']],
														 ['item_id' => $id]);
				}
				else {
					 
					 foreach ($this->merchant ['business_units'] as $bu) {
						  
						  if ($bu ['business_type'] == 2) {
								
								$invItem = ['item_id' => $id,
												'business_unit_id' => $bu ['id'],
												'package_quantity' => $item ['item'] ['inv_items'] ['package_quantity'],
												'on_hand_req' => $item ['item'] ['inv_items'] ['on_hand_req'],
												'on_hand_count' => $item ['item'] ['inv_items'] ['on_hand_count'],
												'supplier_id' => $item ['item'] ['inv_items'] ['supplier_id']];
								
								$invItem = $invItemsTable->newEntity ($invItem);
								$invItemsTable->save ($invItem);
						  }
					 }
				}
		  }
        
        if (isset ($item ['item'] ['item_links']) && (count ($item ['item'] ['item_links'])) > 0) {
            
            $itemLinksTable = TableRegistry::get ('ItemLinks');
            $itemLinksTable->deleteAll (['item_id' => $id]);
            
            foreach ($item ['item'] ['item_links'] as $itemLink) {
                
                $link = $itemLinksTable->newEntity (['item_id' => $id,
                                                     'item_link_id' => $itemLink ['item_link_id'],
                                                     'link_type' =>$itemLink ['link_type']]);
                $itemLinksTable->save ($link);
            }
        }
        
        $this->batch ($item ['item'] ['id']);
        return 0;
    }
    
    /**
     *
     * delete an item
     *
     */
    
    public function delete ($id) {
        
        $status = -1;
        
        if ($id > 0) {
            
            $item = TableRegistry::get ('Items')
											->find ()
											->where (['id' => $id])
											->contain (['ItemPrices', 'ItemLinks', 'InvItems'])
											->first ();
				
            if ($item) {
					 
                $itemID = $item ['id'];
                $batchEntryTable = TableRegistry::get ('BatchEntries');
					 
                $batchEntry = $batchEntryTable->newEntity (['business_unit_id' => $this->merchant ['bu_id'],
																				'update_table' => 'items',
																				'update_id' => $item ['id'],
																				'update_action' => 1,
																				'execution_time' => time ()]);
                $batchEntryTable->save ($batchEntry);
                
                foreach (['item_links', 'item_prices'] as $assoc) {
                    
                    if (isset ($item [$assoc])) {
                        
                        foreach ($item [$assoc] as $tmp) {
                            
                            $batchEntry = $batchEntryTable->newEntity (['business_unit_id' => $this->merchant ['bu_id'],
																								'update_table' => $assoc,
																								'update_id' => $tmp ['id'],
																								'update_action' => 1,
																								'execution_time' => time ()]);
                            
                            $batchEntryTable->save ($batchEntry);
                        }
                    }
                }

                TableRegistry::get ('Items')->deleteAll (['id' => $itemID]);
                TableRegistry::get ('ItemPrices')->deleteAll (['item_id' => $itemID]);
                TableRegistry::get ('InvItems')->deleteAll (['item_id' => $itemID]);
                TableRegistry::get ('ItemLinks')->deleteAll (['item_id' => $itemID]);
            }
            
            $status = 0;
        }
		  
		  $this->set ('response', ['status' => $status]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
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
	 

    function jsonImport () {
		  		  
        if (!empty ($this->request->getData ())) {
				
            $json = json_decode ($this->request->getData () ['json'], true);
				
            $departmentTable = TableRegistry::get ('Departments');
            $itemsTable = TableRegistry::get ('Items');
            $itemPricesTable = TableRegistry::get ('ItemPrices');

            $departmentTable->deleteAll ([]);
            $itemsTable->deleteAll ([]);
            $itemPricesTable->deleteAll ([]);

            foreach ($json as $d) {

                $dept = $departmentTable->newEntity (['department_desc' => strtoupper ($d ['desc']),
                                                      'department_no'  => $d ['department_no'],
                                                      'department_type' => 2]);
                $dept = $departmentTable->save ($dept);
                $sku = $dept ['department_no'];
                foreach ($d ['items'] as $i) {

                    $sku ++;
                    $item = $itemsTable->newEntity (['department_id' => $dept ['id'],
                                                    'sku' => strval ($sku),
                                                    'item_desc' => strtoupper ($i ['desc'])]);

                    $item = $itemsTable->save ($item);

                    $itemPrice = $itemPricesTable->newEntity (['item_id' => $item ['id'],
                                                               'pricing' => '{"class":"standard","price":"'.floatval ($i ['price']).'","cost":"0.00"}']);
						  
                    $itemPrice = $itemPricesTable->save ($itemPrice);
                }       

            }
				
        }

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
     * 
     *
     **/

    public function searchItems ($desc) {
        
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];

        if (strlen ($desc) > 0) {
				
            $query = TableRegistry::get ('Items')
											 ->find ()
											 ->where (['or' => ["item_desc like '".$desc."%'",
																	  "sku like '".$desc."%'"]])
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
     * utility function, create inventory for all items
     *
     */

    function createInvItems ($buID) {

        $itemsTable = TableRegistry::get ('Items');
        $invItemTable = TableRegistry::get ('InvItems');

        $query = $itemsTable->find ('all');
        foreach ($query as $item) {

            $invItem = $invItemTable->newEntity (['item_id' => $item ['id'],
																  'business_unit_id' => $buID]);
            $invItemTable->save ($invItem);
        }
		  
        $this->set (['response' => 0]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
    }
	 
    /**
     *
     * utility function, create item prices for all items
     *
     */

    function createItemPrices () {

        $itemsTable = TableRegistry::get ('Items');
        $itemPricesTable = TableRegistry::get ('ItemPrices');
		  
        $query = $itemsTable
					  ->find ('all')
                 ->contain (['ItemPrices']);

        foreach ($query as $item) {
				
				$itemPrices = [];
				foreach ($this->merchant ['business_units'] as $bu) {

					 if ($bu ['business_type'] == 2) {
						  
						  $present = false;
						  foreach ($item ['item_prices'] as $ip) {
								
								if ($ip ['business_unit_id'] != $bu ['id']) {

									 unset ($ip ['id']);
									 $ip ['business_unit_id'] = $bu ['id'];
									 $itemPrices [$bu ['id']] = $ip->toArray ();
								}
						  }
					 }
				}

				/* $this->debug ('-------------------------------------------------------------------------------------------');
					$this->debug ($itemPrices);
					$this->debug ('-------------------------------------------------------------------------------------------');*/
				
				$itemPricesTable->deleteAll (['item_id' => $item ['id']]);

				foreach ($itemPrices as $bu => $ip) {

					 $ip = $itemPricesTable->newEntity ($ip);
					 $itemPricesTable->save ($ip);
				}
		  }
		  
        $this->set (['response' => 0]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
   }
	 
    function autoSku () {

        $itemsTable = TableRegistry::get ('Items');
		  $sku = '00000';
		  
        $item = $itemsTable
									->find ()
									->where (['length(sku) = 5'])
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
     * utility function, remove inventory and pricing not associated with an item
     *
     */

    function cleanup () {

        $itemsTable = TableRegistry::get ('Items');
        $itemPricesTable = TableRegistry::get ('ItemPrices');
        $invItemTable = TableRegistry::get ('InvItems');

		  foreach ([$itemPricesTable, $invItemTable] as $table) {

				$query = $table->find ('all');
				foreach ($query as $it) {
					 
					 $item = $itemsTable
				->find ()
				->where (['id' => $it ['item_id']])
				->first ();
					 
					 if (!$item) {
						  
						  $table->deleteAll (['id' => $it ['id']]);
					 }
				}
		  }
		  
		  $this->set ('response', ['status' => 0]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
    }

	 /**
     *
     * utility methods, queue updates to the POS
     *
     **/

    private function batch ($id) {
        
        $item = TableRegistry::get ('Items')
									  ->find ()
									  ->where (['id' => $id])
									  ->contain (['ItemPrices', 'ItemLinks'])
									  ->first ();

		  if ($item) {
				
				$batchEntrysTable = TableRegistry::get ('BatchEntries');
				
				$this->addBatch ('items', $item ['id']);
				
				/* foreach ($item ['item_prices'] as $ip) {
					
					$this->addBatch ('item_prices', $ip ['id']);
					}
					
					foreach ($item ['item_links'] as $il) {
					
					$this->addBatch ('item_links', $il ['id']);
					}*/
		  }

		  $this->notifyPos ();
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
}
?>

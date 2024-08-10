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
use App\Model\Entity\Supplier;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Datasource\ConnectionManager;

define ('OPEN_ORDER', 0);
define ('PENDING_ORDER', 1);
define ('CLOSED_ORDER', 2);
define ('CANCELLED_ORDER', 3);

define ('AUTO_ORDER', 0);
define ('CUSTOM_ORDER', 1);

define ('INVENTORY_METHOD', 0);
define ('SALES_METHOD', 1);

class SuppliersController extends PosAppController {

    public $paginate = ['suppliers' => ['limit' => 20,
                                        'order' => ['supplier_name asc']]];

    private $orderMethod;
    private $render;
    
    public function initialize (): void {
		  
        parent::initialize ();
        $this->orderMethod = SALES_METHOD;
    }

    public function index (...$params) {
        
        $suppliers = [];
        foreach ($this->paginate () as $supplier) {
            
            $supplier ['phone1'] = $this->phoneFormat ($supplier ['phone1']);
            $suppliers [] = $supplier;
        }
        
        switch ($this->merchant ['role']) {
					 
				case 'pos':
					 
					 $this->viewBuilder ()->setLayout ('pos_web');
					 $this->render ('pos_index');
					 break;
					 
				default:
					 
					 return ($this->response (__ ('Suppliers'),
													  'Suppliers',
													  'index',
													  compact ('suppliers')));
					 
					 break;
        }
    }
	 
    public function edit ($id = 0) {

		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'states.php';
       
        $supplier = null;
		  $suppliersTable = TableRegistry::get ('Suppliers');
		  
        if ($id == 0) {
				
            $supplier =  ['id' => 0,
								  'supplier_name' => '',
								  'contact1' => '',
								  'email' => '',
								  'phone1' => '',
								  'addr1' => '',
								  'addr2' => '',
								  'addr3' => '',
								  'addr5' => '',
								  'addr6' => '',
								  'supplier_type' => 1];
		  }
        else {
            
            $supplier = TableRegistry::get ('Suppliers')
												 ->find ()
												 ->where (['id' => $id])
												 ->first ();
        }

        if (strlen ($supplier ['phone1']) == 7) {

            $supplier ['phone1'] = '000' . $supplier ['phone1'];
        }
        
        return ($this->response (__ ('Suppliers'),
                                 'Suppliers',
                                 'edit',
                                 compact ('supplier', 'states'),
                                 true,
                                 'ajax',
                                 true));	  
	 }

    public function update ($id) {

        $status = -1;
        
        if (!empty ($this->request->getData ())) {

            $supplierTable = TableRegistry::get ('Suppliers');
				$supplier = null;
				
				if ($id > 0) {

                $supplier = $supplierTable
													  ->find ()
													  ->where (['id' => $id])
													  ->first ();
					 if ($supplier) {
						  
						  $supplier ['supplier_name'] = strtoupper ($this->request->getData () ['supplier_name']);
						  $supplier ['contact1'] = strtoupper ($this->request->getData () ['contact1']);
						  $supplier ['addr1'] = strtoupper ($this->request->getData () ['addr1']);
						  $supplier ['addr3'] = strtoupper ($this->request->getData () ['addr3']);
						  $supplier ['addr5'] = strtoupper ($this->request->getData () ['addr5']);
						  $supplier ['addr6'] = strtoupper ($this->request->getData () ['addr6']);
						  $supplier ['phone1'] = $this->clearPhone ($this->request->getData () ['phone1']);
						  $supplier ['email'] = $this->request->getData () ['email'];
						  $supplierTable->save ($supplier);
					 }
					 else {
						  
						  $this->error ("that's wierd supplier $id doesn't exist");
					 }
				}
				else {

					 $supplier = ['supplier_name' => strtoupper ($this->request->getData () ['supplier_name']),
									  'addr1'=> strtoupper ($this->request->getData () ['addr1']),
									  'addr3'=> strtoupper ($this->request->getData () ['addr3']),
									  'addr5'=> strtoupper ($this->request->getData () ['addr5']),
									  'addr6'=> strtoupper ($this->request->getData () ['addr6']),
									  'phone1'=> $this->clearPhone ($this->request->getData () ['phone1']),
									  'email'=> $this->request->getData () ['email']];
					 
					 $supplier = $supplierTable->newEntity ($supplier);
					 $supplierTable->save ($supplier);
				}	 
		  }
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);
    }
	 
    public function delete ($id) {
		  
        $this->request->allowMethod (['post', 'delete']);

        $supplier = $this->Suppliers->findById ($id)->firstOrFail ();
        if ($this->Suppliers->delete ($supplier)) {
        }
    }

    /**
     *
     * orders
     *
     **/

    public function orders ($supplierID) {

        $this->startOrder ($supplierID);  // create an open order if none exist
        
        $q = TableRegistry::get ('SupplierOrders')
								  ->find ('all')
								  ->where (['supplier_id' => $supplierID])
								  ->contain (['SupplierOrderItems'])
								  ->order (['open' => 'desc']);

        $orders = $this->paginate ($q);
        
        foreach ($orders as $order) {

            $order ['open'] = $this->timestamp ($order ['open']);
            switch ($order ['status']) {

					 case OPEN_ORDER:

						  if ($order ['order_type'] == AUTO_ORDER) {
								
								// calculate totals from current inventories
								
								$order ['order_quantity'] = 0;
								$order ['order_total'] = 0;
								
								$items = $this->invItems ($order ['supplier_id']);
								
								foreach ($items as $item) {
									 
									 $order ['order_quantity'] += $item ['order_quantity'];
									 $order ['order_total'] +=
                                $item ['order_quantity'] *
                            $item ['cost'];
								}
						  }
						  
						  break;

					 case PENDING_ORDER:
						  
						  $order ['pending'] = $this->timestamp ($order ['pending']);
						  break;
						  
					 case CLOSED_ORDER:
						  
						  $order ['pending'] = $this->timestamp ($order ['pending']);
						  $order ['closed'] = $this->timestamp ($order ['closed']);
						  break;                
            }

            switch ($order ['order_type']) {

					 case AUTO_ORDER:
						  
						  $order ['order_type_desc'] = __ ('Auto');
						  break;

					 case CUSTOM_ORDER:
						  
						  $order ['order_type_desc'] = __ ('Custom');
						  break;
            }   
        }

        $this->set ('orders', $orders);
        $this->set ('supplierID', $supplierID);

        switch ($this->merchant ['role']) {
					 
				case 'pos':
					 
					 $this->viewBuilder ()->setLayout ('pos_web');
					 $this->render ('pos_orders');
					 break;
					 
				default:
					 
					 break;
        }
    }

    /**
     *
     * order edit
     *
     **/

    public function order ($orderID) {

        $order = $this->getOrder (intVal ($orderID));
        $order ['bu_id'] = $this->merchant ['bu_id'];
        
        if ($order) {
            
            if (!empty ($this->request->getData ())) {
                
                //$this->debug ($this->request->getData ());
                
                $orderTotal = 0;
                $orderQuantity = 0;
                
                if (isset ($this->request->getData () ['items'])) {
                    
                    $invItemsTable = TableRegistry::get ('InvItems');
                    $itemPricesTable = TableRegistry::get ('ItemPrices');
                    $i = 0;
                    
                    foreach ($this->request->getData () ['items'] as $item) {

                        // $orderTotal += $this->request->getData () ['cost_' . $i] * $this->request->getData () ['order_quantity_' . $i];
                        // $orderQuantity += $this->request->getData () ['order_quantity_' . $i];
                        
                        switch ($order ['status']) {
										  
									 case OPEN_ORDER:

										  switch ($order ['order_type']) {

												case AUTO_ORDER:

													 $fields = ['order_quantity' =>  $item ['order_quantity']];
													 if (isset ($item ['add_item'])) {

														  $fields ['on_hand_quantity'] = $item ['order_quantity'] * $item ['package_quantity'] * -1;
													 }
													 
													 $invItemsTable
                                    ->updateAll ($fields,
                                                 ['id' => $item ['inv_item_id']]);
													 
													 $itemPricesTable
                                    ->updateAll (['cost' =>  $item ['cost']],
                                                 ['id' => $item ['item_prices_id']]);
													 break;

												case CUSTOM_ORDER:

													 $orderItemsTable = TableRegistry::get ('SupplierOrderItems');

													 $oi = $orderItemsTable
                                    ->newEntity (['supplier_order_id' => $this->request->getData () ['order_id'],
                                                  'item_id' => $invItem ['item_id'],
                                                  'inv_item_id' => $invItem ['inv_item_id'],
                                                  'order_quantity' => $invItem ['order_quantity']]);

													 $orderItemsTable->save ($oi);
										  }
										  break;
										  
										  
									 default:

										  TableRegistry::get ('SupplierOrderItems')
															->updateAll (['order_quantity' => $invItem ['order_quantity']],
																			 ['supplier_order_id' => $orderID,
																			  'item_id' => $invItem ['item_id']]);
                        }
                        
                        $i ++;
                    }

                    TableRegistry::get ('SupplierOrders')
											->updateAll (['order_quantity' => $orderQuantity,
															  'order_total' => $orderTotal],
															 ['id' => $orderID]);

                }
                
                $this->set ('response', ['status' => 0]);
                $this->render ('json');
                return;
                // return $this->redirect (['controller' => 'suppliers', 'action' => 'orders/' . $supplierID]);
            }
            
            $this->set ('order', $order);
            
            switch ($this->merchant ['role']) {
						  
					 case 'pos':
						  
						  $this->viewBuilder ()->setLayout ('pos_web');
						  $this->render ('pos_order');
						  break;
					 default:
						  
						  break;
            }
        }
    }
    
    /**
     *
     * post order
     *
     **/

    public function postOrder ($orderID) {
        
        $ordersTable = TableRegistry::get ('SupplierOrders');
        $orderItemsTable = TableRegistry::get ('SupplierOrderItems');
        $invItemsTable = TableRegistry::get ('InvItems');

        $order = $this->getOrder ($orderID);
        
        if ($order) {

            // $this->debug ("order... $orderID");
            // $this->debug ($order);
            //return $this->redirect (['controller' => 'suppliers', 'action' => 'orders/' . $order ['supplier_id']]);
            
            $order ['order_date'] = $this->localDateTime (date ('Y-m-d H:i:s'), $this->dateFormat);

            $this->set ('order', $order );

            $orderItems = [];
            
            switch ($order ['status']) {

					 case OPEN_ORDER:

						  $orderQuantity = 0;
						  $orderTotal = 0;
						  
						  foreach ($order ['items'] as $item) {

								// if (inventory > package size...

                        // decrement inventory only for items in order
                        // still need to manage order quantity in inventory???
                        
								$orderItem = $orderItemsTable
                               ->newEntity (['supplier_order_id' => $order ['id'],
                                             'item_id' => $item ['item_id'],
                                             'inv_item_id' => $item ['inv_item_id'],
                                             'order_quantity' => $item ['order_quantity']]);
								
								$orderItemsTable->save ($orderItem);
                        
								$orderQuantity += $item ['order_quantity'];
								$orderTotal += $item ['cost'] * $item ['order_quantity'] * $item ['package_quantity'];

								switch ($this->orderMethod ) {

									 case INVENTORY_METHOD:
										  
										  break;

									 case SALES_METHOD:

										  break;
								}

								// update inventory, current inventory plus what was ordered
								
								$invItemsTable
                        ->updateAll (['on_hand_quantity' =>
                            $item ['on_hand_quantity'] +
																			 ($item ['package_quantity'] * $item ['order_quantity']),
                                      'order_quantity' => 0],
                                     ['id' => $item ['inv_item_id']]);

						  }
						  
						  $ordersTable     
                    ->updateAll (['status' => PENDING_ORDER,
                                  'pending' => time (),
                                  'order_quantity' => $orderQuantity,
                                  'order_total' => $orderTotal],
                                 ['id' => $order ['id']]);
						  
						  break;
						  
					 default:

						  break;
            }
            
            TransportFactory::setConfig('gmail', [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'username' => 'orders@multipos.cloud',
                'password' => 'Ul#i6WPAF52',
                'className' => 'Smtp',
                'tls' => true
            ]);
            
            $title = $this->bus [0] ['business_name'] . ' Order #' . $order ['id'];
            $email = new Email ();
            $email
                ->subject ($title)
                ->helpers (['Html'])
                ->setViewVars (['title' => $title,
                                'supplier' => $order ['supplier'],
                                'bu' => $this->bus [0],
                                'order' => $order,
                                'items' => $order ['items']])
                ->template ('send_order')
                ->emailFormat ('html')
                ->setTo ('orders@multipos.cloud')
                ->setCc ('orders@multipos.cloud')
                ->setFrom ('orders@multipos.cloud')
                ->send ();
            
            return $this->redirect (['controller' => 'suppliers', 'action' => 'orders/' . $order ['supplier_id']]);
        }
    }

    /**
     *
     * close order
     *
     **/

    public function closeOrder ($orderID) {

        $order = $this->getOrder ($orderID);

        if ($order) {
            
            $invItemsTable = TableRegistry::get ('InvItems');
				
            foreach ($order ['supplier_order_items'] as $orderItem) {
                
                $invItem = $invItemsTable
                         ->find ()
                         ->where (['id' => $orderItem ['inv_item_id']])
                         ->first ();

                if ($invItem) {

                    $received = $invItem ['package_quantity'] * $orderItem ['order_quantity'];

                    $invItem ['on_hand_quantity'] += $received;

                    $short = $invItem ['on_hand_req'] - $invItem ['on_hand_quantity'];
                    if ($short > 0) {

                        $invItem ['order_quantity'] = intVal ($short / $invItem ['package_quantity']) + 1;
                    }
                    else {
								
                        $invItem ['order_quantity'] = 0;
                    }

                    $invItemsTable ->save ($invItem);
                }
            }
				
            $ordersTable = TableRegistry::get ('SupplierOrders');
				
            $ordersTable
                ->updateAll (['status' => CLOSED_ORDER,
                              'closed' => time ()],
                             ['id' => $order ['id']]);
            
            return $this->redirect (['controller' => 'suppliers', 'action' => 'orders/' . $order ['supplier_id']]);
        }
        
        $this->redirect (['controller' => 'suppliers']);
    }
    
    /**
     *
     * custom order
     *
     **/

    public function viewOrder ($orderID) {
        
        $this->set ('order', $this->getOrder ($orderID));
        
        switch ($this->merchant ['role']) {
                
            case 'pos':
                
                $this->viewBuilder ()->setLayout ('pos_web');
                $this->render ('pos_view_order');
                break;
            default:
                
                break;
        }
    }

    /**
     *
     * custom order
     *
     **/

    public function customOrder ($supplierID) {
        
        $ordersTable = TableRegistry::get ('SupplierOrders');
        
        $order = $ordersTable
               ->newEntity (['supplier_id' => $supplierID,
                             'open' => time (),
                             'status' => OPEN_ORDER,
                             'order_type' => 1]);
        
        $ordersTable->save ($order);
        $this->redirect (['controller' => 'suppliers/orders/' . $supplierID]);
    }
    
    /**
     *
     * cancel order
     *
     **/

    public function cancelOrder ($supplierID, $orderID) {
        
        $ordersTable = TableRegistry::get ('SupplierOrders');
        
        $ordersTable
            ->updateAll (['status' => CANCELLED_ORDER],
                         ['id' => $orderID]);

        $this->redirect (['controller' => 'suppliers/orders/' . $supplierID]);
    }

    /**
     *
     * start a new order
     *
     **/

    private function startOrder ($supplierID) {

        $ordersTable = TableRegistry::get ('SupplierOrders');
        
        $order = $ordersTable
               ->find ()
               ->where (['supplier_id' => $supplierID,
                         'status' => OPEN_ORDER,
                         'order_type' => AUTO_ORDER])
               ->first ();

        if (!$order) {
            
            $order = $ordersTable->newEntity (['supplier_id' => $supplierID,
                                               'open' => time (),
                                               'status' => OPEN_ORDER]);

            $ordersTable->save ($order);
        }
    }

    /**
     *
     * print order
     *
     **/

    public function posPrintOrder ($orderID) {

        // $this->debug ("pos print order... $orderID");
        // $this->debug ($this->merchant);
        
        $response = ['order_id' => $orderID,
                     'order_items' => []];
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', $response);
        $this->RequestHandler->respondAs ('json');
        
        // $order = $this->getOrder ($orderID);
        // if ($order) {

        //     $order ['order_date'] = $this->localDateTime (date ('Y-m-d H:i:s'), $this->dateFormat);
        
        //     $this->set ('order', $order);
        //     $this->viewBuilder ()->setLayout ('print_layout');
        //     return;
        // }
        
        // $this->redirect (['action' => 'index']);
    }
    
    /**
     *
     * get order
     *
     * get the order and associated items, if auto order get them from inventory
     * if custom get them from the order
     *
     **/

    private function getOrder ($orderID) {

        $order = TableRegistry::get ('SupplierOrders')
										->find ()
										->where (['id' => intVal ($orderID)])
										->contain (['SupplierOrderItems'])
										->first ();
        
        if ($order) {

            // $this->debug ($order);
            
            $order ['supplier'] = TableRegistry::get ('Suppliers')
															  ->find ()
															  ->where (['id' => intVal ($order ['supplier_id'])])
															  ->first ();
            
            $order ['business_units'] = $this->bus;
            $order ['items'] = [];

            switch ($order ['status']) {

					 case OPEN_ORDER:

						  if ($order ['order_type'] == AUTO_ORDER) {
								
								$order ['items'] = $this->invItems ($order ['supplier_id']);
						  }
						  else {

								$order ['items'] = $this->orderItems ($order ['id']);
						  }
						  
						  break;
						  
					 default:
						  
						  $order ['items'] = $this->orderItems ($order ['id']);
						  
            }

            // $this->debug ($order);
            
            return $order;
        }
        
        return false;
    }

    private function invItems ($supplierID) {
        
        $items = [];
        $q = false;
        $orderQuantity = 0;
        $orderTotal = 0;
        $invItemsTable = false;
        $buID = intVal ($this->merchant ['bu_id']);
        
        $salesItems = TableRegistry::get ('SalesItemTotals');

        $q = TableRegistry::get ('Items')
								  ->find ('all')
								  ->where (['inv_items.supplier_id' => $supplierID,
												'inv_items.on_hand_quantity < 0'])
								  ->contain (['InvItems', 'ItemPrices'])
								  ->join (['table' => 'inv_items',
											  'type' => 'right',
											  'conditions' => 'Items.id = inv_items.item_id'])
								  ->order (['Items.item_desc']);

        $count = 0;
        foreach ($q as $item) {


            // if ($count ++ == 5) break;
            
            $onHand =                
                $item ['inv_items'] [0] ['on_hand_quantity'] - $item ['inv_items'] [0] ['on_hand_req'];
            
            if (($item ['inv_items'] [0] ['package_quantity'] > 0) && ($onHand < 0)) {
                
                $onOrder = 0;
                
                if ($item ['inv_items'] [0] ['order_quantity'] > 0) {
                    
                    $onOrder = $item ['inv_items'] [0] ['order_quantity'];
                }
                else {
                    
                    $onOrder = intVal (abs ($onHand - $item ['inv_items'] [0] ['on_hand_req']) / $item ['inv_items'] [0] ['package_quantity']);
                }
                
                // $this->debug ('on hand... ' . $onHand . ' ' . $onOrder . ' ' . $item ['inv_items'] [0] ['package_quantity']);
                
                // $this->debug ("item... $onOrder");
                // $this->debug ($item);
                
                if ($onOrder > 0) {
                    
                    // $item ['inv_items'] [0] ['order_quantity'] = $onOrder;
                    // $item ['order_item'] = ['item_id' => $item ['id'],
                    //                         'inv_item_id' => $item ['inv_items'] [0] ['id'],
                    //                         'order_quantity' => $onOrder];
                    
                    $s = date ('Y-m-d H:i:s', time () - 7 * 24 * 60 * 60);
                    $salesItem = $salesItems
                              ->find ()
                              ->where (["start_time > '$s'",
                                        'summary_type' => 1,
                                        'business_unit_id' => $buID,
                                        'sales_item_key' => $item ['sku']])
                              ->select (['quantity' => 'SUM(quantity)'])
                              ->first ();
                    
                    if (strlen ($salesItem ['quantity']) == 0) {

								$salesItem ['quantity'] = 0;
                    }
                    
                    $this->debug ("item sales... " . $item ['sku'] . ' ' . $salesItem ['quantity']);

                    $items [] = ['item_id' => $item ['id'],
											'sku' => $item ['sku'],
											'item_desc' => $item ['item_desc'],
											'weeks_sales' => $salesItem ['quantity'],
											'inv_item_id' => $item ['inv_items'] [0] ['id'],
											'on_hand_quantity' => $item ['inv_items'] [0] ['on_hand_quantity'],
											'package_quantity' => $item ['inv_items'] [0] ['package_quantity'],
											'cost' => $item ['item_prices'] [0] ['cost'],
											'item_prices_id' => $item ['item_prices'] [0] ['id'],
											'order_quantity' => $onOrder];
                }
            }
        }
        
        return $items;
    }

    private function orderItems ($orderID) {

        $this->debug ("order items... $orderID");
        
        $items = [];

        $orderItems = TableRegistry::get ('SupplierOrderItems')
											  ->find ()
											  ->where (['supplier_order_id' => $orderID])
											  ->order (['order_quantity desc']);
        
        foreach ($orderItems as $orderItem) {
            
            $item = TableRegistry::get ('Items')
											->find ()
											->where (['Items.id' => $orderItem ['item_id']])
											->contain (['InvItems', 'ItemPrices'])
											->join (['table' => 'inv_items',
														'type' => 'right',
														'conditions' => 'Items.id = inv_items.item_id'])
											->first ();
            
            $items [] = ['item_id' => $item ['id'],
                         'sku' => $item ['sku'],
                         'item_desc' => $item ['item_desc'],
                         'inv_item_id' => $item ['inv_items'] [0] ['id'],
                         'on_hand_quantity' => $item ['inv_items'] [0] ['on_hand_quantity'],
                         'package_quantity' => $item ['inv_items'] [0] ['package_quantity'],
                         'cost' => $item ['item_prices'] [0] ['cost'],
                         'item_prices_id' => $item ['item_prices'] [0] ['id'],
                         'order_quantity' => $orderItem ['order_quantity']];
        }

        return $items;
    }

    public function onHandReq () {

        $now = time ();

        $start = $now - 6 * 7 * 24 * 60 * 60;
        $end = $start +  7 * 24 * 60 * 60;
        
        $s = date ('Y-m-d H:i:s', $start);
        $e = date ('Y-m-d H:i:s', $end);
        
        $header = "sku,item_description";

        for ($week = 6; $week > 0; $week --) {

            $start = $now - $week * 7 * 24 * 60 * 60;
            $header .= date (',m/d', $start);
        }

        $header .= ',max';

        $this->debug ($header);
        $salesItems = TableRegistry::get ('SalesItemTotals')
											  ->find ()
											  ->where (["start_time > '$s'",
															"start_time < '$e'",
															'summary_type' => 1,
															'business_unit_id' => 101,
															'department_id' => 4509])
											  ->distinct ('sales_item_key')
											  ->order ('sales_item_key asc')
											  ->select (['sales_item_key', 'sales_item_desc']);
        // ->limit (5);
        
        foreach ($salesItems as $salesItem) {
            
            $sku = $salesItem ['sales_item_key'];
            $desc = $salesItem ['sales_item_desc'];
            
            //$q = sprintf ("%-15s %-50s ", $sku, trim ($desc));
            $q = sprintf ("%s,'%s'", trim ($sku), trim ($desc));

            $weekSales = 0;
            for ($week = 6; $week > 0; $week --) {

                $start = $now - $week * 7 * 24 * 60 * 60;
                $end = $start +  7 * 24 * 60 * 60;
                
                $s = date ('Y-m-d H:i:s', $start);
                $e = date ('Y-m-d H:i:s', $end);
                
                $salesItemQuantities = TableRegistry::get ('SalesItemTotals')
																	 ->find ()
																	 ->where (['sales_item_key' => $salesItem ['sales_item_key'],
																				  "start_time > '$s'",
																				  "start_time < '$e'",
																				  'summary_type' => 1,
																				  'business_unit_id' => 101])
																	 ->select (['quantity' => 'SUM(quantity)']);
                
					 
                foreach ($salesItemQuantities as $quantity) {

                    //$q .= sprintf ("%4d", $quantity ['quantity']);
                    if (intVal ($quantity ['quantity']) > $weekSales) {

                        $weekSales = $quantity ['quantity'];
                    }
                    
                    $q .= sprintf (",%d", $quantity ['quantity']);
                }
            }
            
            $q .= sprintf (",%d", $weekSales);
            $this->debug ($q);

            $connection = ConnectionManager::get ('default');
            $update = "update inv_items set on_hand_req = $weekSales where item_id = (select id from items where sku ='$sku')";
            $this->debug ($update);
            $connection->execute ($update);
        }
    }
}
?>

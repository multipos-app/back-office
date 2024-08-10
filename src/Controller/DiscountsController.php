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
use App\Controller\AdminController;
use Cake\Datasource\ConnectionManager;

class DiscountsController extends PosAppController {
    
    public $paginate = ['limit' => 20,
                        'order' => ['id desc']];
	 
    public function index (...$params) {

        $d = [];
        $addons = TableRegistry::get ('Addons')
										 ->find ('all')
										 ->order (['id' => 'desc']);
        
        foreach ($addons as $addon) {

            foreach (['start_time', 'end_time'] as $t) {
                
                if ($addon [$t] != null) {

                    $addon [$t] = $this->timestamp ($addon [$t], '%d/%m');
                }
                else  {

                    $addon [$t] = '';
                }
            }

            $d [] = $addon->toArray ();
        }

        $addons = $d;
        $addonTypes = [null => __ ('Add Discount'),
                       'mix_and_match/0/amount' => __ ('Mix and match, markdown amount'),
                       'mix_and_match/0/percent' => __ ('Mix and match, markdown percent'),
                       'discount_sale/0' => __ ('Discount sale, all items'),
                       'discount_department/0' => __ ('Department Discount')];
        
        return ($this->response (__ ('Discounts'),
                                 'Discounts',
                                 'index',
                                 compact ('addons',
														'addonTypes')));
    }
    
    public function mixAndMatch ($id, $type = null) {

		  $this->debug ("mix and match... $id $type");
		  
		  $addon = ['id' => 0,
						'addon_type' => 'mix_and_match',
						'description' => '',
						'print_description' => '',
						'class' => 'cloud.multipos.pos.controller.MixAndMatchMarkdown',
						'params' => ['markdown_type' => $type,
										 'markdown_amount' => '',
										 'count' => ''],
						'addon_links' => [],
						'start_time' => '',
						'end_time' => ''];
		  
        if ($id > 0) {
        		
				$addon = TableRegistry::get ('Addons')
											 ->find ()
											 ->where (['id' => $id])
 											 ->contain (['AddonLinks'])
											 ->first ();

				if ($addon) {

					 $addon ['params'] = json_decode ($addon ['params'], true);
					 
					 $links = [];
					 foreach ($addon ['addon_links'] as $link) {
						  
						  $links [] = TableRegistry::get ('Items')
															->find ()
															->where (['id' => $link ['addon_link_id']])
															->contain (['ItemPrices'])
															->first ();
						  
					 }
					 
					 $addon ['addon_links'] = $links;
				}
				else {
					 
					 $this->error ("hmm... addon not found... $id");
					 exit;
				}
		  }
		  
        $departments = $this->departments ();
		  
        return ($this->response (__ ('Discounts'),
                                 'Discounts',
                                 'mix_and_match',
                                 compact ('addon',
														'departments')));
    }

	 public function discountDepartment () {
		  
		  $addon = ['id' => 0,
						'description' => '',
						'print_description' => '',
						'class' => 'cloud.multipos.pos.controller.DiscountDepartment',
						'addon_type' => 'discount_department',
						'params' => ['discount_percent' => 0]];

		  $departments = $this->departments ();
        return ($this->response (__ ('Discounts'),
                                 'Discounts',
                                 'discount_department',
                                 compact ('addon', 'departments')));
	 }

	 public function discountSale () {

		  $addon = ['id' => 0,
						'description' => '',
						'print_description' => '',
						'class' => 'cloud.multipos.pos.controller.SaleDiscountPercent',
						'addon_type' => 'discount_sale',
						'params' => ['discount_percent' => 0]];

        return ($this->response (__ ('Discounts'),
                                 'Discounts',
                                 'discount_sale',
                                 compact ('addon')));
	 }
	 
    public function update () {

		  $this->debug ($this->request->getData ());
		  $status = -1;
		  
		  if (!empty ($this->request->getData ())) {
				
            $addonsTable = TableRegistry::get ('Addons');
				$addon = null;
            $id = $this->request->getData () ['id'];
				$startTime = strlen ($this->request->getData () ['start_time']) ? $this->request->getData () ['start_time'] : null;
				$endTime = strlen ($this->request->getData () ['end_time']) ? $this->request->getData () ['end_time'] : null;
				
				if ($this->request->getData () ['id'] > 0) {

					 $addon = $addonsTable
                       ->find ()
                       ->where (['id' => $id])
                       ->contain (['AddonLinks'])
                       ->first ();

					 
					 $addon ['description'] = strtoupper ($this->request->getData () ['description']);
					 $addon ['print_description'] = strtoupper ($this->request->getData () ['print_description']);
					 $addon ['class'] = $this->request->getData () ['class'];
					 $addon ['addon_type'] = $this->request->getData () ['addon_type'];
					 $addon ['start_time'] = $startTime;
					 $addon ['end_time'] = $endTime;
					 $addon ['class'] = $this->request->getData () ['class'];
					 $addon ['params'] = json_encode ($this->request->getData () ['params']);
				}
				else {
					 
					 $addon = $addonsTable->newEntity (['description' => strtoupper ($this->request->getData () ['description']),
																	'print_description' => strtoupper ($this->request->getData () ['print_description']),
																	'class' => $this->request->getData () ['class'],
																	'addon_type' => $this->request->getData () ['addon_type'],
																	'start_time' => $startTime,
																	'end_time' => $endTime,
																	'params' => json_encode ($this->request->getData () ['params'])]);
				}

				if ($addon) {
					 
        			 $this->save ('Addons', $addon);
				}
		  }
		  
		  if (isset ($this->request->getData () ['addon_links']) && (count ($this->request->getData () ['addon_links']) > 0)) {
				
				$addonLinksTable = TableRegistry::get ('AddonLinks');
				$links = $this->request->getData () ['addon_links'];
				$this->debug ($links);
				
				$addonLinksTable->deleteAll (['addon_id' => $addon ['id']]);
				
				foreach ($links as $link) {
					 
					 $this->debug ($link);
					 
					 $addonLink = $addonLinksTable->newEntity (['addon_id' => $addon ['id'],
																			  'addon_link_id' => intval ($link)]);
					 
					 $this->save ('AddonLinks', $addonLink);
				}
		  }
		  else {
				
				$addonLinksTable = TableRegistry::get ('AddonLinks');
				$addonLinksTable->deleteAll (['addon_id' => $addon ['id']]);
		  }
		  
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);
    }

    public function delete ($id) {
		  
        // $this->request->allowMethod (['post', 'delete']);
        
        $addonsTable = TableRegistry::get ('Addons');
        $addonLinksTable = TableRegistry::get ('AddonLinks');
        $batchEntriesTable = TableRegistry::get ('BatchEntries');
        
        $discount = $addonsTable
                  ->findById ($id)
                  ->contain (['addonLinks'])
                  ->firstOrFail ();

        $batchEntry = ['business_unit_id' => $this->merchant ['bu_id'],
                       'batch_id' => 0,
                       'update_table' => 'item_addons',
                       'update_id' => $id,
                       'update_action' => 1,
                       'execution_time' => time ()];
        
        $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
        $batchEntriesTable->save ($batchEntry);
        
        foreach ($discount ['addon_links'] as $link) {

            $batchEntry = ['business_unit_id' => $this->merchant ['bu_id'],
									'batch_id' => 0,
									'update_table' => 'addon_links',
									'update_id' => $link ['id'],
									'update_action' => 1,
									'execution_time' => time ()];
            
            $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
            $batchEntriesTable->save ($batchEntry);
        }
        
        $addonsTable->deleteAll (['id' => $id]);
		  $addonLinksTable->deleteAll (['addon_link_id' => $id]);
		  
        $this->ajax (['status' => 0]);
    }
    
    public function getItems ($desc, $addonType = null, $discount = null) {
        
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];

        if (strlen ($desc) > 0) {
				
            $query = TableRegistry::get ('Items')
											 ->find ()
											 ->where (['or' => ["sku like '" . $desc."%'",
																	  "item_desc like '" . $desc."%'"]])
											 ->contain ('ItemPrices')
											 ->limit (5);

            $response = [];

            if ($addonType != null) {
                
                foreach ($query as $item) {

                    $response [] = $this->mapItem ($item, $addonType, $discount);
                }
            }
            else {
                
                foreach ($query as $item) {
                    
                    $response [] = ['id' => $item ['id'],
                                    'name' => $item ['item_desc']];
                }
            }
        }
		  
        $this->set (['response' => $response]);
    }

    private function mapItem ($item, $addonType, $discount) {

        $discount = floatVal ($discount);
        
        $price = 0;
        $discountPrice = 0;
        $cost = 0;
        $profit = 0;
        $discountProfit = 0;
        $discountValue = 0;
        
        if (count ($item ['item_prices']) > 0 ) {

            $pricing = $item ['item_prices'] [0];
            $price = $pricing ['price'];
            $cost = $pricing ['cost'];

            switch ($addonType) {

					 case 1:
					 case 2:
					 case 8:

						  $discountPrice = $pricing ['price'] - $discount;
						  $discountValue = $discount;
						  break;

					 case 3:
					 case 4:
					 case 9:
						  
						  $discountPrice = $pricing ['price'] - ($pricing ['price'] * ($discount / 100));
						  $discountValue = $pricing ['price'] - $discountPrice;
						  break;
            }
            
            if (($pricing ['price'] > 0) && ($discountPrice > 0)) {
                
                $profit = sprintf ("%.1f%%", 100 - (($pricing ['cost'] / $pricing ['price']) * 100.0));
                $discountProfit = sprintf ("%.1f%%", 100 - (($pricing ['cost'] / $discountPrice) * 100.0));
            }
            else if (($pricing ['price'] > 0) && ($pricing ['cost'] == 0)) {
                
                $profit = 100;
            }
        }

        return ['id' => $item ['id'],
                'sku' =>  $item ['sku'],
                'name' =>  $item ['item_desc'],
                'price' => $price,
                'cost' => $cost,
                'profit' => $profit,
                'discount_price' => $discountPrice,
                'discount_profit' => $discountProfit,
                'markdown_amount' => $discountValue];
    }

	 private function departments () {
		  
        $departments = [null => __ ('Department')];
        $query = TableRegistry::get ('Departments')
										->find ()
										->order (['department_desc' => 'asc']);
        
        foreach ($query as $d) {
            
            $departments [$d ['id']] = $d ['department_desc'];
        }

		  return $departments;
	 }
}

?>

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
use App\Controller\PosAppController;

class PricingController extends PosAppController {
  
    public function initialize (): void {
        
        parent::initialize ();
    }
    
    public function index (...$params) {

        $query = TableRegistry::get ('Pricing')
               ->find ();

        $pricing = [];
        foreach ($query as $p) {

            $pricing [] = $p;
        }

		  $pricingTypes = [null => __ ('Add Pricing Option'),
								 'size/0' => __ ('Price by product size, small, medium, large...'),
								 'metric/0' => __ ('Price by product measure, weight, length...'),
								 'group/0' => __ ('Group pricing, all items in group priced the same')];
		  
        return ($this->response (__ ('Pricing'),
                                 'Pricing',
                                 'index',
                                 compact ('pricing',
														'pricingTypes')));
    }
        
    public function size ($id = 0) {

        $this->debug ("pricing... $id");
        
        $pricingTable  = TableRegistry::get ('Pricing');
        $pricing = false;
        $pricingDesc =  __ ('Price by size of product, small, med, large...');
                 
        if ($id > 0) {
                
            $pricing = $pricingTable
                     ->find ()
                     ->where (['id' => $id])
                     ->first ();
            
            if ($pricing) {
                    
                $params = json_decode ($pricing ['params'], true);
                $pricing ['sizes'] = $params ['sizes'];
                $pricing ['category'] = __ ('Price by item size');
                
                for ($i = 0; $i < count ($pricing ['sizes']); $i ++) {
                    
                    $pricing ['sizes'] [$i] ['price'] = $pricing ['sizes'] [$i] ['price'];
                    $pricing ['sizes'] [$i] ['cost'] = $pricing ['sizes'] [$i] ['cost'];
                }
                
                $pricing = $pricing;
                $pricingDesc =  __ ('Price by size of product, small, med, large...');
            }
        }
        else {
                
            $pricing = ['id' => 0,
                        'class' => 'size',
                        'name' => '',
                        'description' => '',
                        'sizes' => []];
        }
        
        return ($this->response (__ ('Pricing'),
                                 'Pricing',
                                 'size',
                                 compact ('pricing',
														'pricingDesc')));
    }

    public function sizePricingUpdate () {

        $this->debug ('size pricing update... ');
        $this->debug ($this->request->getData ());
       
        if (!empty ($this->request->getData ())) {
            
            $pricingTable  = TableRegistry::get ('Pricing');
            if ($this->request->getData () ['pricing_id'] > 0) {
                
                $pricing = $pricingTable
                         ->find ()
                         ->where (['id' => $this->request->getData () ['pricing_id']])
                         ->first ();
                
                if (!$pricing) {
                    
                    $this->log ('attempt to load invalid pricing option... ' . $id, 'error');
                }
                
                $pricing ['name'] = strtoupper ($this->request->getData () ['pricing'] ['name']);
                $pricing ['description'] = strtoupper ($this->request->getData () ['pricing'] ['description']);
            }
            else {
                
                $pricing = ['class' =>  $this->request->getData () ['class'],
                            'name' => strtoupper ($this->request->getData () ['pricing'] ['name']),
                            'description' => strtoupper ($this->request->getData () ['pricing'] ['description']),
                            'create_time' => time ()];
                
                $pricing = $pricingTable->newEntity ($pricing);
            }

            $params = false;
            $params = ['class' => 'size',
                       'name' => strtoupper ($this->request->getData () ['pricing'] ['name']),
                       'description' => strtoupper ($this->request->getData () ['pricing'] ['description']),
                       'sizes' => []];
                
            foreach ($this->request->getData () ['pricing'] ['sizes'] as $size) {
                    
                $params ['sizes'] [] = ['description' =>  strtoupper ($size ['size']),
                                        'price' => $size ['price'],
                                        'cost' => $size ['cost'],
                                        'is_default' => 0];
            }
            
            $pricing ['params'] = json_encode ($params);                
            $this->debug ($pricing);
				$pricingTable->save ($pricing);

            $this->updateItems ($pricing ['id'], json_decode ($pricing ['params'], true));
        }
		  
        $this->set ('response', ['status' => '0']);
    }
    
    public function group ($id = 0) {

        $this->debug ("pricing... $id");
        
        $pricingTable  = TableRegistry::get ('Pricing');
        $pricing = false;
        $pricingDesc =  __ ('Group pricing, all items same price.');
         
        if ($id > 0) {
                
            $pricing = $pricingTable
                     ->find ()
                     ->where (['id' => $id])
                     ->first ();           
           
            if ($pricing) {
               
                $p = json_decode ($pricing ['params'], true);
                $p ['price'] = number_format ($p ['price']);
                $p ['cost'] = number_format ($p ['cost']);
                $p ['category'] = __ ('Group');
               
                $pricingDesc =  __ ('Group Pricing');
                $pricing = $p;
            }
        }
        else {
                
            $pricing = ['id' => 0,
                        'class' => 'group',
                        'name' => '',
                        'description' => '',
                        'category' => '',
                        'sizes' => []];
        }
        
        return ($this->response (__ ('Group Pricing'),
                                 'Pricing',
                                 'group_pricing',
                                 compact ('pricing', 'pricingDesc')));
    }

    public function groupPricingUpdate () {
        
        if (!empty ($this->request->getData ())) {

            $this->debug ($this->request->getData ());
            $pricingTable  = TableRegistry::get ('Pricing');

            if ($this->request->getData () ['pricing_id'] > 0) {
              
                $pricing = $pricingTable
                         ->find ()
                         ->where (['id' => $this->request->getData () ['pricing_id']])
                         ->first ();
                
                if (!$pricing) {
                    
                    $this->log ('attempt to load invalid pricing option... ' . $id, 'error');
                }
                
                $pricing ['name'] = strtoupper ($this->request->getData () ['pricing'] ['name']);
                $pricing ['description'] = strtoupper ($this->request->getData () ['pricing'] ['description']);
            }
            else {
                
                $pricing = ['class' =>  $this->request->getData () ['class'],
                            'name' => strtoupper ($this->request->getData () ['pricing'] ['name']),
                            'description' => strtoupper ($this->request->getData () ['pricing'] ['description']),
                            'create_time' => 'current_timestamp'];
                
                $pricing = $pricingTable->newEntity ($pricing);
            }

            $params = false;
            $pricing ['params'] = json_encode (['class' => 'group',
                                                'name' => strtoupper ($this->request->getData () ['pricing'] ['name']),
                                                'description' => strtoupper ($this->request->getData () ['pricing'] ['description']),
                                                'price' => number_format ($this->request->getData () ['pricing'] ['price']),
                                                'cost' => number_format ($this->request->getData () ['pricing'] ['cost'])]);               
            $pricingTable->save ($pricing);

            $this->updateItems ($pricing ['id'], json_decode ($pricing ['params'], true));
        }

        $this->set ('response', ['status' => '0']);
    }
    
      public function metric ($id = 0) {

        $this->debug ("pricing... $id");
        
        $pricingTable  = TableRegistry::get ('Pricing');
        $pricing = false;
        $pricingDesc =  __ ('Price by weight, volume, etc.');
         
        if ($id > 0) {
                
            $pricing = $pricingTable
                     ->find ()
                     ->where (['id' => $id])
                     ->first ();           
           
            if ($pricing) {

                $p = json_decode ($pricing ['params'], true);
                
                $p ['id'] = $pricing ['id'];
                $p ['price'] = number_format ($p ['price']);
                $p ['cost'] = number_format ($p ['cost']);
               
                $pricingDesc =  __ ('Metric Pricing');
                $pricing = $p;
            }
        }
        else {
                
            $pricing = ['id' => 0,
                        'class' => 'metric',
                        'name' => '',
                        'description' => '',
                        'price' => null,
                        'cost' => null];
        }

        $metrics = [null => __ ('Select type of measure'),
                    'pounds' => __ ('Pounds'),
                    'gallons' => __ ('Gallons'),
                    'liquid_ounces' => __ ('Liquid Ounces'),
                    'grams' => __ ('Grams'),
                    'length' => __ ('Length')];
                    
        
        return ($this->response (__ ('Metric Pricing'),
                                 'Pricing',
                                 'metric_pricing',
                                 compact ('pricing', 'pricingDesc', 'metrics')));
    }

    public function metricPricingUpdate () {
        
        if (!empty ($this->request->getData ())) {

            $this->debug ($this->request->getData ());
            
            $pricingTable  = TableRegistry::get ('Pricing');

            if ($this->request->getData () ['pricing_id'] > 0) {
              
                $pricing = $pricingTable
                         ->find ()
                         ->where (['id' => $this->request->getData () ['pricing_id']])
                         ->first ();
                
                if (!$pricing) {
                    
                    $this->log ('attempt to load invalid pricing option... ' . $id, 'error');
                }
                
                $pricing ['name'] = strtoupper ($this->request->getData () ['pricing'] ['name']);
                $pricing ['description'] = $this->request->getData () ['pricing'] ['description'];
            }
            else {
                
                $pricing = ['class' =>  $this->request->getData () ['class'],
                            'name' => strtoupper ($this->request->getData () ['pricing'] ['name']),
                            'description' => strtoupper ($this->request->getData () ['pricing'] ['description']),
                            'create_time' => 'current_timestamp'];
                
                $pricing = $pricingTable->newEntity ($pricing);
            }

            $params = false;
            $pricing ['params'] = json_encode (['class' => 'metric',
                                                'name' => strtoupper ($this->request->getData () ['pricing'] ['name']),
                                                'description' => $this->request->getData () ['pricing'] ['description'],
                                                'metric' => $this->request->getData () ['pricing'] ['metric'],
                                                'price' => number_format ($this->request->getData () ['pricing'] ['price']),
                                                'cost' => number_format ($this->request->getData () ['pricing'] ['cost'])]);               
            $pricingTable->save ($pricing);

            $this->updateItems ($pricing ['id'], json_decode ($pricing ['params'], true));
        }

        $this->set ('response', ['status' => '0']);
    }
   
    private function updateItems ($pricingID, $params) {


        $this->debug ('pricing update items... ' . $pricingID);
        $this->debug ($params);

        $itemPricesTable = TableRegistry::get ('ItemPrices');

        $query = $itemPricesTable
               ->find ()
               ->where (['pricing_id' => $pricingID]);

        foreach ($query as $itemPrice) {

            $itemPrice ['pricing'] = json_decode ($itemPrice ['pricing'], true);
            $this->debug ($itemPrice);
            $itemPrice ['pricing'] ['price'] = $params ['price'];
            $itemPrice ['pricing'] ['amount'] = $params ['price'];
            $itemPrice ['pricing'] ['cost'] = $params ['cost'];
            $itemPrice ['pricing'] = json_encode ($itemPrice ['pricing']);

            $this->save ('ItemPrices', $itemPrice);
        }
    }
}

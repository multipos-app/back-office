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

class SearchController extends PosAppController {
    
    public $paginate = ['limit' => 20,
                        'order' => ['item_desc asc']];

    public function initialize (): void{
    
        parent::initialize ();
        
    }

    public function items ($fields, $search = 'sku_and_desc') {
        
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];
    
        if (strlen ($search) > 0) {
          
            $query = TableRegistry::get ('Items')
											 ->find ()
											 ->where (['or' => ["sku like '" . $search."%'",
																	  "item_desc like '" . $search."%'"]])
                                  ->contain (['ItemPrices'])
											 ->limit (10);
            
            $response = [];
            
            foreach ($query as $item) {
                
                $name = '';
                switch ($fields) {
                    
                case 'sku_and_desc':
                    
                    $name = $item ['sku'] . ' - ' .$item ['item_desc'];
                    break;
                    
                case 'item_desc':
                    
                    $name = $item ['item_desc'];
                    break;
                }
                
                $response [] = ['id' => $item ['id'],
                                'name' => $name,
										  'item' => $item];
            }
        }
    
        $this->set (['response' => $response]);
    }
    
    public function invItems ($supplierID, $search) {

        $this->debug ("search... $supplierID $search");
        
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];
    
        if (strlen ($search) > 0) {
          
            $query = TableRegistry::get ('Items')
                   ->find ()
                   ->where (['inv_items.supplier_id' => $supplierID,
                             'inv_items.order_quantity = 0',
                             'or' => ["Items.sku like '" . $search."%'",
                                      "Items.item_desc like '" . $search."%'"]])
                   ->contain (['InvItems', 'ItemPrices'])
                   ->join (['table' => 'inv_items',
                            'type' => 'right',
                            'conditions' => 'Items.id = inv_items.item_id'])
                   ->limit (10);

            $response = [];
            
            foreach ($query as $item) {

                $item = ['item_id' => $item ['id'],
                         'sku' => $item ['sku'],
                         'item_desc' => $item ['item_desc'],
                         'inv_item_id' => $item ['inv_items'] [0] ['id'],
                         'on_hand_quantity' => $item ['inv_items'] [0] ['on_hand_quantity'],
                         'package_quantity' => $item ['inv_items'] [0] ['package_quantity'],
                         'cost' => $item ['item_prices'] [0] ['cost'],
                         'item_prices_id' => $item ['item_prices'] [0] ['id'],
                         'order_quantity' => 0,
                         'add_item' => true];
                
                $response [] = ['id' => $item ['item_id'],
                                'name' => $item ['sku'] . ' - ' . $item ['item_desc'],
                                'item_detail' => $item];
            }
        }
    
        $this->set (['response' => $response]);
    }

    public function employeeUser ($search) {

        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];
    
        if (strlen ($search) > 0) {
          
            $query = TableRegistry::get ('Employees')
                   ->find ()
                   ->where (["username like '" . $search."%'"])
                   ->limit (10);

            $response = [];
        
            foreach ($query as $employee) {
            
                $response [] = ['id' => $employee ['id'],
                                'name' => $employee ['username'] . ' - ' . $employee ['fname'] . ' - ' . $employee ['lname']];
            }
        }
    
        $this->set (['response' => $response]);
    }
    
    public function employeeName ($search) {

        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];
    
        if (strlen ($search) > 0) {
          
            $query = TableRegistry::get ('Employees')
                   ->find ()
                   ->where (['or' => ["fname like '" . $search."%'",
                                      "lname like '" . $search."%'"]])
                   ->limit (10);

            $response = [];
        
            foreach ($query as $employee) {
            
                $response [] = ['id' => $employee ['id'],
                                'name' => $employee ['username'] . ' - ' . $employee ['fname'] . ' - ' . $employee ['lname']];
            }
        }
    
        $this->set (['response' => $response]);
    }
    
    public function employeeProfile ($search) {

        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];
    
        if (strlen ($search) > 0) {
          
            $query = TableRegistry::get ('Employees')
                   ->find ()
                   ->where (['profile_id' => $search])
                   ->limit (10);

            $response = [];
        
            foreach ($query as $employee) {
            
                $response [] = ['id' => $employee ['id'],
                                'name' => $employee ['username'] . ' - ' . $employee ['fname'] . ' - ' . $employee ['lname']];
            }
        }
    
        $this->set (['response' => $response]);
    }

    public function customers ($field, $search) {

        $this->debug ("customers... $field $search");
        
        $this->viewBuilder ()->setLayout ('ajax');
        $response = [];
    
        if (strlen ($search) > 0) {
          
            $query = TableRegistry::get ('Customers')
                   ->find ()
                   ->where ([$field . " like '" . $search . "%'"])
                   ->limit (10);

            $response = [];
        
            foreach ($query as $customer) {

                $name = $customer [$field];
                
                switch ($field) {

                case 'phone':
                    
                    $name = $this->phoneFormat ($customer [$field]);
                    break;

                default:
                    break;
                }
                
                $response [] = ['id' => $customer ['id'],
                                'name' => $name];
            }
        }
        
        $this->set (['response' => $response]);
    }
}

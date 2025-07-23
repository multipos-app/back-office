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

class InventoryController extends PosAppController {

	 public $paginate = ['limit' => 25];
	 
    public function initialize (): void {
		  
        parent::initialize ();
    }
    
    public function index (...$args) {

		  $where = [];
 		  $itemsTable = TableRegistry::get ('Items');
		  
		  /* $join [] = ['table' => 'departments',
			  'type' => 'left',
			  'conditions' => ['departments.inventory > 0',
			  'Items.department_id = departments.id']];*/
		  
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
				}

				$q = $itemsTable->find ()
									 ->distinct ()
                            ->where ($where)
                            ->contain (['InvItems']);
		  }
        else {
				
            $q = $itemsTable
               ->find ()
					->where ($where)
					->order (['sku asc'])
               ->contain (['InvItems']);
        }

 		  // $this->debug ($q);
		  
        $items = $this->paginate ($q);

		  // get departments and suppliers for search
		  
        $departments = [null => __ ('Department')];
		  $query = TableRegistry::get ('Departments')
										->find ()
										->where ('inventory > 0')  // inventory is set
										->all ();
		  
		  foreach ($query as $d) {
            
            $departments [$d ['id']] = $d ['department_desc'];
        }
        
        $suppliers = [null => __ ('Suppliers')];
        $query = TableRegistry::get ('Suppliers')
										->find ()
										->order (['supplier_name' => 'asc']);
        
        foreach ($query as $d) {
            
            $suppliers [$d ['id']] = $d ['supplier_name'];
        }
		  
		  $this->set (['departments' => $departments,
							'suppliers' => $suppliers,
							'items' => $items]);
	 }
	 
    public function edit ($id) {
		  				
		  if (!empty ($this->request->getData ())) {
				
				$update = $this->update ($id, $this->request->getData ());
				
				$this->ajax (['status' => 0,
								  'item' => $update]);
				return;
		  }

		  $item = TableRegistry::get ('Items')->find ()
									  ->where (['id' => $id])
									  ->contain (['InvItems'])
									  ->first ();

		  $item ['inv_item'] = $item ['inv_items'] [0];
		  unset ($item ['inv_items']);
		  
		  $editTitle = $item ['item_desc'];
        $suppliers = [null => __ ('Suppliers')];
        $query = TableRegistry::get ('Suppliers')
										->find ()
										->order (['supplier_name' => 'asc']);
        
        foreach ($query as $d) {
            
            $suppliers [$d ['id']] = $d ['supplier_name'];
        }
		  
 		  $this->set (['item' => $item,
							'editTitle' => $editTitle,
							'suppliers' => $suppliers]);
	
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Inventory')
								->setTemplate ('edit');

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }

	 public function update ($id, $update) {
		  

		  $this->debug ($update);
		  
		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';
		  
		  $invItemsTable = TableRegistry::get ('InvItems');

		  $invItemsTable->updateAll (['package_quantity' => $update ['inv_item'] ['package_quantity'],
												'on_hand_req' => $update ['inv_item'] ['on_hand_req'],
												'on_hand_count' => $update ['inv_item'] ['on_hand_count'],
												'supplier_id' => $update ['inv_item'] ['supplier_id']],
											  ['id' => $update ['inv_item_id']]);
		  return 0;
	 }
}

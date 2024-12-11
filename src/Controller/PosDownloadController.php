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

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use \DateTime;
use \DateTimeZone;
use Cake\I18n\I18n;
use App\Controller\PosApiController;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class PosDownloadController extends PosApiController {

    private $models = ['items' => 'Items',
                       'item_prices' => 'ItemPrices',
                       'business_units' => 'BusinessUnits',
                       'customers' => 'Customers',
                       'departments' => 'Departments',
                       'department_mods' => 'DepartmentMods',
                       'employees' => 'Employees',
                       'item_links' => 'ItemLinks',
                       'addons' => 'Addons',
                       'addon_links' => 'AddonLinks',
                       'profiles' => 'Profiles',
                       'profile_permissions' => 'ProfilePermissions',
                       'tax_groups' => 'TaxGroups',
                       'taxes' => 'Taxes',
                       'tenders' => 'Tenders',
                       'pos_configs' => 'PosConfigs',
                       'currencies' => 'Currencies', 
                       'currency_denoms' => 'CurrencyDenoms',
                       'suppliers' => 'Suppliers',
                       'pos_messages' => 'PosMessages'];

    public function index () {

		  $post = $this->request->getData ();
		  $post ['remote'] = $_SERVER ['HTTP_X_FORWARDED_FOR'];
        $this->debug ($post);
		  
		  $buID = $this->request->getData () ['business_unit_id'];
        
        if (!isset ($this->request->getData ()  ['merchant_id'])) {
            
            $this->log ('invalid merchant id', 'error');
            $this->jsonResponse (['status' => 1]);
            return;      
        }
        
        $this->dbconnect ('m_' . $this->request->getData ()  ['merchant_id']);
		          
        $limit = 25;

        $where = ['id > ' . $this->request->getData ()  ['update_id']];
		  
        $query = TableRegistry::get ('BatchEntries')
										->find ()
										->select (['id',
													  'business_unit_id', 
													  'update_time', 
													  'update_table', 
													  'update_id', 
													  'update_action'])
										->where ($where)
										->limit ($limit);

        $total = $query->count ();
        $updates = [];
		  
        foreach ($query as $update) {

            $update = $update->toArray ();				
				$where = ['id' => $update ['update_id']];
				
				unset ($update ['update_time']);
				unset ($update ['execution_time']);
            
				switch ($update ['update_action']) {
						  
					 case 0:

						  switch ($update ['update_table']) {

								case 'items':
									 
									 $item = TableRegistry::get ('Items')
																 ->find ()
																 ->where (['id' => $update ['update_id']])
																 ->first ();
									 
									 if ($item) {

										  $item = $item->toArray ();

										  foreach (['item_prices' => 'ItemPrices', 'item_links' => 'ItemLinks'] as $assoc => $table) {

												if (!isset ($item [$assoc])) {

													 $item [$assoc] = [];
												}

												$where = ['item_id' => $item ['id']];
												if ($table == 'item_prices') {

													 $where ['business_unit_id'] = $buID;
												}
													 
												$query = TableRegistry::get ($table)
																			 ->find ()
																			 ->where ($where);
												
												foreach ($query as $t) {

													 $item [$assoc] [] = $t->toArray ();
												}
										  }
										  
										  $update ['update'] = $item;
										  $updates [] = $update;
										  
										  unset ($item ['supplier_id']);
									 }
									 
									 break;
									 
								default:
									 
									 $query = TableRegistry::get ($this->models [$update ['update_table']])
																  ->find ()
																  ->where ($where);
									 									 
									 if ($entity = $query->first ()) {
										  
										  $entity = $entity->toArray ();
										  
										  unset ($entity ['update_time']);
										  
										  switch ($update ['update_table']) {
													 
												case 'departments':
													 
													 unset ($entity ['pricing']);
													 $entity ['tax_group_id'] = 0;
													 $entity ['update_time'] = "'".date ('Y-m-d H:i:s')."'";
													 break;

												case 'addons':
													 
													 $entity ['description'] = $entity ['print_description'];
													 unset ($entity ['print_description']);
													 break;
													 
												case 'addon_links':
													 
													 $entity ['addon_id'] = $entity ['addon_id'];
													 unset ($entity ['addon_id']);
													 break;

												case 'employees':
													 
													 unset ($entity ['logged_on']);
													 unset ($entity ['hourly_rate']);
													 unset ($entity ['ot_eligible']);            
													 break;

												case 'bo_updates':
													 
													 unset ($entity ['execution_time']);
													 unset ($entity ['batch_id']);
													 break;
													 
												case 'pos_configs':
													 
													 if ($entity ['id'] != $this->request->getData () ['pos_config_id']) {
														  
														  $update = null;
														  $total --;
													 }
													 else {

														  $tmp = json_decode ($entity ['config'], true);
														  
														  unset ($tmp ['edit_menus']);
														  $entity ['config'] = json_encode ($tmp);
														  
														  unset ($entity ['create_time']);
														  unset ($entity ['config_type']);
														  unset ($entity ['status']);
														  unset ($entity ['business_unit_id']);
														  unset ($entity ['config_template']);
													 }
													 
													 break;
													 
												case 'addons':
												case 'addon_links':

													 break;
													 
												case 'profiles':
													 
													 $update ['update'] = $entity;
													 
													 $updates [] = $update;
													 
													 $permissions = TableRegistry::get ('ProfilePermissions')
																						  ->find ()
																						  ->where (['profile_id' => $update ['update'] ['id']]);
													 
													 foreach ($permissions as $permission) {
														  
														  $update ['update'] = $permission->toArray ();
														  $update ['update_table'] = 'profile_permissions';
														  $updates [] = $update;
													 }

													 $update = null;
													 break;
										  }
									 }

									 if ($update != null) {
										  
										  $update ['update'] = $entity;
										  $updates [] = $update;
									 }
						  }
						  
						  break;
						  
					 case 1:
						  
						  $updates [] = $update;
						  break;
						  
					 case 100:
						  
						  TableRegistry::get ('BatchEntries')->delete ($update);
						  $response ['command'] = 'toggle_server_log';
						  break;
				}  
		  }
		  
		  $response = ['count' => count ($updates),
							'total' => $total,
							'updates' => $updates];

		  $this->debug ($response);
		  
		  $this->jsonResponse ($response);
	 }

	 public function posMessage () {
		  
		  if (!empty ($this->request->getData ())) {
				
				$this->debug ($this->request->getData ());
		  }
		  
		  $this->jsonResponse (['status' => 0]);
	 }
}
?>

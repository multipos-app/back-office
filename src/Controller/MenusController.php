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

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class MenusController extends PosAppController {
    
    public function menu ($configID, $menuName = null, $menuIndex = 0) {
		  
        $posConfigTable = TableRegistry::get ('PosConfigs');
        $posConfig = $posConfigTable
                   ->find ()
                   ->where (['id' => $configID])
                   ->first ();
        
        $posConfig = $posConfig->toArray ();
        $posConfig ['config'] = json_decode ($posConfig ['config'], true);
		  
		  $menus = [];

		  if ($menuName == null) {
				
				// get the name of the first menu
				
				foreach ($posConfig ['config'] ['pos_menus'] as $key => $value) {
					 
		  			 if (!$menuName) {
						  
						  $menuName = $key;
						  break;
					 }
				}
		  }

		  foreach ($posConfig ['config'] ['pos_menus'] [$menuName] ['horizontal_menus'] as $menu) {

				$menus [] = $menu ['name'];
		  }

        $this-> set (['posConfig' => $posConfig,
							 'menus' => $menus,
							 'menuName' => $menuName,
							 'menuIndex' => $menuIndex]);
	 }

	 public function update () {

		  if (!empty ($this->request->getData ())) {

				$update = $this->request->getData ();

				$posConfigsTable = TableRegistry::get ('PosConfigs');
				$posConfig = $posConfigsTable
                   ->find ()
                   ->where (['id' => $update ['config_id']])
                   ->first ();

				// decode the original
				
				$posConfig ['config'] = json_decode ($posConfig ['config'], true);

				// update with new menu
				
				$posConfig ['config'] ['pos_menus'] [$update ['menu_name']] ['horizontal_menus'] [$update ['menu_index']] = $update ['menu'];
							  
				// encode updated config
								
				$posConfig ['config'] = json_encode ($posConfig ['config']);
				
				// $posConfigsTable->save ($posConfig);
				
				$this->save ('PosConfigs', $posConfig);
				$this->ajax (['status' => 0]);
		  }
	 }
}

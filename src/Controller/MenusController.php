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

	 /**
	 *
	 *
	 *
	 **/
    // public function index ($configID, $menuName, $menuIndex) {
		  
	 function index (...$args) {
		  
		  $configID = 0;
		  $menuName = null;
		  $menuIndex = 0;
		  $posConfig = null;
		  
		  switch (count ($args)) {
					 
				case 3:
					 
					 $configID = array_shift ($args);
					 $menuName = array_shift ($args);
					 $menuIndex = array_shift ($args);
					 break;

				case 2:
					 
					 $configID = array_shift ($args);
					 $menuName = array_shift ($args);
					 break;
				 
				case 1:
					 
					 $configID = array_shift ($args);
					 break;

				default:
					 
		  			 $this->error ("menus index bad params...");
					 return $this->redirect ('pos-configs');
		  }

		  if ($configID > 0) {
				
				$posConfigTable = TableRegistry::get ('PosConfigs');
				$posConfig = $posConfigTable
                   ->find ()
                   ->where (['id' => $configID])
                   ->first ();
		  }

		  if ($posConfig) {
	 
					 $posConfig = $posConfig->toArray ();
					 $posConfig ['config'] = json_decode ($posConfig ['config'], true);
		  }
		  else {
				
				$this->error ("menus index invalid config id... $configID");
				return $this->redirect ('pos-configs');	
		  }
				
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
		  
		  $controls = TableRegistry::get ('PosControlCategories')
											->find ()
											->contain (['PosControls'])
											->all ();
		  
        $this-> set (['controls' => $controls,
							 'posConfig' => $posConfig,
							 'menus' => $menus,
							 'menuName' => $menuName,
							 'menuIndex' => $menuIndex,
							 'menuActions' => ['' => __ ('Actions'),
													 'menu_action_insert' => __ ('Insert submenu'),
													 'menu_action_append' => __ ('Append submenu'),
													 'menu_action_resize' => __ ('Resize'),
													 'menu_action_settings' => __ ('Settings'),
													 'menu_action_delete' => __ ('Delete')]]);
	 }

	 /**
	 *
	 *
	 *
	 **/
    /* public function menu ($configID, $menuName = null, $menuIndex = 0) {
		 
	  *     $posConfigTable = TableRegistry::get ('PosConfigs');
	  *     $posConfig = $posConfigTable
	  *                ->find ()
	  *                ->where (['id' => $configID])
	  *                ->first ();
	  *     
	  *     $posConfig = $posConfig->toArray ();
	  *     $posConfig ['config'] = json_decode ($posConfig ['config'], true);
		 
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
		 
		 $controls = TableRegistry::get ('PosControlCategories')
		 ->find ()
		 ->contain (['PosControls'])
		 ->all ();
		 
	  *     $this-> set (['controls' => $controls,
		 'posConfig' => $posConfig,
		 'menus' => $menus,
		 'menuName' => $menuName,
		 'menuIndex' => $menuIndex,
		 'menuActions' => [null => __ ('Actions'),
		 'menu_action_insert' => __ ('Insert submenu'),
		 'menu_action_append' => __ ('Append submenu'),
		 'menu_action_resize' => __ ('Resize'),
		 'menu_action_settings' => __ ('Settings'),
		 'menu_action_delete' => __ ('Delete')]]);
		 }
	  */
	 /**
	 *
	 *
	 *
	 **/
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
	 
	 /**
	 *
	 *
	 *
	 **/
	 public function action ($action, $configID, $menuName, $menuIndex) {

		  $this->debug ("menu actions... $action, $configID, $menuName, $menuIndex");
		  
		  $posConfigsTable = TableRegistry::get ('PosConfigs');
		  $posConfig = $posConfigsTable
                   ->find ()
                   ->where (['id' => $configID])
                   ->first ();

		  $config = json_decode ($posConfig ['config'], true);
		  
		  if (!empty ($this->request->getData ())) {

				$update = $this->request->getData ();
				
				switch ($update ['action']) {

					 case 'menu_action_insert':

						  $index = $menuIndex > 0 ? $menuIndex - 1 : 0;
						  
						  $submenu = $this->submenu (strtoupper ($update ['menu_name']),
															  '#555555',
															  $update ['cols'],
															  $update ['rows'] * $update ['cols']);
						  						  						  						  
						  array_splice ($config ['pos_menus'] [$menuName] ['horizontal_menus'], $index, 0, $submenu);
						  break;
						  
					 case 'menu_action_append':
						  
						  $submenu = $this->submenu (strtoupper ($update ['menu_name']),
															  '#555555',
															  $update ['cols'],
															  $update ['rows'] * $update ['cols']);
						  						  						  						  
						  array_splice ($config ['pos_menus'] [$menuName] ['horizontal_menus'], $menuIndex + 1, 0, $submenu);
						  break;

					 case 'menu_action_settings':
						  
						  $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['name'] = strtoupper ($update ['menu_name']);
						  $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['color'] = $update ['tab_color'];
						  $config ['pos_menus'] [$menuName] ['tabs'] = $update ['menu_tabs'] == 'on';						  
						  break;
						  
					 case 'menu_action_resize':

						  $curSize = count ($config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons']);
						  $newSize = intval ($update ['rows']) * intval ($update ['cols']);
						  
						  $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['width'] = $update ['cols'];

						  if ($newSize > $curSize) {
								
								for ($i = 0; $i < ($newSize - $curSize); $i ++) {

									 array_push ($config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons'], $this->empty ());
								}
						  }
						  else {
								
								for ($i = 0; $i < ($curSize - $newSize); $i ++) {
									 
									 array_pop ($config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons']);
								}
						  }
						  
						  break;
						  
					 case 'menu_action_delete':
						  
						  array_splice ($config ['pos_menus'] [$menuName] ['horizontal_menus'], $menuIndex, 1);
						  break;
				}
				
				$posConfig ['config'] = json_encode ($config);
				$this->save ('PosConfigs', $posConfig);
				
				return $this->redirect ("/menus/index/$configID/$menuName/0");
		  }

		  $template = $action;
		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'colors.php';
		  
		  switch ($action) {

				case 'menu_action_insert':
				case 'menu_action_append':
					 
					 $template = 'menu_action_submenu';
					 break;
		  }
		  
		  $actionsTitle = ['menu_action_insert' => __ ('Insert submenu'),
								 'menu_action_append' => __ ('Append submenu'),
								 'menu_action_resize' => __ ('Resize'),
								 'menu_action_settings' => __ ('Settings'),
								 'menu_action_delete' => __ ('Delete')];
		  
		  $this->set (['configID' => $configID,
							'menuName' => $menuName,
							'menuIndex' => $menuIndex,
							'name' => $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['name'],
							'defaultColor' => $defaultColor,
							'colors' => $colors,
		  					'action' => $action]);
		  
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Menus')
								->setTemplate ($template);

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'title' => $actionsTitle [$action],
							 'html' => $html]);
	 }

	 /**
	 *
	 *
	 *
	 **/
	 private function submenu ($name, $color, $cols, $count) {
		  
		  $menu = ['type' => 'controls',
					  'style' => 'outline',
					  'name' => strtoupper ($name),
					  'width' => $cols,
					  'buttons' => []];
	
		  for ($i = 0; $i < $count; $i ++) {

				$menu ['buttons'] [] = $this->empty ();;
		  }

		  return [$menu];
	 }

	 private function empty () {

		  return ["text" => "",
					 "class" => "Null",
					 "color" => "transparent"];
	 }
}

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

class ButtonsController extends PosAppController {
    
	 /**
	  *
	  * edit a button
	  *
	  **/
	 
    function index (...$args) {

        if (!empty ($this->request->getData ())) {

				$button = $this->request->getData ();
				
				$configID = $button ['config_id'];
				$menuName = $button ['menu_name'];
				$menuIndex = $button ['menu_index'];
				$pos = $button ['pos'];
				$params = [];
				
				$this->debug ('buttons controller index... ');
				$this->debug ($button);
				
				$data = ['configID' => $configID,
							'menuName' => $menuName,
							'menuIndex' => $menuIndex,
							'pos' => $pos,
							'detail' => 'empty',
							'colors' => $this->colors ()];

				
				if ($button ['class'] == 'Null') {
					 
					 // start a new button, prompt with list of available controls
				 
					 $template = 'control_select';
					 $controls = TableRegistry::get ('PosControlCategories')
													  ->find ()
													  ->contain (['PosControls'])
													  ->where (['enabled' => 1])
													  ->all ();
					 
					 $data ['controls'] = $controls;
				}
				else {

					 // edit an existing button
					 
					 $posConfig = TableRegistry::get ('PosConfigs')
														->find ()
														->where (['id' => $configID])
														->first ();
				
					 if (!empty ($button)) {
						  
						  if (isset ($button ['class'])) {
								
								$class = $this->javaClassToTemplate ($this->request->getData ('class'));
						  }
					 }
					 
					 if ($posConfig) {
						  
						  // decode the config
						  
						  $config = json_decode ($posConfig ['config'], true);
						  
						  // start view setup
						  
						  $template = 'index';
						  $params = [];
						  $button = $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons'] [$pos];
						  $data ['button'] = $button;
						  
						  // use the config class to get the view temlate

						  switch ($button ['class']) {

								case 'CashTender':
								
									 $data ['tenderOpts'] = [null => __ ('Select tender amount'),
																	 '-1' => __ ('Amount of sale'),
																	 '0' => __ ('Round up'),
																	 '500' => __ ('$5'),
																	 '1000' => __ ('$10'),
																	 '2000' => __ ('$20'),
																	 '5000' => __ ('$50')];
									 
									 $data ['tenderValue'] = $button ['params'] ['value'];
									 $data ['detail'] = $this-> javaClassToTemplate ($button ['class']);

									 break;
									 
								case 'Item':
								case 'DefaultItem':
									 									 
									 $data ['item'] = TableRegistry::get ('Items')
																			 ->find ()
																			 ->where (['sku' => $button ['params'] ['sku']])
																			 ->first ();
									 
									 $data ['detail'] = $this-> javaClassToTemplate ($button ['class']);
									 break;
									 
								case 'EpxTender':
									 $data ['detail'] = 'epx_tender';
									 break;
									 
								default:
									 
									 $data ['detail'] = 'no_params';
									 break;
						  }
					 }
					 else {

						  $this->error ('buttons index, invalid config...');
						  $this->ajax (['status' => 2]);
						  return;
					 }
				}

				// detail used in button index view
								
				$this->set ($data);
				
				$builder = $this->viewBuilder ()
									 ->disableAutoLayout ()
									 ->setTemplatePath ('Buttons')
									 ->setTemplate ($template);
				
				$view = $builder->build ();
				$html = $view->render ();
				
				$this->ajax (['status' => 0,
								  'html' => $html,
								  'params' => $params]);
				return;
		  }
		  
		  $this->error ('buttons index, empty post...');
		  $this->ajax (['status' => 1]);
	 }
	 
	 /**
	  *
	  * update a button
	  *
	  **/
	 
    function update () {

		  $status = 1;
		  
		  $this->debug ('buttons update... ');
		  $this->debug ($this->request->getData ());
		  
		  if (!empty ($this->request->getData ())) {

				$update = $this->request->getData ();
				
				$configID = $this->request->getData () ['config_id'];
				$menuName = $this->request->getData () ['menu_name'];
				$menuIndex = $this->request->getData () ['menu_index'];
				$pos = $this->request->getData () ['pos'];

				$posConfigsTable = TableRegistry::get ('PosConfigs');
				$posConfig = $posConfigsTable
												  ->find ()
												  ->where (['id' => $configID])
												  ->first ();
				
				$config = json_decode ($posConfig ['config'], true);
				$button = $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons'] [$pos];
				
				$this->debug ('org button... ');
				$this->debug ($button);
				
				$button ['text'] = strtoupper ($update ['text']);
				$button ['color'] = $update ['color'];
				$button ['class'] = $update ['class'];
				$button ['params'] = $update ['params'];
				
				$this->debug ('new button... ');
				$this->debug ($button);
				
				$config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons'] [$pos] = $button;
				$posConfig ['config'] = json_encode ($config);

				$posConfigsTable->save ($posConfig);

				$status = 0;
		  }
		  
		  $this->ajax (['status' => $status]);
	 }

	 /**
	  *
	  * post select button control
	  *
	  **/
	 
    function selectControlTemplate () {

		  if (!empty ($this->request->getData ())) {
				
				$button = $this->request->getData ();
				
				$data = ['configID' => $button ['config_id'],
							'menuName' => $button ['menu_name'],
							'menuIndex' => $button ['menu_index'],
							'pos' => $button ['pos']];
				
				$html = '';

				$this->debug ('buttons controller select template... ');
				$this->debug ($button);
				$template = 'index';
				
				switch ($button ['button_type']) {
						  
					 case 'Item':
					 case 'DefaultItem':

						  // prompt for existing item or item pricing type
						  
						  $this->set (['itemOpts' => [null => __ ('Use existing item or create new item?'),
																'existing' => __ ('Existing item'),
																'standard' => __ ('Standard pricing, one price per item'),
																'variant' => __ ('Variant pricing (size, color...)'),
																'open' => __ ('Open/enter price, prompt for price'),
																'metric' => __ ('Price by volume/weight, prompt for value')]]);
						  $template = 'item_select';
						  
						  break;

					 case 'CashTender':
						  
						  $data ['tenderOpts'] = [null => __ ('Select tender amount'),
														  '-1' => __ ('Amount of sale'),
														  '0' => __ ('Round up'),
														  '500' => __ ('$5'),
														  '1000' => __ ('$10'),
														  '2000' => __ ('$20'),
														  '5000' => __ ('$50')];
						  
						  $data ['tenderValue'] = null;
						  $data ['detail'] = $this->javaClassToTemplate ($button ['button_type']);
						  $data ['button'] = ['class' => 'CashTender',
													 'text' => '',
													 'color' => $this->defaultColor ()];
						  
						  $data ['colors'] = $this->colors ();
						  break;
						  
					 default:
						  
						  $data ['detail'] = 'no_params';
						  $data ['button'] = ['class' => $button ['button_type'],
													 'text' => $button ['button_text'],
													 'color' => $this->defaultColor ()];

						  $this->debug ($data);
						  
						  $data ['colors'] = $this->colors ();
				}
				
				$this->set ($data);
				$builder = $this->viewBuilder ()
									 ->disableAutoLayout ()
									 ->setTemplatePath ('Buttons')
									 ->setTemplate ($template);
				
				$view = $builder->build ();
				$html = $view->render ();
				$this->ajax (['status' => 0,
								  'html' => $html]);
		  }
		  else {

				$this->ajax (['status' => 1,
								  'status_text' => __ ('button type not supported')]);
		  }
	 }

	 /**
	  *
	  * add an item button, prompt for existing item or new item
	  *
	  **/
	 
    function itemType () {
		  
		  if (!empty ($this->request->getData ())) {
				
				$template = '';
				$html = '';
				$button = $this->request->getData ();
				
				$this->debug ('buttons controller item type... ');
				$this->debug ($button);
				
				$data = ['configID' => $button ['config_id'],
							'menuName' => $button ['menu_name'],
							'menuIndex' => $button ['menu_index'],
							'pos' => $button ['pos'],
							'colors' => $this->colors ()];
				
				switch ($button ['item_opts']) {

					 case 'existing':

						  $template = 'item_search';
						  $this->set ($data);
						  break;
						  
					 default:
						  
						  $data = array_merge ($data, ['button' => ['class' => 'Null', 'text' => '', 'color' => $this->defaultColor ()],
																 'colors' => $this->colors (),
																 'detail' => 'item',
																 'item' => ['id' => 0,
																				'item_template' => $button ['item_opts']]]);
								
						  $template = 'index';
						  $this->set ($data);
						  break;

				}
				
				$builder = $this->viewBuilder ()
									 ->disableAutoLayout ()
									 ->setTemplatePath ('Buttons')
									 ->setTemplate ($template);
				
				$view = $builder->build ();
				$html = $view->render ();
				
				$this->debug ("button type template... $template");
				
				$this->ajax (['status' => 0,
								  'html' => $html]);
		  }
		  else {
					$this->ajax (['status' => 1]);
		  }
	 }

	 function itemAdd () {

		  $button = $this->request->getData ();

		  $this->debug ('buttons controller item-add... ');
		  $this->debug ($button);
		  

		  $posConfigsTable = TableRegistry::get ('PosConfigs');
		  $posConfig = $posConfigsTable
											 ->find ()
											 ->where (['id' => $button ['config_id']])
											 ->first ();

		  $status = 1;
		  if ($posConfig) {
				
				$config = json_decode ($posConfig ['config'], true);
				$config ['pos_menus'] [$button ['menu_name']] ['horizontal_menus'] [$button ['menu_index']] ['buttons'] [$button ['pos']] = $button ['button'];
				$posConfig ['config'] = json_encode ($config);
				$posConfigsTable->save ($posConfig);
				$status = 0;
		  }
		  
		  $this->ajax (['status' => $status]);
	 }

	 private function javaClassToTemplate ($javaClass) {
		  
	 	  $chars = str_split ($javaClass);
		  $tmp = '';
		  $index = 0;
		  
		  foreach ($chars as $char) {
				
				if (ctype_upper ($char) && ($index > 0)) {
					 
					 $tmp .= '_';
				}
				$tmp .= strtolower ($char);
				$index ++;
		  }

		  return $tmp;
	 }

	 private function colors () {
		  
		  return ['#006482',
					 '#0172BB',
					 '#018380',
					 '#026851',
					 '#0A0A0A',
					 '#0E4E93',
					 '#111E6C',
					 '#1A3A46',
					 '#264E3A',
					 '#2C262D',
					 '#2E183D',
					 '#3062A5',
					 '#512D55',
					 '#679ACD',
					 '#696969',
					 '#8C0306',
					 '#007002',
					 '#21512D',
					 '#42008A',
					 '#B2005B',
					 '#7F4F4F',
					 '#6161CD',
					 '#520100',
					 '#8F2703'];
	 }

	 private function defaultColor () { return '#006482'; }
}

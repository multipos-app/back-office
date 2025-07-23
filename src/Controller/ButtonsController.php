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

		  include ROOT . DS . 'src' . DS  . 'Controller' . DS . 'colors_oasis.php';
		  
        if (!empty ($this->request->getData ())) {

				$button = $this->request->getData ();

				$this->debug ($button);
				
				$configID = $button ['config_id'];
				$menuName = $button ['menu_name'];
				$menuIndex = $button ['menu_index'];
				$pos = $button ['pos'];
				$params = [];
				
				$data = ['configID' => $configID,
							'menuName' => $menuName,
							'menuIndex' => $menuIndex,
							'pos' => $pos,
							'detail' => 'empty',
							'colors' => $colors];
				
				$posConfig = TableRegistry::get ('PosConfigs')
												  ->find ()
												  ->where (['id' => $configID])
												  ->first ();
				if (!$posConfig) {

					 $this->error ("config not found... $configID");
					 return $this->redirect ('/pos-configs');
				}
				
				// decode the config
				
				$config = json_decode ($posConfig ['config'], true);
				
				// default outher template
				
				$template = 'index';
				
				$configButton = $config ['pos_menus'] [$menuName] ['horizontal_menus'] [$menuIndex] ['buttons'] [$pos];
				
				if ($configButton ['class'] == 'Null') {

					 $data ['button'] = ['class' => $button ['class'],
												'color' => $defaultColor,
												'text' => ''];
				}
				else {
					 
					 $data ['button'] = $configButton;
				}
		
				// use the config class to get inner template

				$data ['detail'] = $this-> javaClassToTemplate ($data ['button'] ['class']);
				
				switch ($data ['button'] ['class']) {

					 case 'CashTender':
						  
						  $data ['tenderOpts'] = [null => __ ('Select tender amount'),
														  '-1' => __ ('Amount of sale'),
														  '0' => __ ('Round up'),
														  '500' => __ ('$5'),
														  '1000' => __ ('$10'),
														  '2000' => __ ('$20'),
														  '5000' => __ ('$50')];
						  
						  if (!isset ($data ['button'] ['params'])) {

								$data ['button'] ['params'] = ['value' => null];
						  }
						  break;
						  
					 case 'Item':
					 case 'DefaultItem':

						  if (!isset ($data ['button'] ['params'])) {

								// prompt for existing item or item pricing type
						  
								$this->set (['itemOpts' => [null => __ ('Use existing item or create new item?'),
																	 'existing' => __ ('Existing item'),
																	 'standard' => __ ('Standard pricing, one price per item'),
																	 'variant' => __ ('Variant pricing (size, color...)'),
																	 'open' => __ ('Open/enter price, prompt for price'),
																	 'metric' => __ ('Price by volume/weight, prompt for value')]]);
								$template = 'item_select';
						  }
						  else {
																
								$data ['item'] = TableRegistry::get ('Items')
																		->find ()
																		->where (['sku' => $data ['button'] ['params'] ['sku']])
																		->first ();
						  }
						  break;
						  
					 case 'Navigate':

						  $this->debug ($data ['button']);
						  
						  $menus = [null => __ ('Select a menu')];
						  $subMenus = [null => __ ('Select a sub-menu')];

						  foreach ($config ['pos_menus'] as $mn => $m) {

								$menus [$mn] = $m ['menu_description'];

								foreach ($m ['horizontal_menus'] as $subMenu) {

									 $subMenus [$mn] [] = $subMenu ['name'];
								}
						  }
						  
						  $this->set (['menus' => $menus,
											'subMenus' => $subMenus]);
						  
						  break;
						  
					 case 'ItemMarkdownPercent':
					 case 'ItemMarkdownAmount':
					 case 'SaleDiscount':
						  
						  break;
						  
					 default:
						  
						  $data ['detail'] = 'no_params';
						  break;
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
				return;
		  }
		  
		  $this->error ('buttons index, empty post...');
		  $this->ajax (['status' => 1]);
	 }
	 
	 /**
	  *
	  * post select button control
	  *
	  **/
	 
    function selectControlTemplate () {
		  
		  include ROOT . DS . 'src' . DS  . 'Controller' . DS . 'colors_oasis.php';
		  if (!empty ($this->request->getData ())) {
				
				$button = $this->request->getData ();
				
				$data = ['configID' => $button ['config_id'],
							'menuName' => $button ['menu_name'],
							'menuIndex' => $button ['menu_index'],
							'pos' => $button ['pos'],
							'colors' => $colors];

				$html = '';
				$template = 'index';
				
				switch ($button ['class']) {
						  
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
						  $data ['detail'] = $this->javaClassToTemplate ($button ['class']);
						  $data ['button'] = ['class' => 'CashTender',
													 'text' => '',
													 'color' => $defaultColor];
						  break;
						  
					 case 'ItemMarkdownAmount':
					 case 'ItemMarkdownPercent':
						  
						  $data ['detail'] = $this->javaClassToTemplate ($button ['class']);
						  $data ['button'] = ['class' => $button ['class'],
													 'text' => $button ['button_text'],
													 'color' => $defaultColor];
						  break;
						  
					 default:
						  
						  $data ['detail'] = 'no_params';
						  $data ['button'] = ['class' => $button ['class'],
													 'text' => $button ['button_text'],
													 'color' => $defaultColor];
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
				
				include ROOT . DS . 'src' . DS  . 'Controller' . DS . 'colors_oasis.php';
				$template = '';
				$html = '';
				$button = $this->request->getData ();
				
				$data = ['configID' => $button ['config_id'],
							'menuName' => $button ['menu_name'],
							'menuIndex' => $button ['menu_index'],
							'pos' => $button ['pos'],
							'colors' => $colors];
				
				switch ($button ['item_opts']) {

					 case 'existing':

						  $template = 'item_search';
						  $this->set ($data);
						  break;
						  
					 default:
						  
						  $data = array_merge ($data, ['button' => ['class' => 'Null', 'text' => '', 'color' => $defaultColor],
																 'colors' => $colors,
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
				
				$this->ajax (['status' => 0,
								  'html' => $html]);
		  }
		  else {
				$this->ajax (['status' => 1]);
		  }
	 }

	 function itemAdd () {

		  $button = $this->request->getData ();

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
}

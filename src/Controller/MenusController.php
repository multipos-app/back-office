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
    
    public function index (...$args) {

        $posConfigTable = TableRegistry::get ('PosConfigs');
        $posConfig = $posConfigTable
                   ->find ()
                   ->where (['id' => $args [0]])
                   ->first ();
        
        $posConfig = $posConfig->toArray ();
        $posConfig ['config'] = json_decode ($posConfig ['config'], true);
		  
        $data = ['posConfig' => $posConfig];
		  
        return ($this->response (__ ('Menus'),
                                 'Menus',
                                 'index',
                                 compact ('posConfig')));
    }
	 
    public function all ($configID) {

        $posConfigTable = TableRegistry::get ('PosConfigs');
        $posConfig = $posConfigTable
                   ->find ()
                   ->where (['id' => $configID])
                   ->first ();
        
        $posConfig = $posConfig->toArray ();
        $posConfig ['config'] = json_decode ($posConfig ['config'], true);
		  
        $data = ['posConfig' => $posConfig];
		  
        return ($this->response (__ ('Menus'),
                                 'Menus',
                                 'all',
                                 compact ('posConfig')));
    }

    function button () {

		  if (!empty ($this->request->getData ())) {

				$template = 'empty';
				$data = [];
				$extras = [];
				$button = $this->request->getData ();
				$new = isset ($button ['new']);

				$this->debug ('menus button... ');
				$this->debug ($button);

				switch ($button ['class']) {
						  
					 case 'Item':
					 case 'DefaultItem':

						  if (isset ($button ['exists']) && $button ['exists']) {

								$template = 'item_edit';
						  }
						  else {
								
								$template = 'item';
								
								$button ["text"] = "";
								$button ["class"] = $button ['class'];
								$button ["color"] =  "#999999";
								$button ["params"] = ["sku" =>  ""];
								
								$extras ['pricingOptions'] = [null => __ ('Add Item'),
																		'existing' => 'Existing item',
																		'standard_pricing' => 'Standard pricing, one price per item',
													 					'variant_pricing' => 'Variant pricing (size, color...)',
																		'open_pricing' => 'Open/enter price, prompt for price',
																		'metric_pricing' => 'Price by volume/weight, prompt for value'];
						  }	
						  break;

					 case 'CashTender':

						  $template = 'cash_tender';
						  break;

					 case 'Navigate':

						  $template = 'navigate';

						  if ($new) {
								
								$button ["text"] = "";
								$button ["class"] = "Navigate";
								$button ["color"] =  "#999999";
						  }
						  break;

					 case 'ItemMarkdown':

						  $template = 'item_markdown';

						  if ($new) {
								
								$button ["text"] = "";
								$button ["class"] = "ItemMarkdown";
								$button ["color"] =  "#999999";
								$button ["params"] = ["receipt_text" => ""];
						  }
						  break;
						  
					 case 'SaleDiscount':

						  $template = 'sale_discount';
						  $button ["class"] = "SaleDiscount";
						  
						  if ($new) {
								
								$button ["color"] =  "#999999";
								$button ["params"] = ["receipt_text" => "",
															 "percent" =>  "0"];
						  }
						  break;
						  
					 case 'Null':
						  
						  $query = TableRegistry::get ('PosControlCategories')
														->find ('all', ['contain' => ['PosControls']])
														->where (['enabled' => 1]);

						  $controls = [];
						  foreach ($query as $controlCategories) {
								
								$controls ['separator-' . $controlCategories ['id']] =
									 ['desc' => $controlCategories ['name']];
								
								foreach ($controlCategories ['pos_controls'] as $control) {
									 
									 $controls [$control ['class']] = ['text' => $control ['description'],
																				  'class' => $control ['class'],
																				  'params' => json_decode ($control ['params'], true),
																				  'help' => $control ['help_text']];
								}
						  }
						  
						  $button ["text"] = "";
						  $button ["color"] = "#999999";
						  $template = 'empty';
						  $button ['controls'] = $controls;
						  break;

					 default:
						  
						  $template = 'no_params';
						  break;
				}
				
				$data = ['status' => 0,
							'button' => $button];

				if (count ($extras)) {

					 foreach ($extras as $key => $value) {

						  $data [$key] = $value;
					 }
				}

				/* $this->debug ("menus button... $template");
					$this->debug ($data);*/
				
				return ($this->response (__ ('Menus'),
												 'Menus',
												 $template,
												 $data));
		  }
		  else {
				
				return ($this->response (__ ('Menus'),
												 'Menus',
												 'empty',
												 ['status' => 1]));
		  }

	 }

	 function update ($id) {

		  $response = ['status' => 1];
		  
		  if (($id > 0) && !empty ($this->request->getData ())) {
				
				$config = json_encode ($this->request->getData () ['config']);

				$posConfigTable = TableRegistry::get ('PosConfigs');
				$posConfig = $posConfigTable
					->find ()
					->where (['id' => $id])
					->first ();
				
				$posConfig ['config'] = $config;
				$this->save ('PosConfigs', $posConfig);
				$response ['status'] = 0;
				$this->notifyPos ();
		  }
		  
		  $this->set ('response', $response);
		  $this->viewBuilder ()->setLayout ('ajax');
		  $this->RequestHandler->respondAs ('json');
	 }
}

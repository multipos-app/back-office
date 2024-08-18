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

    function button () {

        $button = $this->request->getData ();
		  
		  $template = 'empty';
		  $data = [];

        switch ($button ['class']) {

				case 'Item':
				case 'OpenItem':
					 
					 $template = 'item';
					 
					 if (!isset ($button ['text'])) {

						  $button ["text"] = "";
						  $button ["class"] = $button ['class'];
						  $button ["color"] =  "#999";
						  $button ["params"] = ["sku" =>  ""];
					 }
					 
					 break;

				case 'CashTender':

					 $template = 'cash_tender';
					 break;

				case 'Navigate':

					 $template = 'navigate';
					 $button ["text"] = "";
					 $button ["class"] = "Navigate";
					 $button ["color"] =  "#999";
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
								
								$controls [$control ['class']] = ['desc' => $control ['description'],
																			 'class' => $control ['class'],
																			 'params' => json_decode ($control ['params'], true),
																			 'help' => $control ['help_text']];
						  }
					 }
					 
					 $button ["text"] = "";
					 $button ["color"] =  "#999";
					 $template = 'empty';
					 $button ['controls'] = $controls;
					 break;

				default:
					 
					 $template = 'no_params';
					 $button ["text"] = "";
					 $button ["class"] = $button ['class'];
					 $button ["color"] =  "#999";
					 break;
        }
		  
		  $data ['button'] = $button;

        return ($this->response (__ ('Menus'),
                                 'Menus',
                                 $template,
                                 $data));

    }

	 function update ($id) {

		  $response = ['status' => 1];
		  
		  if (($id > 0) && !empty ($this->request->getData ())) {

				$this->debug ($this->request->getData ());
				
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

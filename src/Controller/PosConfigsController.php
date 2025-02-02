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

class PosConfigsController extends PosAppController {

    private $configs;
    private $templates;
	 
    public function initialize (): void {
        
        parent::initialize ();
    }
	 
	 /**
	  *
	  *
	  */

	 public function index (...$params) {
        
        $this->initConfigs ();
        $configs = $this->configs;
        $templates = $this->templates;
		  
        $locations = [null => __ ('Select location')];
		  
		  foreach ($this->merchant ['business_units'] as $bu) {

				if ($bu ['business_type'] == BU_LOCATION) {

					 $locations [$bu ['id']] = $bu ['business_name'];
				}
		  }

		  
		  $templates = [null => __ ('Add a POS configuration')];
		  $tmp = $this->templates ();
		  
		  $configsDir = ROOT . DS . 'src' . DS  . 'Controller/configs';
		  $configTemplates = scandir ($configsDir);

		  foreach ($configTemplates as $template) {

				if (strpos ($template, ".json")) {

					 $config = file_get_contents ($configsDir . DS . $template);
					 $config = json_decode ($config, true);
					 $templates [$config ['id']] = $config ['desc'];
				}
		  }
		  
        return ($this->response (__ ('POS Cofigurations'),
                                 'PosConfigs',
                                 'index',
                                 compact ('configs',
														'locations',
														'templates')));
    }

	 /**
	  *
	  *
	  */

    private function initConfigs () {
        
        $posConfigsTable = TableRegistry::get ('PosConfigs');
        
        $this->configs = [];
        $query = TableRegistry::get ('PosConfigs')->find ('all');
        foreach ($query as $config) {

				$config ['config'] = json_decode ($config ['config'], true);
				unset ($config ['pos_config']);
				$this->configs [] = $config;
        }
		  
        $this->templates = [];
        $query = TableRegistry::get ('PosConfigTemplates')->find ('all');
        
        foreach ($query as $configTemplate) {
            
            $this->templates [$configTemplate ['id']] = $configTemplate ['config_desc'];
        }
    }
	 
 	 /**
	  *
	  *
	  */

    public function selectMenus ($configID) {
        
        $posConfig = TableRegistry::get ('PosConfigs')
											 ->find ()
											 ->where (['id' => $configID])
											 ->first ();

        $posConfig ['config'] = json_decode ($posConfig ['config'], true);
        $this->set ('posConfig', $posConfig);
    }
    
	 /**
	  *
	  *
	  */

    public function addConfig ($configID, $configName) {

        $this->debug ("add config... $configID, $configName");

		  $posConfigsTable = TableRegistry::get ('PosConfigs');

		  $configFile = ROOT . DS . 'src' . DS  . 'Controller/configs/' . $configID . '.json';
		  $config = file_get_contents ($configFile);
		  $config = json_decode ($config, true);
		  
		  if ($config) {
						  		
				$posConfig = ['config_desc' => strtoupper ($configName),
								  'config' => json_encode ($config),
								  'config_type' => 0];
				
				$posConfig = $posConfigsTable->newEntity ($posConfig);
				$posConfigsTable->save ($posConfig);
				$this->set ('response',  ['status' => 0]);
		  }
		  
        return $this->index ();
    }
    	 
	 /**
	  *
	  *
	  */

    public function settings ($id) {

		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'devices.php';
		  		  
        $posConfigTable = TableRegistry::get ('PosConfigs');
        $posConfig = $posConfigTable
                   ->find ()
                   ->where (['id' => $id])
                   ->first ();
        
        if ($posConfig) {

            $config = json_decode ($posConfig ['config'], true);
            
            $query = TableRegistry::get ('PosOptions')
											 ->find ('all', ['fields' => ['key', 'type', 'default', 'name', 'description']]);

				$settings = ['options' => [],
								 'devices' => []];
				
            foreach ($query as $option) {

                $option ['on'] = isset ($config [$option ['key']]) && $config [$option ['key']];
                $settings ['options'] [] = $option->toArray ();
            }

				foreach ($devices as $name => $device) {
					 
					 $settings ['devices'] [$name] = ['name' => $name,
																 'desc' => $device ['desc'],
																 'selected' => null,
																 'options' => [null => '']];
					 
					 foreach ($device ['options'] as $opt => $val) {
						  
						  $settings ['devices'] [$name] ['options'] [$opt] = $val ['desc'];
					 }
				}
				
				foreach ($config ['devices'] as $name => $device) {
					 
					 $settings ['devices'] [$name] ['selected'] = $device ['name'];
				}
				
	         $posConfigID = $id;

				$this->debug ($settings);
				
            return ($this->response (__ ('POS Settings'),
                                     'PosConfigs',
                                     'settings',
                                     compact ('posConfigID',
															 'settings')));
        }
    }

	 /**
	  *
	  *
	  */

    public function updateSettings () {
        
        if (!empty ($this->request->getData ())) {

				require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'devices.php';
				
            $this->debug ('.....................................................................');
				$this->debug ($this->request->getData ());
				$this->debug ('.....................................................................');
								
            $options = $this->request->getData () ['options'];
				
            $posConfigTable = TableRegistry::get ('PosConfigs');
            $posConfig = $posConfigTable
                       ->find ()
                       ->where (['id' => $this->request->getData () ['pos_config_id']])
                       ->first ();
            
            $config = json_decode ($posConfig ['config'], true);
				$config ['devices'] = [];

				// handle built in devices
				
				$this->debug ($this->request->getData () ['devices'] ['pos']);
				
				if (isset ($this->request->getData () ['devices'] ['pos']) && (strlen ($this->request->getData () ['devices'] ['pos']) > 0)) {

					 
					 $this->debug ('pos...');
					 $this->debug ($devices ['pos'] ['options'] [$this->request->getData () ['devices'] ['pos']] ['devices']);
					 
					 $config ['devices'] = $devices ['pos'] ['options'] [$this->request->getData () ['devices'] ['pos']] ['devices'];
				}
				
				foreach ($this->request->getData () ['devices'] as $key => $val) {
					 
					 /* if ($val && !isset... (devices...)) {
					  */
					 $this->debug ('dev... ' . $key . ' '. $val);
					 
					 /* $this->debug ($devices [$key] ['options'] [$val]);*/
					 
					 /* $config ['devices'] [$key] = $devices [$key] ['options'] [$val];*/
				}

					 /* $config ['devices'] [$key] = ['name' => $devices [$key] ['name'],
						 'class' => $devices [$key] ['class'],
						 'params' => $devices [$key] ['params']];
					  */
				
				$this->debug ($config ['devices']);

				$query = TableRegistry::get ('PosOptions')
											 ->find ('all', ['fields' => ['key', 'type', 'default', 'name', 'description']]);
				
				foreach ($query as $option) {
					
					 $val = false;
					 if (isset ($options [$option ['key']])) {
						  
						  $val = true;
					 }
					 
					 $config [$option ['key']] = $val;
				}
				
				$posConfig ['config'] = json_encode ($config);
				$this->save ('PosConfigs', $posConfig);
				$this->notifyPOS ();
				
				$this->viewBuilder ()->setLayout ('ajax');
				$this->set ('response', ['status' => 0]);
		  }
    }
    
 	 /**
	  *
	  *
	  */

    public function customerDisplay ($buID) {
        
        $cd = [];
        $sections = ['left'];
        
        $businessUnitsTable = TableRegistry::get ('BusinessUnits');
        $bu = $businessUnitsTable
            ->find ()
            ->where (['id' => $this->buMap [$this->buID] ['id']])
            ->first ();

        if ($bu) {

            $params = json_decode ($bu ['params'], true);

            foreach ($sections as $section) {

                if (isset ($params ['cd_' . $section])) {
                    
                    $cd [$section] = $params ['cd_' . $section];
                }
                else {
                    $cd [$section] = [];
                }
            }
        }
        else {
            
            // wha???

            $this->redirect (['action' => 'index']);
            return;
        }
        
        if (!empty ($this->request->getData ())) {

            $this->debug ($this->request->getData ());
            
            foreach ($sections as $section) {

                if (isset ($this->request->getData () [$section])) {
                    
                    $this->buMap [$this->buID] ['params'] ["cd_" . $section] = $this->request->getData () [$section];
                    $cd [$section] = $this->request->getData () [$section];
                }
            }
				
            $bu ['params'] = json_encode ($this->buMap [$this->buID] ['params']);
            $this->save ('BusinessUnits', $bu);
            $this->redirect (['action' => 'index']);
        }
        
        $this->set ("cd", $cd);

    }
    
 	 /**
	  *
	  *
	  */

    public function receipt ($buID) {

		  $this->debug ("receipts... $buID");
		  $bu = null;
		  foreach ($this->merchant ['business_units'] as $bu) {

				if ($bu ['id'] == $buID) {

					 break;
				}
		  }
		  
 		  $this->debug ($bu);
        $sections = ['receipt_header' => [], 'receipt_footer' => []];
		  
		  if ($bu ['params'] != null) {

		  		if (isset ($bu ['params'] ['receipt_header'])) {

					 $sections ['receipt_header'] = $bu ['params'] ['receipt_header'];
				}
				
		  		if (isset ($bu ['params'] ['receipt_footer'])) {

					 $sections ['receipt_footer'] = $bu ['params'] ['receipt_footer'];
				}
		  }

		  
        return $this->response (__ ('POS Receipt'),
                                'PosConfigs',
                                'receipt',
                                ['buID' => $buID,
											'qrcode' => '',
											'receipt' => $sescions],
                                false);
    }
    
	 /**
	  *
	  *
	  */

    public function saveTemplate ($id) {

    }
    
    
 	 /**
	  *
	  *
	  */

    public function settingsRaw ($id) {

        $editMenus = [];
		  
        $config = null;
        $query = TableRegistry::get ('PosConfigs')
										-> find ()
										-> where (['id' => $id]);
        
        if ($config = $query->first ()) {
            $this->set ('posConfig', $config);
        }
    }

	 /**
	  *
	  *
	  */

    function getParam ($name, $config) {

        if (isset ($config [$name]) && $config [$name]) {
            return true;
        }    
        return false;
    }
	 
 	 /**
	  *
	  *
	  */

    public function upload ($id) {

        $null = '';
		  if (!empty ($this->request->getData ())) {
				
				$handle = fopen ($_FILES ['upload_file'] ['tmp_name'], "r");
				
				if ($handle === false) {

				}

				$json = '';
				
				while (!feof ($handle)) {

					 $json .= fgets ($handle);
				}
				
				fclose ($handle);
				
				if (strlen ($json) > 0) {
					 
					 $posConfigTable = TableRegistry::get ('PosConfigs');
					 $configID = intVal ($id);
					 
					 $posConfig = $posConfigTable
                    ->find ()
                    ->where (['id' => $configID])
                    ->first ();
					 
					 $json = preg_replace ([ '/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'], [' ', ''], $json);

					 if ($posConfig) {

						  $posConfig ['config'] = $json;
						  $this->save ('PosConfigs', $posConfig);
						  $this->notifyPOS ();
					 }
				}

				return $this->index ();
		  }
		  
		  $configID = $id;
		  return ($this->response (__ ('POS Cofiguration upload'),
											'PosConfigs',
											'upload',
											compact ('configID')));
    }
	 
	 /**
	  *
	  *
	  */

    public function download ($id) {
        
        $exportDir = $this->request->getAttribute ('webroot') . 'exports';

        $query = TableRegistry::get ('PosConfigs')
										->find ()
										->where (['id' => $id]);

        $fname = 'pos-config-' . $id . '.json';
		  $download = getcwd () . "/exports/$fname";

		  $this->debug ('download... ' . $fname . ' ' . $download);
		  
        if ($config = $query->first ()) {
				
            $json = json_encode (json_decode ($config ['config'], true), JSON_PRETTY_PRINT);    
            file_put_contents ($download, $json);
        }

		  return $this->response->withFile ($download, ['download' => true,
																		'name' => $fname]);
    }
	 
 	 /**
	  *
	  *
	  */

    public function clone ($id) {

		  return $this->index ();
	 }
	 
 	 /**
	  *
	  *
	  */

    public function deleteConfig ($id) {

		  $this->debug ("delete config... $id");
		  
		  TableRegistry::get ('PosConfigs')->deleteAll (['id' => $id]);
	  
		  return $this->index ();
	 }
	 
	 private function templates () {

		  $jsonConfigs = file_get_contents (ROOT . DS . 'src' . DS  . 'Controller' . DS . 'configs.json');
		  if ($jsonConfigs) {

				$tmp = json_decode ($jsonConfigs, true);
				return $tmp;
		  }
		  
		  return [];
	 }
}

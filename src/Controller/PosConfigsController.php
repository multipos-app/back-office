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
		  
		  $this->set (['configs' => $configs,
							'locations' => $locations]);
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

	 public function settings ($id) {

		  require ROOT . DS . 'src' . DS  . 'Controller' . DS . 'devices.php';
		  
		  $posConfigsTable = TableRegistry::get ('PosConfigs');

		  if (!empty ($this->request->getData ())) {
				
				$this->update ($id, $this->request->getData (), $posConfigsTable);
				$this->ajax (['status' => 0]);
				return;
		  }
		  else {
				
				$posConfig = $posConfigsTable->find ()
													  ->where (['id' => intval ($id)])
													  ->first ();
				
				// decode the current config
				
				$config = json_decode ($posConfig ['config'], true);
							
				$query = TableRegistry::get ('PosOptions')
											 ->find ('all', ['fields' => ['key', 'type', 'default', 'name', 'description']]);

				$settings = ['posConfigID' => $id,
								 'devices' => $devices,
								 'options' => []];
				
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
				
				$this->set (['posConfigID' => $id,
								 'settings' => $settings]);
				
 				$builder = $this->viewBuilder ()
									 ->setLayout ('ajax')
									 ->disableAutoLayout ()
									 ->setTemplatePath ('PosConfigs')
									 ->setTemplate ('settings');
				
				$view = $builder->build ();
				$html = $view->render ();
				
				$this->ajax (['status' => 0,
								  'html' => $html]);
				
		  }
	 }
	 
	 /**
	  *
	  * upload a json configuration file and apply it to an existing configuration
	  *
	  */

	 public function upload ($id) {
		  
		  if (!empty ($this->request->getData ())) {
				
				$handle = fopen ($_FILES ['upload_file'] ['tmp_name'], "r");
				
				if ($handle) {

					 $json = '';
					 
					 while (!feof ($handle)) {

						  $json .= fgets ($handle);
					 }
					 
					 fclose ($handle);

					 
					 if (strlen ($json) > 0) {
						  
						  $posConfigsTable = TableRegistry::get ('PosConfigs');
						  
						  $posConfig = $posConfigsTable->find ()
																 ->where (['id' => $id])
																 ->first ();
						  $decode = json_decode ($json, true);

						  if (!$decode) {
								
								return $this->ajax (['status' => 1,
															'status_text' => 'invalid json file ' . $_FILES ['upload_file'] ['full_path']]);
						  }
						  
						  if ($posConfig) {
								
								$posConfig ['config'] = preg_replace (['/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'], [' ', ''], $json);
								$this->save ('PosConfigs', $posConfig);
						  }
					 }
				}
		  }
		  		  
		  $this->set (['posConfigID' => $id]);
		  
 		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('PosConfigs')
								->setTemplate ('upload');
		  
		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }

	 /**
	  *
	  * create a copy of a configuration
	  *
	  */

	 public function clone ($id) {
		  
		  if (!empty ($this->request->getData ())) {
				
				$posConfigsTable = TableRegistry::get ('PosConfigs');

				$config = $posConfigsTable->find ()
												  ->where (['id' => $id])
												  ->first ();

				unset ($config ['id']);
				
				$config = $posConfigsTable->newEntity ($config->toArray ());
				$config ['config_desc'] = strtoupper ($this->request->getData () ['config_desc']);
				$config ['create_time'] = time ();
				$posConfigsTable->save ($config);
		  }

		  $this->set (['posConfigID' => $id]);
		  
 		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('PosConfigs')
								->setTemplate ('clone');
		  
		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }

	 /**
	  *
	  * settings update
	  *
	  */

	 private function update ($id, $settings, $posConfigsTable) {
		  		  
		  require ROOT . DS . 'src' . DS  . 'Controller' . DS . 'devices.php';

		  $options = $settings ['options'];
		  $posConfigsTable = TableRegistry::get ('PosConfigs');
		  $posConfig = $posConfigsTable
                       ->find ()
                       ->where (['id' => $settings ['pos_config_id']])
                       ->first ();
		  
		  $config = json_decode ($posConfig ['config'], true);
		  $config ['devices'] = [];

		  // handle devices

		  foreach ($devices as $deviceType => $device) {

				if (strlen ($settings ['devices'] [$deviceType]) > 0) {

					 $config ['devices'] [$deviceType] = $device ['options'] [$settings ['devices'] [$deviceType]];
				}
					 
		  }
		  		  
		  foreach ($settings ['options'] as $key => $val) {
				
				$config [$key] = $val == 'on' ? true : false;
		  }
		  
		  $posConfig ['config'] = json_encode ($config);
		  $this->save ('PosConfigs', $posConfig);
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

	 public function download ($id) {
		  
		  $exportDir = $this->request->getAttribute ('webroot') . 'exports';

		  $query = TableRegistry::get ('PosConfigs')
										->find ()
										->where (['id' => $id]);

		  $fname = 'pos-config-' . $id . '.json';
		  $download = getcwd () . "/exports/$fname";
		  
		  if ($config = $query->first ()) {
				
				$json = json_encode (json_decode ($config ['config'], true), JSON_PRETTY_PRINT);    
				file_put_contents ($download, $json);
		  }

		  return $this->response->withFile ($download, ['download' => true,
																		'name' => $fname]);
	 }

	 public function templates () {

		  if (!empty ($this->request->getData ())) {
				
				$file = ROOT . DS . 'src' . DS  . 'Controller/configs/' . $this->request->getData () ['json_file'];
				$desc = $this->request->getData () ['desc'];
				$config = file_get_contents ($file);
				
				$posConfigsTable = TableRegistry::get ('PosConfigs');
				$posConfig = $posConfigsTable->newEntity (['config_desc' => strtoupper ($desc),
																		 'config' => $config,
																		 'config_type' => 1]);
				$posConfigsTable->save ($posConfig);
				
				return $this->ajax (['status' => 0]);
		  }
		  
		  $templates = [];
		  
		  $configsDir = ROOT . DS . 'src' . DS  . 'Controller/configs';
		  $configTemplates = scandir ($configsDir);

		  foreach ($configTemplates as $template) {

				if (str_ends_with ($template, ".json")) {

					 $config = file_get_contents ($configsDir . DS . $template);
					 $config = json_decode ($config, true);
					 $templates [] = ['json_file' => $template,
											'desc' => $config ['desc'],
											'layout' => $config ['root_layout']];
				}
		  }

		  $this->set (['templates' => $templates]);
		  
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('PosConfigs')
								->setTemplate ('templates');
		  
		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }

	 /**
	  *
	  *
	  */

	 public function deleteConfig ($id) {
		  
		  TableRegistry::get ('PosConfigs')->deleteAll (['id' => $id]);		  
		  return $this->ajax (['status' => 0]);
	 }
}

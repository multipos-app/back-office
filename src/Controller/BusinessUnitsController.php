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
use App\Model\Entity\BusinessUnit;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class BusinessUnitsController extends PosAppController {

    public function initialize (): void {
		  
        parent::initialize ();

    }

    public function index (...$params) {
		  
        $bus = TableRegistry::get ('BusinessUnits')
									 ->find ()
									 ->all ();

		  $businessUnits = [];
		  
		  foreach ($bus as $bu) {
				
				$bu ['phone_1'] = $this->phoneFormat ($bu ['phone_1']);

				$buType = '';
				switch ($bu ['business_type']) {

					 case BU_CORP:
						  $bu ['business_type'] = __ ('Primary business');
						  break;

					 case BU_LOCATION:
						  $bu ['business_type'] = __ ('Location');
						  break;
				}
		  }
		  
		  $this->set (['businessUnits' => $bus]);
    }

	 /**
	  *
	  * edit or update
	  *
	  **/
	 
    public function edit ($id) {
		  
		  if (!$this->session) {

				return $this->redirect ('/');
		  }

        require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'states.php';

        $businessUnitsTable = TableRegistry::get ('BusinessUnits');
        
        if (!empty ($this->request->getData ())) {

				// update and return to bu index

				$this->update ($id, $this->request->getData (), $businessUnitsTable);
 				return $this->redirect ('/business-units');
       }

		  // lookup or create a new business unit
		  
		  $bu = null;
		  if ($id == 0) {
				
				$bu = ['id' => 0,
						 'business_type' => BU_LOCATION,
						 'business_name' => '',
						 'email' => '',
						 'addr_1' => '',
						 'addr_2' => '',
						 'city' => '',
						 'state' => '',
						 'postal_code' => '',
						 'phone_1' => ''];
		  }
		  else {
				
				$bu = $businessUnitsTable
										 ->find ()
										 ->where (['id' => $id])
										 ->first ();
		  }
		  
        $data = ['bu' => $bu,
					  'states' => $states];
		  
		  $this->set ($data);
		  
 		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('BusinessUnits')
								->setTemplate ('edit');

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }
	 
	 /**
	  *
	  * update or create
	  *
	  **/

	 private function update ($id, $bu, $businessUnitsTable) {
		  
		  if ($id == 0) {
				
				foreach (['business_name', 'addr_1', 'addr_2', 'city'] as $field) {
					 
					 $bu [$field] = strtoupper ($bu [$field]);
				}
				
				$bu ['phone_1'] = preg_replace ('/\(|\)|\s+|\-/', '', $bu ['phone_1']);
				
				$bu = $businessUnitsTable->newEntity ($bu);
				$businessUnitsTable->save ($bu);
		  }
		  else {
				
				$businessUnitsTable->updateAll (['business_name' => strtoupper ($this->request->getData () ['business_name']),
															'email' => $this->request->getData () ['email'],
															'addr_1' => strtoupper ($this->request->getData () ['addr_1']),
															'addr_2' => strtoupper ($this->request->getData () ['addr_2']),
															'city' => strtoupper ($this->request->getData () ['city']),
															'state' => $this->request->getData () ['state'],
															'postal_code' => $this->request->getData () ['postal_code'],
															'phone_1' => preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone_1'])],
														  ['id' => $id]);
		  }
	 }

	 /**
	  *
	  * send a message to a POS
	  *
	  **/

	 public function pos () {
        
        $pu = TableRegistry::get ('PosUnits');
        $employees = TableRegistry::get ('Employees');
        
        // $posMessages = TableRegistry::get ('PosMessages');
        // $batchEntryTable = TableRegistry::get ('BatchEntries');

        if (!empty ($this->request->getData ())) {
            
            $pos = [];
            $method = false;
            
            foreach ($this->request->getData () as $key => $value) {

                if (strpos ($key, 'pos') === 0) {
                    
                    $pos [] = intVal (substr ($key, 4));
                }
                
            }
            
            $method = $this->request->getData () ['method'];
				
            $tokens = [];

            foreach ($pos as $p) {
                
                $posUnit = $pu 
                         ->find ()
                         ->where (['id' => $p])
                         ->first ();
                
                $token = $posUnit ['token'];
                $message = '[]';
                
                switch ($method) {
								
						  case 'z':
								
								$message = ['method' =>'control',
												'control' => ['class' => 'ZSession',
																  'params' => ['confirmed' => '1']]];
								break;
								
						  case 'log_out':
								
								$message = ['method' =>'control',
												'control' => ['class' => 'LogOut',
																  'params' => ['confirmed' => '1']]];
								break;
								
						  case 'exit':
								
								$message = ['method' =>'exit'];
								break;
								
						  case 'send_log':
								
								$message = ['method' =>'send_log'];
								break;
								
						  case 'message':
								
								$message = ['method' =>'message',
												'message' => ['header' => __ ('Incoming Message'),
																  'prority' => 0,
																  'body' => $this->request->getData () ['message_text']]];
								// 'body' => $this->timestamp (time ()) . "\n\n" . $this->request->getData () ['message_text']]];
								break;
                }
                
                $this->queueMessage ($posUnit, ['business_unit_id' => $posUnit ['business_unit_id'],
                                                'pos_unit_id' => $posUnit ['id'],
                                                'message' => json_encode ($message)]);
            }
        }
        
        $p = [];
        $posUnits = $pu
                  ->find ('all')
        // ->where (["login_time != '0000-00-00 00:00:00'"])
                  ->order (['id desc'])
                  ->limit (10);
        
        foreach ($posUnits as $posUnit) {
				
            $employee = '';
            if ($posUnit ['employee_id'] > 0) {
                
                $e = $employees
                   ->find ()
                   ->where (['id' => $posUnit ['employee_id']])
                   ->first ();
                
                $employee = $e ['fname'];
            }
            
            $status = __ ('Unknown');
            switch ($posUnit ['status']) {
						  
					 case 0:
						  
						  $status = __ ('Log Out');
						  break;
						  
					 case 1:
						  
						  $status = __ ('Log In');
						  break;
            }
            
            $posUnit ['create_time'] = $this->timestamp ($posUnit ['create_time']);
            $posUnit ['restart_time'] = $this->timestamp ($posUnit ['restart_time']);
            $posUnit ['login_time'] = $this->timestamp ($posUnit ['login_time']);
            $posUnit ['employee'] = $employee;
            $posUnit ['status'] = $status;
            
            $p [] = $posUnit;
        }
        
        $posUnits = $p;
        $actions = [null => __ ('Actions'),
                    'log_out' => __ ('Log out'),
                    'z' => __ ('Z report'),
                    'exit' => __ ('Stop POS'),
                    'send_log' => __ ('Send POS logs'),
                    'message' => __ ('Send message')];
        

        return ($this->response (__ ('POS'),
                                 'BusinessUnits',
                                 'pos',
                                 compact ('posUnits', 'actions')));
    }

    public function posMessage () {

        $p = [];
        $pos = TableRegistry::get ('PosUnits');
        $posUnits = $pos
                  ->find ('all')
                  ->where (["model is not null"])
                  ->order (['id desc'])
                  ->limit (10);

        foreach ($posUnits as $posUnit) {
            
            $p [$posUnit ['id']] = $posUnit ['pos_no'] . ' ' . $posUnit ['model'];
        }
        
        $this->set ('posUnits', $p);
        
        if (!empty ($this->request->getData ())) {

            $posUnit = $pos
                     ->find ()
                     ->where (['id' => $this->request->getData () ['pos_id']])
                     ->first ();

            if ($posUnit) {
                
                $this->queueMessage ($posUnit, ['business_unit_id' => $posUnit ['business_unit_id'],
                                                'pos_unit_id' => $posUnit ['id'],
                                                'message' => $this->request->getData () ['message_body']]);
            }
        }
    }

    private function queueMessage ($posUnit, $posMessage) {

        $posMessages = TableRegistry::get ('PosMessages');
        $batchEntryTable = TableRegistry::get ('BatchEntries');
		  
        $posMessage = $posMessages->newEntity ($posMessage);
        $posMessage = $posMessages->save ($posMessage);
        
        $batchEntry = $batchEntryTable->newEntity (['business_unit_id' => $posUnit ['business_unit_id'],
																	 'pos_unit_id' => $posUnit ['id'],
																	 'update_table' => 'pos_messages',
																	 'update_id' =>  $posMessage ['id'],
																	 'update_action' => 0,
																	 'execution_time' => time ()]);
        $batchEntryTable->save ($batchEntry);
    }

	 public function batches () {
        
        $paginate = ['Batches' => ['limit' => 20,
                                   'Batches.id asc']];
        
        $batches = TableRegistry::get ('Batches');
        $query = $batches
               ->find ('all')
               ->where (['status' => 0])
               ->order (['id desc']);

        $batches = $this->paginate ($query);
        foreach ($batches as $batch) {

            // $batch ['submit_date'] = $this->utcToLocal ($batch ['submit_date'], $this->tz (), 'm/d/y h:i a');
        }
        
        $batchTypes = [null => __ ('Batch type'),
                       0 => __ ('Auto, empty batch'),
                       1 => __ ('Full, all store data'),
                       2 => __ ('Employees'),
                       3 => __ ('Menus and configuration'),
                       5 => __ ('Customers'),
                       6 => __ ('Images'),
                       7 => __ ('Items')];

		  $this->set (['batches' => $batches,
							'batchTypes' => $batchTypes]);
    }
    
    public function createBatch () {
        
        if (!empty ($this->request->getData ())) {

				$this->loadComponent ('Batch');
            
            $batchID = $this->Batch->createBatch ($this->request->getData () ['batch_type'],
																  $this->merchant ['bu_id'],
																  '',
																  date ('Y-m-d H:i:s', time ()));
				$this->notifyPOS ();
        }

		  return $this->redirect ('/business-units/batches');
    }

    
    function cancelBatches () {

        $status = -1;

        if (!empty ($this->request->getData ())) {

				$this->debug ('cancel batch... ');
				$this->debug ($this->request->getData ());
				
				
            $batchEntriesTable = TableRegistry::get ('BatchEntries');
            $batchesTable = TableRegistry::get ('Batches');
				
				foreach ($this->request->getData () as $batch => $value) {
                
                $batchID = intVal ($batch);
                $batchEntriesTable->deleteAll (['batch_id' => $batchID]);
					 $batchesTable->deleteAll (['id' => $batchID]);
            }
        }
		  
     	  return $this->redirect ('/business-units/batches');   
    }

    public function serverLog () {
        
        $batchEntry = ['business_unit_id' => $this->merchant ['bu_id'],
                       'update_table' => 'business_units',
                       'update_id' => 0,
                       'update_action' => 100,
                       'execution_time' => time ()];      
        
        $batchEntriesTable = TableRegistry::get ('BatchEntries');
        $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
        $batchEntriesTable->save ($batchEntry);        
    }

    public function apps () {
        
        $appsDir = '/multipos/www/d/webroot/apps';

        foreach (scandir ($appsDir, SCANDIR_SORT_DESCENDING) as $file) {
            
            $fname = getcwd () . '/apps/' . $file;
        }

        $apps = '';
        return ($this->response (__ ('POS'),
                                 'BusinessUnits',
                                 'apps',
                                 compact ('apps')));

    }

	 public function receipts () {

		  $locations = [];
		  
		  foreach ($this->merchant ['business_units'] as $bu) {
				
				if ($bu ['business_type'] == BU_LOCATION) {

					 $locations [] = $bu;
				}
		  }

		  $this->set (['locations' => $locations]);
	 }

	 public function receipt ($buID) {

		  $bu = null;
		  $buIndex = 0;
		  
		  foreach ($this->merchant ['business_units'] as $bu) {

				if ($bu ['id'] == $buID) {

					 break;
				}
				$buIndex ++;
		  }
		  
		  $receipt = ['receipt_header' => [["text" => "", "justify" => "center","font" => "bold","size" => "big","feed" => "0"]],
						  'receipt_footer' => [["text" => "", "justify" => "center","font" => "bold","size" => "big","feed" => "0"]]];
		  
		  if ($bu ['params'] != null) {

		  		if (isset ($bu ['params'] ['receipt_header'])) {

					 $receipt ['receipt_header'] = $bu ['params'] ['receipt_header'];
				}
				
		  		if (isset ($bu ['params'] ['receipt_footer'])) {

					 $receipt ['receipt_footer'] = $bu ['params'] ['receipt_footer'];
				}
		  }
		  
        $this->set (['buID' => $buID,
							'buIndex' => $buIndex,
							'qrcode' => '',
							'receipt' => $receipt]);
	 }
	 
 	 /**
	  *
	  *
	  */

    public function updateReceipt ($buID, $buIndex) {
		  
		  $receipts = ['receipt_header' => [],
							'receipt_footer' => []];
		  $status = 1;
		  
        if (!empty ($this->request->getData ())) {
				
				foreach (['receipt_header', 'receipt_footer'] as $section) {
					 
					 $receipts [$section] = $this->request->getData () [$section];
				}
				
		  
				$businessUnitsTable = TableRegistry::get ('BusinessUnits');
				$bu = $businessUnitsTable->find ()
												 ->where (['id' => $buID])
												 ->first ();
		  
				$bu ['params'] = json_decode ($bu ['params'], true);
		  
				foreach (['receipt_header', 'receipt_footer'] as $section) {
				
					 if (isset ($receipts [$section])) {
					 
						  $this->merchant ['business_units'] [$buIndex] ['params'] [$section] = $receipts [$section];
					 }
				}
				
				// save it in the session
		  
				$session = $this->request->getSession ();
				$session->write ('merchant', $this->merchant);
		  
				// save it in the db
				
				$bu ['params'] = json_encode ($this->merchant ['business_units'] [$buIndex] ['params']);
				$this->save ('BusinessUnits', $bu);

				$status = 0;
		  }
		  
		  $this->ajax (['status' => $status]);
	 }
}

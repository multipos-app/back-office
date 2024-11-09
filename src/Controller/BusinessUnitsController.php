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

class BusinessUnitsController extends PosAppController {
    
    public function initialize (): void {
		  
        parent::initialize ();

    }

    public function index (...$params) {
		  
        require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'states.php';

        $businessUnitsTable = TableRegistry::get ('BusinessUnits');
        
        if (!empty ($this->request->getData ())) {

				
            $businessUnitsTable
                ->updateAll (['business_name' => strtoupper ($this->request->getData () ['business_name']),
                              'email' => strtoupper ($this->request->getData () ['email']),
                              'addr_1' => strtoupper ($this->request->getData () ['addr_1']),
                              'addr_2' => strtoupper ($this->request->getData () ['addr_2']),
                              'city' => strtoupper ($this->request->getData () ['city']),
                              'state' => $this->request->getData () ['state'],
                              'postal_code' => $this->request->getData () ['postal_code'],
                              'phone_1' => preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone_1']),
                              'phone_2' => preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone_2']),
                              'timezone' => $this->request->getData () ['timezone']],
                             ['business_type' => 1]);
            
        }
        
        $primaryBusiness = TableRegistry::get ('BusinessUnits')
													 ->find ()
													 ->where (['business_type = 1'])
													 ->first ();

        $query = TableRegistry::get ('BusinessUnits')
										->find ()
										->where (['business_type = 2']);


        $data = ['primaryBusiness' => $primaryBusiness,
					  'businessUnits' => $query->all (),
					  'states' => $states,
					  'timeZones' => $timeZones];
		  
        return ($this->response (__ ('Business Units'),
                                 'BusinessUnits',
                                 'index',
                                 $data));
    }
    
    public function edit ($id = 0) {

        require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'states.php';
		  
        $businessUnits = TableRegistry::get ('BusinessUnits');
        
        $primaryBusiness = $businessUnits
                         ->find ()
                         ->where (['business_type = 1'])
                         ->first ();
		  
        if (!empty ($this->request->getData ())) {

				if ($this->request->getData () ['business_name']) {
					 
					 $bu = $businessUnits->newEntity ($this->request->getData ());
					 $businessUnits->save ($bu);
				}
				else {
					 
					 $businessUnitsTable->updateAll (['business_name' => strtoupper ($this->request->getData () ['business_name']),
																 'email' => $this->request->getData () ['email'],
																 'addr_1' => strtoupper ($this->request->getData () ['addr_1']),
																 'addr_2' => strtoupper ($this->request->getData () ['addr_2']),
																 'city' => strtoupper ($this->request->getData () ['city']),
																 'state' => $this->request->getData () ['state'],
																 'postal_code' => $this->request->getData () ['postal_code'],
																 'phone_1' => preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone_1']),
																 'phone_2' => preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone_2']),
																 'timezone' => $this->request->getData () ['timezone']],
																['business_type' => 1]);
				}
		  }
		  
        $bu = ['id' => $id,
					'business_name' => '',
					'email' => '',
					'addr_1' => '',
					'addr_2' => '',
					'city' => '',
					'state' => '',
					'postal_code' => '',
					'phone_1' => '',
               'phone_2' => '',
               'timezone' => ''];
		  
        if ($id > 0) {
				
				$bu = $businessUnits
            ->find ()
            ->where (['id' => $id])
            ->first ();
		  }
		  
        $sameAsPrimary = [0 => __ ('No'),
                          1 => __ ('Yes')];
        
        return ($this->response (__ ('Edit location'),
                                 'BusinessUnits',
                                 'edit',
                                 compact ('bu', 'sameAsPrimary', 'primaryBusiness', 'states', 'timeZones')));    
    }

    public function settings ($buID) {
		  
        $businessUnits = TableRegistry::get ('BusinessUnits');
        $businessUnit = $businessUnits
                      ->find ()
                      ->where (['id' => $buID])
                      ->first ();
        
        if (!empty ($this->request->getData ())) {

            $businessUnit ['business_name'] = $this->request->getData () ['business_name'];
            $businessUnit ['phone_1'] = $this->request->getData () ['phone_1'];
            $businessUnit ['addr_1'] = $this->request->getData () ['addr_1'];
            $businessUnit ['contact'] = $this->request->getData () ['contact'];
            $businessUnit ['postal_code'] = $this->request->getData () ['postal_code'];
            $businessUnit ['city'] = $this->request->getData () ['city'];
            $businessUnit ['email'] = $this->request->getData () ['email'];

            $this->save ('BusinessUnits', $businessUnit);
            return $this->redirect (['controller' => 'sales', 'action' => 'index']);
        }
		  
		  
        if ($businessUnit) {

            $businessUnit ['params'] = json_decode ($businessUnit ['params'], true);
            
            $this->set ('businessUnit', $businessUnit);
            $this->set ('states', $states);

            switch ($this->bus [$this->bu] ['locale']) {

					 case 'da_DK':
						  
						  $this->render ('settings_dk');
						  break;
            }
				
            return;
        }
    }
	 
    public function addPayment () {

    }
	 
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

            require_once ROOT . DS . 'vendor' . DS  . 'firebase' . DS . 'firebase.php';
            $firebase = new  \App\Controller\Firebase ($this);

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
        
        
        if (strlen ($posUnit ['token']) > 0) {  // notifiy POS if Google Messaging registered
            
            require_once ROOT . DS . 'vendor' . DS  . 'firebase' . DS . 'firebase.php';
            $firebase = new  \App\Controller\Firebase ($this);
            
            $result = $firebase->send ($posUnit ['token'], ['method' => 'update']);
       }
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
                       4 => __ ('Pending, schedule for future')];
        
        return ($this->response (__ ('POS Batches'),
                                 'BusinessUnits',
                                 'batches',
                                 compact ('batches', 'batchTypes')));
    }
    
    public function create () {

        $status = -1;
        
        if (!empty ($this->request->getData ())) {
            
				$this->loadComponent ('Batch');
            
            $submit = null;
            if (strlen ($this->request->getData () ['submit_date']) > 0) {
                
                $submit = date ('yy-m-d', strtotime ($this->request->getData () ['submit_date']));
                
                if (strlen ($this->request->getData () ['submit_time']) > 0) {
                    
                    $submit .= ' ' . $this->request->getData () ['submit_time'] . ':00';
                }
                else {
                    $submit .= '00:00:00';
                }
            }
            
            // $submit = $this->localToUTC ($submit, $this->tz ());
            
            $batchID = $this->Batch->createBatch ($this->request->getData () ['batch_type'],
																  $this->merchant ['bu_id'],
																  $this->request->getData () ['batch_desc'],
																  date ('Y-m-d H:i:s', time ()));

            $status = 0;
        }

        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);
    }

    
    function cancelBatches () {

        $status = -1;

        if (!empty ($this->request->getData ())) {

            $batches = TableRegistry::get ('Batches');
            foreach ($this->request->getData () ['batches'] as $batchID) {
                
                $batchID = intVal (substr ($batchID, 1));

                TableRegistry::get ('BatchEntries')->deleteAll (['batch_id' => $batchID]);
                TableRegistry::get ('Batches')->deleteAll (['id' => $batchID]);
                
            }
        }
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);
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

		  return ($this->response (__ ('Receipts'),
                                 'BusinessUnits',
                                 'receipts',
                                 compact ('locations'))); 
	 }

	 public function receipt ($buID) {

		  $bu = null;
		  foreach ($this->merchant ['business_units'] as $bu) {

				if ($bu ['id'] == $buID) {

					 break;
				}
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
		  
        return $this->response (__ ('POS Receipt'),
                                'BusinessUnits',
                                'receipt',
                                ['buID' => $buID,
											'qrcode' => '',
											'receipt' => $receipt],
                                false);
	 }

	 
 	 /**
	  *
	  *
	  */

    public function updateReceipt ($buID) {

		  $receipts = ['receipt_header' => [],
							'receipt_footer' => []];
		  
        if (!empty ($this->request->getData ())) {

				foreach (['receipt_header', 'receipt_footer'] as $section) {

					 $receipts [$section] = $this->request->getData () [$section];
				}
		  }
		  
		  $businessUnitsTable = TableRegistry::get ('BusinessUnits');
		  $bu = $businessUnitsTable->find ()
											->where (['id' => $buID])
											->first ();
		  
		  $bu ['params'] = json_decode ($bu ['params'], true);
		  
		  foreach (['receipt_header', 'receipt_footer'] as $section) {
				
				if (isset ($receipts [$section])) {
					 
					 $this->merchant ['business_units'] [$buID] ['params'] [$section] = $receipts [$section];
				}
		  }
		  
		  // save it in the session
		  
		  $session = $this->request->getSession ();
		  $session->write ('merchant', $this->merchant);
		  
		  // save it in the db
		  
		  $bu ['params'] = json_encode ($this->merchant ['business_units'] [$buID] ['params']);
		  $this->save ('BusinessUnits', $bu);
		  
		  $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => 0]);
    }
}

function guid () {
    
    if (function_exists ('com_create_guid') === true) {
        
        return trim (com_create_guid (), '{}');
    }
    
    $data = openssl_random_pseudo_bytes (16);
    $data[6] = chr (ord ($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr (ord ($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf ('%s%s-%s-%s-%s-%s%s%s', str_split (bin2hex ($data), 4));
}

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

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use \DateTime;
use \DateTimeZone;
use Cake\I18n\I18n;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class PosController extends PosApiController {
  
    public $bu = null;
    public $bus = null;
    public $posNo = null;
    public $totalTimes = null;
    
    /**
     *
     * New client registration
     *
     */
    
    public function register () {
     
        $response = ['status' => 1];

		  $this->debug ($this->request->getData ());
		  
        if (isset ($this->request->getData () ['uname']) && isset ($this->request->getData () ['passwd'])) {
				        
            $merchantUser = TableRegistry::get ('MerchantUsers')
                          ->find ()
                          ->where (['uname' => $this->request->getData () ['uname'],
                                    'passwd' => md5 ($this->request->getData () ['passwd'])])
                          ->first ();
				
            if ($merchantUser) {
                
                $dbname = 'm_' . $merchantUser ['merchant_id'];
                $this->dbconnect ($dbname);
					 
                $response = ['register_status' => 0,
									  'merchant_id' => $merchantUser ['merchant_id']];

					 $query = TableRegistry::get ('BusinessUnits')->find ()->order ('business_type asc');
					 foreach ($query as $bu) {
						  
						  if ($bu ['business_type'] == 1) {
								
								$response ['business_name'] = $bu ['business_name'];
						  }
						  else {
								
								$response ['business_units'] [] = ['business_unit_id' => $bu ['id'],
																			  'business_name' => $bu ['business_name'],
																			  'addr_1' => $bu ['addr_1'],
																			  'city' => $bu ['city'],
																			  'state' => $bu ['state'],
																			  'postal_code' => $bu ['postal_code'],
																			  'phone_1' => $bu ['phone_1'],
																			  'timezone' => $bu ['timezone'],
																			  'params' => $bu ['params']];
						  }
					 }

					 $query = TableRegistry::get ('PosConfigs')->find ();
					 foreach ($query as $posConfig) {

						  $response ['pos_configs'] [] = ['pos_config_id' => $posConfig ['id'],
																	 'config_desc' => $posConfig ['config_desc']];
					 }
					 
					 $response ['register_status'] = 0;
				}
            else {
					 
                $response = ['register_status' => 1,
                             'status_text' => 'invalid_username_or_password',
									  'remote_ip' =>  $_SERVER ['HTTP_X_FORWARDED_FOR']];
            }
        }
		  
		  $this->debug ($response);

        $this->set ('response', $response);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');
    }    
    
    /**
     *
     * Setup a new device
     *
     */
  
    public function init () {
		  
        $response = ['status' => 0];

		  $this->debug ($this->request->getData ());
         
        $this->dbconnect ('m_' . $this->request->getData () ['merchant_id']);

		  $devicesTable = TableRegistry::get ('Devices');
		  
		  $device = ['create_time' => 'current_timestamp',
						 'business_unit_id' => $this->request->getData () ['business_unit_id'],
						 'pos_config_id' => $this->request->getData () ['pos_config_id'],
						 'access_token' => $this->guid (),
						 'refresh_token' => $this->guid (),
						 'access_status' => 'success',
						 'version_code' => $this->request->getData () ['version_code'],
						 'version_name' => $this->request->getData () ['version_name'],
						 'display_density' => $this->request->getData () ['density'],
						 'display_width' => $this->request->getData () ['display_width'],
						 'display_height' => $this->request->getData () ['display_height'],
						 'model' => $this->request->getData () ['model'],
						 'sdk' => $this->request->getData () ['sdk'],
						 'android_id' => $this->request->getData () ['android_id'],
						 'android_release' => $this->request->getData () ['android_release']];
		  		  
		  $device = $devicesTable->newEntity ($device);
		  $devicesTable->save ($device);
		  
		  $response ['device'] = ['id' => $device ['id'],
										  'business_unit_id' => $this->request->getData () ['business_unit_id'],
										  'pos_config_id' => $this->request->getData () ['pos_config_id'],
										  'access_token' => $this->guid (),
										  'refresh_token' => $this->guid ()];

		  $this->debug ($response);

        $this->set ('response', $response);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**
     *
     * Capture a ticket
	  *
	  * save it to pending tickets
	  * exec processing in background
     *
     */
  
    public function ticket () {
		  
        // $timeDiff = time () - strtotime ($this->request->getData () ['ticket'] ['complete_time']);

		  $timeDiff = 0;
        $ticketNo = 0;
		  
        if (isset ($this->request->getData () ['ticket'] ['ticket_no'])) {
            
            $ticketNo = $this->request->getData () ['ticket'] ['ticket_no'];
        }

		  $this->request->getData () ['ticket'] ['complete_time'] = date ('Y-m-d H:i:s', time ());

        /* $log = ['ticket' => ['dbname' => $this->request->getData () ['dbname'],
			*                      'business_unit_id' => $this->request->getData () ['ticket'] ['business_unit_id'],
			*                      'pos_no' => $this->request->getData () ['ticket'] ['pos_no'],
			*                      'ticket_no' => $this->request->getData () ['ticket'] ['ticket_no'],
			*                      'state' => $this->request->getData () ['ticket'] ['state'],
			*                      'ticket_type' => $this->request->getData () ['ticket'] ['ticket_type'],
			*                      'complete_time' => $this->request->getData () ['ticket'] ['complete_time'],
			*                      'time_diff' => $timeDiff,
			*                      'uuid' => $this->request->getData () ['ticket'] ['uuid']]];*/
             
        if (abs ($timeDiff) > (60 * 5)) {
            
            $log ['time_diff'] = $timeDiff;
        }
        
        $dbname = false;
        $response = ['status' => 1,
                     'status_text' => 'invalid request'];
        
        if (isset ($this->request->getData () ['merchant_id'])) {  

				$dbname = 'm_' . $this->request->getData () ['merchant_id'];
				
            $this->dbconnect ($dbname);
            $pendingTicketTable = TableRegistry::get ('PendingTickets');

            switch ($this->request->getData () ['ticket'] ['state']) {

            case SUSPEND:
            case RECALLED:

                break;
                
            default:
                
						  /* $t = $pendingTicketTable
							*    ->find ()
							*    ->where (['uuid' => $this->request->getData () ['ticket'] ['uuid']])
							*    ->first ();
							*             
							* if ($t) {
							*                 
							*     $this->error ('pos controller duplicate ticket... ' . $this->request->getData () ['ticket'] ['pos_no'] . ' ' . $ticketNo, 'debug');
							*     $this->jsonResponse (['status' => 0,
							*                           'status_text' => 'duplicate ticket']);
							*     return;
							* }*/
            }

            $pt = ['business_unit_id' => $this->request->getData () ['ticket'] ['business_unit_id'],
                   'pos_no' => $this->request->getData () ['ticket'] ['pos_no'],
                   'ticket_no' => $ticketNo,
                   'uuid' => $this->request->getData () ['ticket'] ['uuid'],
                   'json' => json_encode ($this->request->getData ())];
            
            $pendingTicket = $pendingTicketTable->newEntity ($pt);
            $pendingTicketTable->save ($pendingTicket);
            $ticketID = $pendingTicket ['id'];
            
            $response = ['status' => 0,
                         'pending_ticket_id' => $ticketID,
                         'status_text' => 'OK'];
        }
        
        $this->jsonResponse ($response);
        
        if ($dbname) {

            $result = -1;
            $exec = getcwd () . "/../bin/cake ConsumeTicketsByID $dbname $ticketID >/dev/null 2>/dev/null &";
            $this->debug ("exec... $exec");
            $result = shell_exec ($exec);
        }
    }


    function upload ($dbname, $fname) {

		  $response = ['status' => 1,
							'status_text' => 'Unknown error'];

		  $path = "/var/www/html/pos-logs/$dbname/";

		  if (!file_exists ($path)) {
    
				if (!mkdir ($path, 0755, true)) {
					 
					 $response = ["status" => 1,
									  "result_text" => "Failed to create directory"];
				}
		  }

  		  $path = $path.basename ($_FILES ['uploaded_file'] ['name']);
		  
		  if (move_uploaded_file ($_FILES ['uploaded_file'] ['tmp_name'], $path)) {

				$response = ['status' => 0,
								 'status_text' => 'success'];

		  }
		  
		  $this->jsonResponse ($response);
	 }
	 
    function status () {
		  
		  $this->debug ($this->request->getData ());
		  
        $this->set ('response', ['status' => 0]);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');		  
	 }
	 
    /**
     *
     * pos app update item
     *
     */
	 
    function itemUpdate () {

		  $status = 1;
		  
		  $this->debug ($this->request->getData ());

		  if (isset ($this->request->getData () ['item'])) {

				$status = 0;
				$merchantID = $this->request->getData () ['merchant_id'];
				$data = $this->request->getData () ['item'];
				
				$this->dbconnect ("m_$merchantID");

				// get all the locations for pricing
				
				$query = TableRegistry::get ('BusinessUnits')
											 ->find ()
											 ->where (['business_type' => 2])
											 ->select (['id'])
											 ->all ();
				
				$pricing = json_encode (['class' => 'standard',
												 'price' => $data ['price'],
												 'cost' => $data ['price']]);
			

				// check for existing item
				
				$itemsTable = TableRegistry::get ('Items');
				$item = $itemsTable->find ()
										 ->where (['sku' => $data ['sku']])
										 ->contain (['ItemPrices', 'ItemLinks'])
										 ->first ();

				if ($item) {
					 
					 $this->debug ('existing item... ');
					 $this->debug ($item);

					 $item ['item_desc'] = $data ['item_desc'];
					 $item ['department_id'] = $data ['department_id'];
					 $itemsTable->save ($item);

					 TableRegistry::get ('ItemPrices')->updateAll (['tax_group_id' => $data ['tax_group_id'],
																					'price' => $data ['price'],
																					'cost' => $data ['cost'],
																					'pricing' => $pricing],
																				  ['item_id' => $item ['id']]);
		
					 $status = 0;
				}
				else {
					 

					 $this->debug ('new item... ');
					 
					 $itemPrices = [];
					 foreach ($query as $bu) {

						  $itemPrices [] = ['business_unit_id' => $bu ['id'],
												  'tax_group_id' => $data ['tax_group_id'],
												  'tax_inclusive' => 0,
												  'pricing' => $pricing,
												  'price' => $data ['price'],
												  'cost' => $data ['cost'],
												  'class' => 'standard',
												  'supplier_id' => 0];
					 }
					 
					 $item = ['item_desc' => $data ['item_desc'],
								 'sku' => $data ['sku'],
								 'department_id' => $data ['department_id'],
								 'locked' => 0,
								 'enabled' => 1,
								 'supplier_id' => 0,
								 'uuid' => '',
								 'item_prices' => $itemPrices];
					 
					 $item = $itemsTable->newEntity ($item);
					 $itemsTable->save ($item);
					 $status = 0;
		  		}
				

				$this->batch ('items', $item ['id']);
				$this->debug ($item);
				$this->notifyPOS ($merchantID);
		  }
		  
		  $this->set ('response', ['status' => $status]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
	 }

	 /**
     *
     * pos app get inventory
     *
     */
	 
    function inventory ($itemID) {

		  $this->debug ("inv... $itemID");
		  $this->debug ($this->request->getData ());
		  
        $response = ['status' => 1];

        if (!empty ($this->request->getData ())) {
				
				$this->dbconnect ('m_' . $this->request->getData () ['merchant_id']);

				$invItem = TableRegistry::get ('InvItems')
												->find ()
												->where (['item_id' => $itemID,
															 'business_unit_id' => $this->request->getData () ['business_unit_id']])
												->first ();

				$this->debug ($invItem);
				
				$response = ['status' => 0,
								 'inv_count' => $invItem ['on_hand_count']];

				$this->debug ($response);

				$this->jsonResponse ($response);
				return;
        }

        $this->jsonResponse ($response);
        return;
    }

    /**
     *
     * pos app update inventory
     *
     */
    
    function inventoryUpdates () {

        $response = ['status' => 1];

        if (!empty ($this->request->getData ())) {

            $this->debug ("pos inventory update... ");
            $this->debug ($this->request->getData ());

            $this->dbconnect ('m_' . $this->request->getData () ['merchant_id']);

            foreach ($this->request->getData () ['updates'] as $update) {

                TableRegistry::get ('InvItems')
									  ->updateAll (['on_hand_count' => $update ['on_hand_count'],
														 'on_hand_quantity' => $update ['on_hand_count']],
														['item_id' => $update ['item_id'],
														 'business_unit_id' => $this->request->getData () ['business_unit_id']]);
            }

            $response ['status'] = 0;

            $this->jsonResponse ($response);
        }

        $this->jsonResponse ($response);
        return;
    }

    /**
     *
     * 
     *
     */

    public function posLogin () {

        $response = ['status' => 0];

        $this->dbconnect ($this->request->getData () ['dbname']);

        $posTable = TableRegistry::get ('Devices');

        $posNo = intVal ($this->request->getData () ['pos_no']);
        $token = $this->request->getData () ['token'];

        $query = $posTable->find ()->where (["pos_no = $posNo"]);
        $pos = $query->first ();

        if ($pos) {

            $pos ['token'] = $token;
            $pos ['status'] = 1;
            $posTable->save ($pos);
        }

        $this->jsonResponse ($response);
    }


	 function delItems ($merchantID, $departmentID) {

		  $this->dbconnect ("m_$merchantID");

		  $itemsTable = TableRegistry::get ('Items');
		  $itemPricesTable = TableRegistry::get ('ItemPrices');
		  $itemLinksTable = TableRegistry::get ('ItemLinks');
		  
		  $query = $itemsTable->find ()
									 ->where ("department_id = $departmentID");
		  
		  foreach ($query as $item) {

				$this->debug ($item);
				
				$itemPricesTable->deleteAll (['item_id' => $item ['id']]);
				$itemLinksTable->deleteAll (['item_id' => $item ['id']]);
				$itemsTable->deleteAll (['id' => $item ['id']]);
		  }
		  
        $this->jsonResponse (['status' => 0]);
	 }

}

?>

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

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class PosApiController extends AppController {
	 
	 private $utcOffsets = ['America/New_York' => 5,
									'America/Chicago' => 6,
									'America/Denver' => 7,
									'America/Los_Angeles' => 8,
									'America/Anchorage' => 9,
									'HST' => 10,
									'Europe/London' => 0,
									'Europe/Copenhagen' => 23];
	 
    public function dbconnect ($dbname) {
		  
        ConnectionManager::drop ('default');
        ConnectionManager::setConfig ('default', ['className' => 'Cake\Database\Connection',
                                                  'driver' => 'Cake\Database\Driver\Mysql',
                                                  'persistent' => false,
                                                  'host' => 'localhost',
                                                  'username' => 'vr',
                                                  'database' => $dbname,
                                                  'encoding' => 'utf8',
                                                  'timezone' => 'UTC',
                                                  'cacheMetadata' => true,
        ]);
    }
    
    public function jsonResponse ($response) {
		  
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', $response);
        $this->RequestHandler->respondAs ('json');
    }

    public function batch ($table, $id) {

        $batchID = 0;
        $batchTable = TableRegistry::get ('Batches');
        $batch = $batchTable
               ->find ()
               ->order (['id' => 'desc'])
               ->first ();
        
        if ($batch) {
            
            $batchID = $batch ['id'];
        }
        else {
            
            $batch = ['batch_desc' => __ ('Auto batch'),
                      'batch_type' =>  0];
            
            $batch = $batchTable->save ($batchTable->newEntity ($batch));
            $batchID = $batch ['id'];
        }
        
        $batchEntry = ['business_unit_id' => 0,
							  'batch_id' => $batchID,
							  'update_table' => $table,
							  'update_id' => $id,
							  'update_action' => 0,
							  'execution_time' => time ()];
        
        $batchEntriesTable = TableRegistry::get ('BatchEntries');
        $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
        $batchEntriesTable->save ($batchEntry);

        $batch ['update_count'] += 1;
        $batchTable->save ($batch);
	 }
	 
    public function itemsJson () {
		  
        if (!empty ($this->request->getData ())) {

				
            $json = $this->request->getData ();
				
				$this->debug ($json);
				
				$this->dbconnect ($json ['dbname']);
				
            $departmentTable = TableRegistry::get ('Departments');
            $itemsTable = TableRegistry::get ('Items');
            $itemPricesTable = TableRegistry::get ('ItemPrices');
            $invItemsTable = TableRegistry::get ('InvItems');

            $departmentTable->deleteAll ([]);
            $itemsTable->deleteAll ([]);
            $itemPricesTable->deleteAll ([]);
            $invItemsTable->deleteAll ([]);

            foreach ($json ['departments'] as $d) {

                $dept = $departmentTable->newEntity (['department_desc' => strtoupper ($d ['desc'])]);
                $dept = $departmentTable->save ($dept);
					 
                $sku = $d ['start_sku'];
					 
                foreach ($d ['items'] as $i) {

                    $item = $itemsTable->newEntity (['department_id' => $dept ['id'],
                                                     'sku' => strval ($sku),
                                                     'item_desc' => strtoupper ($i ['item_desc'])]);

                    $item = $itemsTable->save ($item);

                    $itemPrice = $itemPricesTable->newEntity (['item_id' => $item ['id'],
																					'business_unit_id' => $json ['business_unit_id'],
																					'price' => floatval ($i ['price']),
                                                               'pricing' => '{"class":"standard","price":"'.floatval ($i ['price']).'","cost":"0.00"}']);
						  
                    $itemPrice = $itemPricesTable->save ($itemPrice);
						  
                    $invItem = $invItemsTable->newEntity (['item_id' => $item ['id'],
 																			  'business_unit_id' => $json ['business_unit_id']]);
						  
                    $invItemsTable->save ($invItem);
 						  $sku ++;
               }
            }
        }
		  exit;
    }

	 function posLog () {
		  
		  if (!empty ($this->request->getData ())) {
	
            $this->debug ($this->request->getData ());
		  }
        $this->ajax (['status' => 0]);
	 }

 	 protected function notifyPOS ($merchantID) {
		  
		  $exec = 'mosquitto_pub -h localhost ' .
					 ' -m \'{"method": "download"}\'' .
					 ' -t \'multipos/' . $merchantID . '\'';
		  
		  shell_exec ($exec);
	 }
	 
	 public function message ($merchantID) {

		  $response = ['status' => 1];
		  if (!empty ($this->request->getData ())) {
				
				$this->dbconnect ("m_$merchantID");
		  
				$posMessages = TableRegistry::get ('PosMessages');
				$batchEntriesTable = TableRegistry::get ('BatchEntries');

				$posMessage = $posMessages->newEntity (["message" => json_encode ($this->request->getData ())]);
				$posMessage = $posMessages->save ($posMessage);
				
				$batchEntry = $batchEntriesTable->newEntity (['business_unit_id' => 1,
																			 'update_table' => 'pos_messages',
																			 'update_id' =>  $posMessage ['id'],
																			 'update_action' => 0,
																			 'execution_time' => time ()]);
				$batchEntriesTable->save ($batchEntry);


				$exec = 'mosquitto_pub -h localhost -t ' . "multipos/$merchantID" . ' -m \'{"method": "download"}\'';
				$result = shell_exec ($exec);

				$this->debug ("pos message... $exec $result");
		  }

		  $this->jsonResponse ($response);
	 }
	 
	 protected function guid () {
    
		  if (function_exists ('com_create_guid') === true) {
				
				return trim (com_create_guid (), '{}');
		  }
		  
		  $data = openssl_random_pseudo_bytes (16);
		  $data[6] = chr (ord ($data[6]) & 0x0f | 0x40); // set version to 0100
		  $data[8] = chr (ord ($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		  return vsprintf ('%s%s-%s-%s-%s-%s%s%s', str_split (bin2hex ($data), 4));
	 }
	 
    protected function tzOffset ($tz) {

		  return $this->utcOffsets [$tz];
	 }
}



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

    public function save ($table, $entity) {
        
        $table = TableRegistry::get ($table);
		  
        $res = $table->save ($entity);

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
                      'batch_type' =>  0,
                      'business_unit_id' => $this->buID];
            
            $batch = $batchTable->save ($batchTable->newEntity ($batch));
            $batchID = $batch ['id'];
        }
        
        $batchEntry = ['business_unit_id' => 0,
                       'batch_id' => $batchID,
                       'update_table' => $table->getTable (),
                       'update_id' => $res ['id'],
                       'update_action' => 0,
                       'execution_time' => time ()];
        
        
        $batchEntryTable = TableRegistry::get ('BatchEntries');
        $batchEntry = $batchEntryTable->newEntity ($batchEntry);
        $batchEntryTable->save ($batchEntry);

        $batch ['update_count'] += 1;
        $batchTable->save ($batch);
		  
        return $res;
    }

    function tzOffset ($tz) {
        
        /* $originDateTimezone = new DateTimeZone ('UTC');
			* $targetDateTimezone = new DateTimeZone ($tz);
			* $originDateTime = new DateTime ("now", $originDateTimezone);
			* $targetDateTime = new DateTime ("now", $targetDateTimezone);
			* $offset = $originDateTimezone->getOffset ($originDateTime) - $targetDateTimezone->getOffset ($targetDateTime);

			  $this->debug ("tzoffset... $tz " . $offset / 60 / 60);
			  
			* return intVal ($offset / 60 / 60);*/

		  $timezones = ['America/New_York' => 5,
							 'America/Chicago' => 6,
							 'America/Denver' => 7,
							 'America/Los_Angelas' => 8,
							 'America/Anchorage' => 9];
							 
		  return $timezones [$tz];
    }
}



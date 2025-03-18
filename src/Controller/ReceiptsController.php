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

class ReceiptsController extends PosAppController {
    
    public function initialize (): void {
		  
        parent::initialize ();

    }

    public function index (...$params) {

		  $locations = [];
		  foreach ($this->merchant ['business_units'] as $bu) {
				
				if ($bu ['business_type'] == BU_LOCATION) {
					 
					 $locations [] = $bu;
				}
		  }
		  
		  $this->set (['locations' => $locations]);
	 }
}

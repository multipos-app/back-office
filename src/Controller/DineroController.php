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

class DineroController extends PosAppController {

	 public function accounting () {
        
        $this->debug ($this->request->getData ());

        if (!empty ($this->request->getData ())) {

            if (isset ($this->request->getData () ['accounting_provider'])) {
                
                switch ($this->request->getData () ['accounting_provider']) {

						  case 'dinero':
								
								$this->bus [$this->bu] ['params'] = ['accounting' => ['provider' => 'dinero',
																										'account_id' => '',
																										'client_id' => 'POSappliance',
																										'client_secret' => 'vbAb00ZaeEqKkubIsRw2HrS6rKgNaesZv1zohPRN4',
																										'encoded_token_auth' => '',
																										'username' => '',
																										'password' => '',
																										'accounts' => ['total' => ['account_no' => 1000, 'multiplier' => -1, 'desc' => 'Salg of varer', 'tax_code' => 'U25'],
																															'cash' => ['account_no' => 55040, 'multiplier' => 1, 'desc' => 'Kontant'],
																															'account' => ['account_no' => 54041, 'multiplier' => 1, 'desc' => 'Konto'],
																															'dankort' => ['account_no' => 55000, 'multiplier' => 1, 'desc' => 'Dankort'],
																															'visa' => ['account_no' => 54050, 'multiplier' => 1, 'desc' => 'Visa'],
																															'mastercard' => ['account_no' => 54050, 'multiplier' => 1, 'desc' => 'Mastercard'],
																															'maestro' => ['account_no' => 54050, 'multiplier' => 1, 'desc' => 'Maestro'],
																															'mobile' => ['account_no' => 54053, 'multiplier' => 1, 'desc' => 'Mobile'],
																															'cash_drop' => ['account_no' => 55000, 'multiplier' => 1, 'desc' => 'Bank']]]];
								break;
                }
            }
            else if (isset ($this->request->getData () ['account_update'])) {
                
                switch ($this->request->getData () ['account_update']) {
								
						  case 'dinero':

								foreach ($this->request->getData () ['accounts'] as $key => $account) {

									 $this->bus [$this->bu] ['params'] ['accounting'] ['accounts'] [$key] ['account_no'] = $this->request->getData () ['accounts'] [$key] ['account_no'];
									 $this->bus [$this->bu] ['params'] ['accounting'] ['accounts'] [$key] ['desc'] = $this->request->getData () ['accounts'] [$key] ['desc'];
								}
								
								$this->bus [$this->bu] ['params'] ['accounting'] ['account_id'] = $this->request->getData () ['account_id'];
								$this->bus [$this->bu] ['params'] ['accounting'] ['username'] = $this->request->getData () ['api_key'];
								$this->bus [$this->bu] ['params'] ['accounting'] ['password'] = $this->request->getData () ['api_key'];
								$this->bus [$this->bu] ['params'] ['accounting'] ['encoded_token_auth'] = base64_encode ('POSappliance:' . $this->bus [$this->bu] ['params'] ['accounting'] ['client_secret']);
								
								$this->debug ($this->bus [$this->bu]);
								
								$businessUnitsTable = TableRegistry::get ('BusinessUnits');
								$businessUnit = $businessUnitsTable
                                  ->find ()
                                  ->where (['business_type' => 1])
                                  ->first ();
								if ($businessUnit) {

									 $businessUnit ['params'] = json_encode ($this->bus [$this->bu] ['params']);
									 $businessUnitsTable->save ($businessUnit);
								}
								
								break;
                }
            }
        }
		  
        if (isset ($this->bus [$this->bu] ['params'] ['accounting'])) {
            
            switch ($this->bus [$this->bu] ['params'] ['accounting'] ['provider']) {
						  
					 case 'dinero':

						  $this->set ('accounting', $this->bus [$this->bu] ['params'] ['accounting']);
						  $this->render ('dinero');
						  break;
            }
        }

        $this->set ('accounting', [null => __ ('Select Provider'),
                                   'dinero' => 'Dinero',
                                   'economic' => 'Economic',
                                   'business_central_365' => 'Business Central 365',
                                   'billy' => 'Billy',
                                   'quick_books' => 'QuickBooks Online',
                                   'sage' => 'Sage Business Cloud Accounting']);
        
    }
}

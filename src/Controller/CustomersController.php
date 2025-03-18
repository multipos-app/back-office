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
use App\Model\Entity\Customer;

class CustomersController extends PosAppController {
    
    public $paginate = ['limit' => 25];

    public function index (...$params) {

        $customers = [];
        $q = null;
        
        if (count ($params) > 1) {                        
            
            $q = TableRegistry::get ('Customers')
										->find ()
										->where (['id' => $params [1]]);
        }
        else {
            
            $q = TableRegistry::get ('Customers')
										->find ('all');
        }

        foreach ($this->paginate ($q) as $customer) {
            
            $customer ['phone'] = $this->phoneFormat ($customer ['phone']);
            $customer ['last_update'] = date ('M d', strtotime ($customer ['last_update']));
				$customers [] = $customer;
        }
		  
 		  $this->set (['merchant' => $this->merchant,
							'customers' => $customers]);
	 }
    
    public function edit ($id = 0) {
		  
		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'states.php';

		  $customer = null;

        if ($id == 0) {

            // generate a customer number
            
            $customer = ['id' => 0,
								 'fname' => '',
								 'lname' => '',
								 'email' => '',
								 'phone' => '',
								 'addr_1' => '',
								 'addr_2' => '',
								 'city' => '',
								 'state' => '',
								 'postal_code' => ''];
        }
        else {
            
            $customer = TableRegistry::get ('Customers')
												 ->find ()
												 ->where (['id' => $id])
												 ->first ();
        }

        $totals = [];

        /* $som = $this->startOfMonth ();
			* $soy = $this->startOfYear ();
			*/       
        $monthTotal = 0;
        $yearTotal = 0;
        
        $ticketsTable = TableRegistry::get ('Tickets');

        /* foreach ([$som, $soy] as $d) {
			*     
			*     $query = $ticketsTable
			*            ->find ()
			*            -> where (["customer_id" => $id,
			*                       "complete_time > '$d'"]);

			*     $total =$query->select (['sum' => $query->func ()->sum ('total')])->first ();
			*     
			*     if ($total ['sum'] != 0) {
			*         
			*         $totals [] = $total ['sum'];
			*     }
			*     else {
			*         
			*         $totals [] = 0;
			*     }
			* }*/

        
        switch ($this->merchant ['locale']) {
					 
				case 'da_DK':
					 
					 $this->render ('edit_dk');
					 break;

				default:
					 
					 // $customer ['phone'] = strlen ($customer ['phone']) == 9 ? $this->phoneFormat ($customer ['phone']) : '';
        }


		  $this->debug ($customer);
        
        return ($this->response (__ ($customer ['fname'] . ' ' . $customer ['lname']),
                                 'Customers',
                                 'edit',
                                 compact ('customer',
														'totals',
														'states')));
    }

    public function update ($id = 0) {

        $status = -1;
        
        if (!empty ($this->request->getData ())) {

				$this->debug ("update customer... $id");
				$this->debug ($this->request->getData ());

            $customer = false;
            $customerTable = TableRegistry::get ('Customers');
				
            if ($id > 0) {
                
                $customer = $customerTable
                          ->find ()
                          ->where (['id' => $id])
                          ->first ();
                
                if ($customer) {

                    $customer ['fname'] = strtoupper ($this->request->getData () ['fname']);
                    $customer ['lname'] = strtoupper ($this->request->getData () ['lname']);
                    $customer ['email'] = $this->request->getData () ['email'];
                    $customer ['phone'] = preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone']);
                    $customer ['addr_1'] = strtoupper ($this->request->getData () ['addr_1']);
                    $customer ['addr_2'] = strtoupper ($this->request->getData () ['addr_2']);
                    $customer ['city'] = strtoupper ($this->request->getData () ['city']);
                    $customer ['state'] = strtoupper ($this->request->getData () ['state']);
                    $customer ['postal_code'] = $this->request->getData () ['postal_code'];
                }
            }
            else {

                $customer = $customerTable->newEntity (['fname' => strtoupper ($this->request->getData () ['fname']),
                                                        'lname' => strtoupper ($this->request->getData () ['lname']),
                                                        'email' => $this->request->getData () ['email'],
                                                        'phone' => preg_replace ('/\(|\)|\s+|\-/', '', $this->request->getData () ['phone']),
                                                        'addr_1' => strtoupper ($this->request->getData () ['addr_1']),
                                                        'addr_2' => strtoupper ($this->request->getData () ['addr_2']),
                                                        'city' => strtoupper ($this->request->getData () ['city']),
                                                        'state' => strtoupper ($this->request->getData () ['state']),
                                                        'postal_code' => $this->request->getData () ['postal_code']]);
            }
            
            $this->save ('Customers', $customer);
        }

		  $status = 0;
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);
    }

    public function delete ($id) {
        
        $this->request->allowMethod (['post', 'delete']);

        $customer = $this->Customers->findById ($id)->firstOrFail ();
        $id = $customer ['id'];
        if ($this->Customers->delete ($customer)) {

            $batchEntry = ['business_unit_id' => $this->merchant ['bu_id'],
									'update_table' => 'customers',
									'update_id' => $id,
									'update_action' => 1];
				
            
            $batchEntryTable = TableRegistry::get ('BatchEntries');
            $batchEntry = $batchEntryTable->newEntity ($batchEntry);
            $batchEntryTable->save ($batchEntry);
            
            $this->Flash->success (__('The {0} customer has been deleted.', $customer->customerDesc));
            return $this->redirect (['action' => 'index']);
        }
    }

    function invoices () {
    }
    
    function inventImport () {
        
        $this->viewBuilder ()->setLayout ('ajax');
        
        $url = 'https://app.cloud.inventio.it/20190815140943261/smartapi/?type=CUSTOMER-GET&token={C541B0B4-E079-4CB0-A435-D8B3F8A4C264}';
        
        $headers = ["Content-type: Application/xml",
                    "Accept-Charset: utf-8",
                    "Cache-Control: no-cache"];
        
        $curl = curl_init ();
        
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt ($curl, CURLOPT_HTTPGET, false);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec ($curl);
        $xml = json_decode (json_encode ((array) simplexml_load_string ($response)), true);

        if (count ($xml) > 0) {
				
            $customerTable = TableRegistry::get ('Customers');
            $customerTable->deleteAll ([]);

            foreach ($xml ['customer'] as $icust) {
                
                $cust = $customerTable->newEntity (['customer_no' => $this->getVal ('no', $icust),
                                                    'name' => $this->getVal ('name', $icust),
                                                    'contact' => $this->getVal ('contact', $icust),
                                                    'addr_1' => $this->getVal ('address', $icust),
                                                    'addr_2' => $this->getVal ('address-2', $icust),
                                                    'city' => $this->getVal ('city', $icust),
                                                    'postal_code' => $this->getVal ('post-code', $icust),
                                                    'country_code' => $this->getVal ('country-region-code', $icust),
                                                    'currency_code' => $this->getVal ('currency-code', $icust),
                                                    'phone' => $this->getVal ('phone-no', $icust),
                                                    'email' => $this->getVal ('e-mail', $icust)]);
					 
                $customerTable->save ($cust);
            }
				
            $this->RequestHandler->respondAs ('json');
            $this->response->type ('application/json');
            $this->autoRender = false;
            echo json_encode (['status'=> 0]);

        }
    }

    function dineroImport () {

        $session = $this->request->getSession ();

        $bu = $session->read ('bus') [$session->read ('bu')];

        if (isset ($bu ['params'] ['accounting'])) {

            switch ($bu ['params'] ['accounting'] ['provider']) {

					 case 'dinero':
						  
						  require_once ROOT . DS . 'vendor' . DS  . 'dinero' . DS . 'dinero.php';

						  $accounting = new \App\Controller\Dinero ($this, $bu ['params'] ['accounting'], true);
						  $accounting->init ();
						  $customers = $accounting->getCustomers ();

						  if ($customers) {

								$accountID = 54101;
								$custTable = TableRegistry::get ('Customers');

								$max = $custTable
                         ->find ('all', ['fields' => ['account_id' => 'max(Customers.account_id)']])
                         ->first ();
								
								if ($max && ($max ['account_id'] > 0)) {
									 
									 $accountID = $max ['account_id'];
								}
								

								foreach ($customers as $customer) {

									 $cust = $custTable
                              ->find ()
                              ->where (['guid' => $customer ['contactguid']])
                              ->first ();

									 $email = '';
									 if (strlen ($customer ['email']) > 0) {
										  
										  $email = $customer ['email'];
									 }
									 
									 if (!$cust) {

										  $cust =  $custTable->newEntity (['email' => $email,
																					  'contact' => $customer ['name'],
																					  'addr_1' => $customer ['street'],
																					  'city' => $customer ['city'],
																					  'postal_code' => $customer ['zipcode'],
																					  'country_code' => $customer ['countrykey'],
																					  'phone' => $customer ['phone'],
																					  'guid' => $customer ['contactguid'],
																					  'account_id' => $accountID ++]);
										  
										  // $response = $accounting->addAccount ($accountID ++, $customer ['name']);

										  // $this->debug ($response);
									 }
									 else {
										  
										  $cust ['email'] = $email;
										  $cust ['contact'] = $customer ['name'];
										  $cust ['addr_1'] = $customer ['street'];
										  $cust ['city'] = $customer ['city'];
										  $cust ['postal_code'] = $customer ['zipcode'];
										  $cust ['country_code'] = $customer ['countrykey'];
										  $cust ['phone'] = $customer ['phone'];                    
									 }
									 
									 $this->save ('customers', $cust);
								}
						  }
						  break;
            }
        }

        return $this->redirect (['action' => 'index']);
    }

    private function getVal ($key, $src) {

        if (!is_array ($src [$key])) {
            return $src [$key];
        }
        return '';
    }

	 function rand () {

		  $customers = TableRegistry::get ('Customers')
											 ->find ()
											 ->all ();

		  foreach ($customers as $customer) {

				
 				/* $customer ['name'] = strtoupper ($customer ['name']);*/
 				$customer ['fname'] = strtoupper ($customer ['fname']);
 				$customer ['lname'] = strtoupper ($customer ['lname']);

				/* 
				 * 				$customer ['addr_1'] = strtoupper ($customer ['addr_1']);
				 *  				$customer ['addr_2'] = strtoupper ($customer ['addr_2']);
				 *  				$customer ['city'] = strtoupper ($customer ['city']);
				 * 				*/
				
				$customer ['phone'] = strval (rand (10,99)) . strval (rand (10,99)) . strval (rand (10,99)) . strval (rand (10,99)) . strval (rand (10,99));
				$customer ['loyalty_points'] = 0;
				$customer ['uuid'] = $this->uuid ();
				$customer ['city'] = 'anytown';
				$customer ['pin'] = sprintf ("%04d", rand (999,9999));

				TableRegistry::get ('Customers')->save ($customer);
		  }
	 }
}

?>

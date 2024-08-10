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
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Mailer;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class MerchantsController extends AppController {

    private $merchant;
    public $utcOffsets = ['America/New_York' => 5,
                          'America/Chicago' => 6,
                          'America/Denver' => 7,
                          'America/Los_Angeles' => 8,
                          'America/Anchorage' => 9,
                          'HST' => 10,
                          'Europe/London' => 0,
								  'Europe/Copenhagen' => 23];
	 
    public function index () {

        $this->set ('locale', 'en_US');
    	  $response = ['status' => 0];
		  
        if (!empty ($this->request->getData ())) {

				if (!isset ($this->request->getData () ['uname'])) {

					 $this->error ('bad login request, no username... ');
					 exit;
				}
				
            $query = TableRegistry::get ('MerchantUsers')
											 ->find ()
											 ->where (['uname' => $this->request->getData () ['uname']]);

            if ($m = $query->first ()) {

                if ($m ['passwd'] == md5 ($this->request->getData () ['passwd'])) {

                    $merchant =  TableRegistry::get ('Merchants')
															 ->find ()
															 ->where (['id' => $m ['merchant_id']])
															 ->first ();
						  
						  
						  $qrImage = '/var/www/html/clips/qr-' . $m ['merchant_id'] . '.png';
						  
						  if (!file_exists ($qrImage)) {  // create a qr mobile link

								$url = 'https://' . $_SERVER ['SERVER_NAME'] . '/snapshots/hourly/' . $merchant ['subscription_key'];
								$cmd = "qrencode -o $qrImage $url";
								exec ($cmd);
						  }

						  $dbname = 'm_'.$m ['merchant_id'];
                    $session = $this->request->getSession ();

						  $timezone = 'America/Anchorage';
						  if (isset ($this->merchant ['timezone'])) {

								$timezone = $this->merchant ['timezone'];
						  }

                    $this->merchant = $m->toArray ();
                    $this->merchant ['dbname'] = $dbname;
                    $this->merchant ['category'] = $merchant ['category'];
                    $this->merchant ['alt_merchant_id'] = $merchant ['alt_merchant_id'];
                    $this->merchant ['access_token'] = $merchant ['access_token'];
                    $this->merchant ['vendor_status'] = $merchant ['vendor_status'];
                    $this->merchant ['registration_id'] = $merchant ['registration_id'];
                    $this->merchant ['params'] = $merchant ['params'];
                    $this->merchant ['csrf_token'] = $merchant ['csrf_token'];
						  $this->merchant ['utc_offset'] = sprintf ('%02d:00:00', $this->utcOffsets [$timezone]);
						  $this->merchant ['utc_offset_hour'] = $this->utcOffsets [$timezone];
						  $this->merchant ['subscription_level'] = $merchant ['subscription_level'];
						  $this->merchant ['mqtt_broker'] = 'multipos/' . $merchant ['id'];
						  
                    // get thte menus
						  
                    require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . $m ['role'] . '-menus.php';
                    $this->merchant ['menus'] = $menus;

						  // connect to the merchant database
						  
                    ConnectionManager::drop ('default');

                    $config = ['className' => 'Cake\Database\Connection',
                               'driver' => 'Cake\Database\Driver\Mysql',
                               'persistent' => false,
                               'host' => 'localhost',
                               'username' => 'vr',
                               'database' => $dbname,
                               'encoding' => 'utf8',
                               'timezone' => 'UTC',
                               'cacheMetadata' => true,
                    ];
                    
                    ConnectionManager::setConfig ('default', $config);
                    
                    $this->init ($this->merchant, $session);
						  
                    if ($merchant ['status']) {
								
								$action = '';
                        if (isset ($this->request->getData () ['action'])) {

                            $action = '/' . $this->request->getData () ['action'];
                            $action = 'sales';
								}
								else if ($merchant ['status'] == 1) {

									 return $this->redirect (['controller' => '/pos-app/index/sales']);
								}
                        
                        return $this->redirect (['controller' => '/pos-app/index/' . $action]);
                    }
                }
                else {

						  $response = ['status' => 1,
											'status_text' => __ ('Bad password')];
						  
                    $this->debug ('merchant bad pass... ' . $this->request->getData () ['uname'], 'error');
                }
            }
            else {
                
 					 $response = ['status' => 1,
									  'status_text' => __ ('Bad user/email')];
					 
               $this->debug ('merchant not found... ' . $this->request->getData () ['uname']);
            }
				
 				/* $this->set ('response', $response);
					$this->viewBuilder ()
					->setLayout ('ajax')
					->disableAutoLayout ()
					->setTemplate ('ajax');
					$this->RequestHandler->respondAs ('json');*/
				
				$this->viewBuilder ()->setLayout ('login');

				return;
		  }

		  $this->viewBuilder ()->setLayout ('login');
    }
	 
    /**
     *
     * add/update a merchant user
     *
     */
    
	 public function user ($id = 0, $merchantID = null) {
		  		  
		  $user = null;
		  
		  $merchantUsersTable = TableRegistry::get ('MerchantUsers');
  
		  if ($id) {
				
				$user = $merchantUsersTable->find ()
													->where (['id' => $id])
													->first ();
		  }
		  else {

				$user = ['id' => 0,
							'merchant_id' => $merchantID,
							'uname' => '',
							'passwd' => '',
							'role' => 'viewer',
							'email' => '',
							'fname' => '',
							'lname' => '',
							'category' => '',
							'sub_category' => '',
							'locale' => 'en_US',
							'timezone' => 'America\/New_York',
							'reset_id' => null];
		  }
		  

		  
		  $data = ['user' => $user,
					  'roles' => ['admin' => 'Admin',
									  'viewer' => 'Viewer'],
					  'timezones' => [null => __ ('Timezone'),
											'America/New_York' => 'Eastern',
											'America/Chicago' => 'Central',
											'America/Denver' => 'Mountain',
											'America/Phoenix' => 'Mountain Time (no DST)',
											'America/Los_Angeles' => 'Pacific',
											'America/Anchorage' => 'Alaska',
											'Pacific/Honolulu' => 'Hawaii Time (no DST)',
											'Canada/Atlantic' => 'Atlantic',
											'Canada/East-Saskatchewan' => 'East-Saskatchewan',
											'Canada/Newfoundland' => 'Newfoundland',
											'Canada/Saskatchewan' => 'Saskatchewan',
											'Canada/Yukon' => 'Yukon']];
		  
		  $this->set ($data);
        
        return ($this->response (__ ('Merchant User'),
                                 'Merchants',
                                 'user',
                                 $data,
                                 false,
                                 'ajax',
                                 false));
	 }
	 

    /**
     *
     * update user data
     *
     */

	 public function userUpdate () {
		  
		  if (!empty ($this->request->getData ())) {
				
				$merchantUsersTable = TableRegistry::get ('MerchantUsers');
				$user = null;
				$response = ['status' => 0];
				
				$id = $this->request->getData () ['id'];
				$email = $this->request->getData () ['email'];
				
				if ($id > 0) {

					 $user = $merchantUsersTable->find ()
														 ->where (['id' => $id])
														 ->first ();
					 
					 $user ['merchant_id'] = $this->request->getData () ['merchant_id'];
					 $user ['uname'] = $this->request->getData () ['email'];
					 $user ['email'] = $this->request->getData () ['email'];
					 $user ['fname'] = $this->request->getData () ['fname'];
					 $user ['lname'] = $this->request->getData () ['lname'];
					 $user ['role'] = $this->request->getData () ['role'];
					 $user ['timezone'] = $this->request->getData () ['timezone'];
				}
				else {

					 // check if this user exists...
					 
					 $user = $merchantUsersTable->find ()
														 ->where (['email' => $email])
														 ->first ();

					 if ($user) {

						  $response = ['status' => 1,
											'status_text' => __ ('A user with this email already exists')];
					 }
					 else {
						  
						  $user = ['merchant_id' => $this->request->getData () ['merchant_id'],
									  'uname' => $this->request->getData () ['email'],
									  'email' => $this->request->getData () ['email'],
									  'fname' => $this->request->getData () ['fname'],
									  'lname' => $this->request->getData () ['lname'],
									  'role' => $this->request->getData () ['role'],
									  'timezone' => $this->request->getData () ['timezone']];
						  
						  $user = $merchantUsersTable->newEntity ($user);

						  // update the users for this session
						  
                    $session = $this->request->getSession ();
						  $this->merchant = $session->read ('merchant');
						  $this->merchant ['users'] [] = $user;
						  $session->write ('merchant', $this->merchant);
					 }
				}

				$merchantUsersTable->save ($user);
		  }
		  
		  $this->set ('response', $response);
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
				 ->disableAutoLayout ()
				 ->setTemplate ('ajax');
	 }

    /**
     *
     * delete a user
     *
     */

	 public function userDelete () {

		  $id = $this->request->getData () ['id'];
		  
		  TableRegistry::get ('MerchantUsers')->deleteAll (['id' => $id]);
		  
		  $response = ['status' => 0];
		  $this->set ('response', $response);
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
				 ->disableAutoLayout ()
				 ->setTemplate ('ajax');	 }
	 
    /**
     *
     * password reset sent from user
     *
     */

	 public function lostpw () {

		  if (!empty ($this->request->getData ())) {

				$email = $this->request->getData () ['email'];
				$response = $this->reset ($email);
													 
				$this->set ('response', $response);
				$this->viewBuilder ()
					  ->setLayout ('ajax')
					  ->disableAutoLayout ()
					  ->setTemplate ('ajax');
				
				$this->RequestHandler->respondAs ('json');
				return;
		  }
		  
        $this->viewBuilder ()->setLayout ('lostpw');
	 }

	 /**
     *
     * password reset sent from admin
     *
     */

	 public function adminpw ($email) {

		  $this->reset ($email);
		  $this->set ('response', []);
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
				 ->disableAutoLayout ()
				 ->setTemplate ('ajax');
		  
		  $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**
     *
     * user reset
     *
     */

	 public function resetpw ($resetID = null) {
		  
		  $response = ['status' => 0];
		  
		  if (!empty ($this->request->getData ())) {
				
				$merchantUsersTable = TableRegistry::get ('MerchantUsers');
  				
				$user = $merchantUsersTable->find ()
													->where (['reset_id' => $this->request->getData () ['reset_id']])
													->first ();
				
				if ($user) {

					 $newPassword = $this->request->getData () ['password'];
					 /* 
						 $user ['passwd'] = md5 ($newPassword);
						 $user ['reset_id'] = null;
						 $merchantUsersTable->save ($user);
					  */
					 
					 $merchantUsersTable->updateAll (['passwd' =>md5 ($newPassword),
																 'reset_id' => null],
																['reset_id' => $resetID]);
					 					 
					 $this->set ('response', $response);
					 $this->viewBuilder ()
							->setLayout ('ajax')
							->disableAutoLayout ()
							->setTemplate ('ajax');
					 
					 return;
				}
		  }

		  $this->set ('resetID', $resetID);
        $this->viewBuilder ()->setLayout ('resetpw');	  
	 }
	 
	 /**
     *
     * send a password reset email
     *
     */

	 private function reset ($email) {

		  $response = ['status' => 0,
							'status_text' => __ ('Email sent')];
		  
		  $merchantUsersTable = TableRegistry::get ('MerchantUsers');
		  
		  $user = $merchantUsersTable->find ()
											  ->where (['email' => $email])
											  ->first ();

		  if ($user) {
				
				$mailer = new Mailer ('default');
				$mailer ->setEmailFormat ('html')
						  ->setTo ($email)
						  ->setFrom ('donotreply@myvideoregister.com')
						  ->setSubject ("Reset your VideoRegister password");
				
				$builder = $mailer
								->viewBuilder()
								->setTemplate('register')
								->setLayout('reset');

				// create a unique id for the reset
				
				$resetID = bin2hex (openssl_random_pseudo_bytes (13));
				$user ['reset_id'] = $resetID;
				$merchantUsersTable->save ($user);
				
				$builder->setVars (['resetID' => $resetID]);

				$res = $mailer->deliver ("Send email to merchant");
		  }
		  else {

				$response = ['status' => 1,
								 'status_text' => __ ('User not found in our system')];
		  }

		  
		  return $response;
	 }
	 
	 /**
     *
     * get all the merchant data
     *
     */
    
    private function init ($merchant, $session) {
        
        $buIndex = 0;
        $buMap = [];
        
        $query = TableRegistry::get ('BusinessUnits')
										->find ()
										->order (['id' => 'asc']);

        $this->merchant ['business_units'] = [];
		  $this->merchant ['bu_list'] = [null => __ ('Select store location')];
 		  $this->merchant ['bu_map'] = [];
       
        foreach ($query as $businessUnit) {

				if ($buIndex == 0) {
					 
					 $this->merchant ['bu_list'] [$buIndex] = $businessUnit ['business_name'];
				}
				else {
					 
					 $this->merchant ['bu_list'] [$buIndex] = $businessUnit ['business_name'] . ', ' . $businessUnit ['addr_1'];
				}
				
            $buIndex ++;
            $bus [] = $businessUnit->toArray ();
            
            if (isset ($businessUnit ['params']) && (strlen ($businessUnit ['params']) > 0)) {
                
                $businessUnit ['params'] = json_decode ($businessUnit ['params'], true);
            }

				// set up the bu list for the nav bar
				
            $this->merchant ['business_units'] [] = $businessUnit->toArray ();
				$this->merchant ['bu_names'] [$businessUnit ['id']] = $businessUnit ['business_name'];
        }
        
        $this->debug ('login... ' . $merchant ['uname'] . ' -> ' . $merchant ['merchant_id'] . ' -> ' . $merchant ['role'] . ' -> ' . $buIndex);
		  
        // intitial setup, point to corp
                
        $this->merchant ['bu_index'] = 0;
 		  $this->merchant ['bu_id'] = $this->merchant ['business_units'] [0] ['id'];
		  
        $session->write ('merchant', $this->merchant);
	 }
	 
    private function logMerchant ($log) {
        
        $merchantsLogsTable = TableRegistry::get ('MerchantLogs');

        $log = $merchantsLogsTable->newEntity ($log);
        $merchantsLogsTable->save ($log);
    }
}

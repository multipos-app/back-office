<?php
/**
 *
 * Copyright (C) 2024 VideoRegister, LLC
 * <http://www.videoregister.com>
 *
 * All rights are reserved. No part of the work covered by the copyright
 * hereon may be reproduced or used in any form or by any means graphic,
 * electronic, or mechanical, including photocopying, recording, taping,
 * or information storage and retrieval systems -- without written
 * permission signed by an officer of VideoRegister, LLC.
 *
 */

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class UserController extends AppController {
	 
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
		  
		  if (isset ($_SERVER ["HTTP_USER_AGENT"]) && str_starts_with ($_SERVER ["HTTP_USER_AGENT"], "ELB-HealthChecker")) {
				
				$this->debug ('health check...');
				exit;
		  }

		  $this->viewBuilder ()
				 ->setLayout ('login')
		  		 ->enableAutoLayout ()
				 ->setTemplate ('index');
	 }

	 public function login (...$args) {

		  $this->debug ('user login... ');
		  $this->debug ($args);
		  
		  if (!empty ($this->request->getData ())) {
				
				$query = TableRegistry::get ('MerchantUsers')
											 ->find ()
											 ->where (['uname' => $this->request->getData () ['uname']]);

				
            if ($m = $query->first ()) {
					 					 					 
                if ($m ['passwd'] == md5 ($this->request->getData () ['passwd'])) {

                    $merchant = TableRegistry::get ('Merchants')
															->find ()
															->where (['id' => $m ['merchant_id']])
															->first ();
						  						  
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
						  
                    return $this->redirect (['controller' => 'dashboard', 'action' => 'index']);
					 }
					 else {
						  
						  $this->debug ('bad username or password...');
		  
                    return $this->redirect ('/bad-login');
					 }
				}
		  }
		  return $this->redirect (['controller' => 'dashboard', 'action' => 'index']);
	 }

	 function badLogin () {
		  
		  $this->viewBuilder ()->setLayout ('blank');

		  $this->viewBuilder ()
				 ->setLayout ('blank')
				 ->setTemplatePath ('User')
				 ->setTemplate ('bad_login');
		  
		  $this->debug ('bad login...');
	 }
	 

	 public function fail () {

		  $this->viewBuilder ()
				 ->setLayout ('user')
				 ->enableAutoLayout ()
				 ->setTemplate ('fail');
	 }
	 
	 public function getStarted () {
		  
		  if (!empty ($this->request->getData ())) {

				$contactsTable = TableRegistry::get ('MerchantContacts');
				
				$contact = $this->request->getData ();
				unset ($contact ['g-recaptcha-response']);
				
				$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' .
						 '6LfWUfcqAAAAAJGePvgrUB-gOEtbWu64VaHRViI9' . '&response=' .
						 $contact ['recaptcha'];
				
				$ch = curl_init (); 
		      curl_setopt ($ch, CURLOPT_URL, $url);
		      curl_setopt ($ch, CURLOPT_POST, false);
		      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

		      $response = curl_exec ($ch);      
		      curl_close ($ch);
  
				$contact ['captcha_response'] = json_encode ($response);

				$contact = $contactsTable->newEntity ($contact);
				$contactsTable->save ($contact);
				
				exit;
		  }
		  
        $builder = $this->viewBuilder ()
								->setLayout ('user')
								->enableAutoLayout ()
								->setTemplate ('get_started');
	 }
 
	 public function getStartedResponse () {

		  $this->debug ('get started response... ');
		  
	 	  $builder = $this->viewBuilder ()
								->setLayout ('user')
								->enableAutoLayout ()
								->setTemplate ('get_started_response');
	 }
	 
	 public function forgotPassword () {

		  $this->debug ('enter email...');

        $builder = $this->viewBuilder ()
								->setLayout ('user')
								->enableAutoLayout ()
								->setTemplate ('forgot_password');
	 }

	 public function checkEmail () {

		  $this->debug ("check email...");
		  $this->debug ($this->request->getData ());

		  $status = 1;
		  $response = __ ('No user with that email could be found');

		  if (!empty ($this->request->getData ()) && isset ($this->request->getData () ['email'])) {

				if ($this->email ($this->request->getData () ['email'])) {

					 $email = $this->request->getData () ['email'];
					 $status = 0;
					 $response  = __ ('Password reset sent to ') . $email;

				}
		  }
		  
		  $this->set (['status' => $status, 'response' => $response]);

		  $this->viewBuilder ()
				 ->setLayout ('user')
				 ->enableAutoLayout ()
				 ->setTemplate ('check_email');
	 }

	 /**
	  *
	  * manager requested email reset
	  *
	  */

	 function resetEmail ($email) {

		  $response = ['response' => 1];
		  
		  if ($this->email ($email)) {

				$response = ['response' => 0];
		  }
		  
		  $this->set ('response', $response);
		  $this->RequestHandler->respondAs ('json');
		  $this->render ('ajax');        
		  return $this->response;
	 }

	 /**
	  *
	  * create and send a reset email
	  *
	  */

	 function email ($email) {

		  $status = 1;
		  
		  $merchantUsersTable = TableRegistry::get ('MerchantUsers');

		  $user = $merchantUsersTable->find ()
											  ->where (['email' => $email])
											  ->first ();

		  $this->debug ($user);
		  
		  if ($user) {
				
				// create and save a reset ID
				
				$resetID = bin2hex (openssl_random_pseudo_bytes (13));
				$user ['reset_id'] = $resetID;
				$merchantUsersTable->save ($user);
				
				$builder = $this->viewBuilder ();
				$builder
										  ->setLayout ('ajax')
										  ->disableAutoLayout ()
										  ->setTemplatePath ('User')
										  ->setTemplate ('create_email');
				
				// email link
				
				$resetURL = $this->webroot () . '/user/reset/' . $resetID;
				
				$data = ['resetURL' => $resetURL];
				
				$this->set ($data);
				
				$view = $builder->build ();
				$output = $view->render ();
				
				/* $this->loadComponent ('VrMail');
					$result = $this->VrMail->sendmail ('VideoRegister, password reset', 'noreply@myvideoregister.com', $email, $output);
					$this->debug ($result);

					$this->loadComponent ('Mailgun');
					$result = $this->Mailgun->sendmail ('VideoRegister, password reset', 'noreply@myvideoregister.com', $email, $output);
				 */


				$this->loadComponent ('EMail');
				$result = $this->EMail->sendmail ('VideoRegister, password reset', 'noreply@myvideoregister.com', $email, $output);
				$this->debug ($result);

				return true;
		  }

		  return false;
	 }
	 
	 /**
	  *
	  * lookup user bo reset ID and update the password and clear the reset ID
	  *
	  */
	 
	 public function reset ($resetID) {
		  
		  if (!empty ($this->request->getData ())) {

				$merchantUsersTable = TableRegistry::get ('MerchantUsers');
  				
				$user = $merchantUsersTable->find ()
													->where (['reset_id' => $resetID])
													->first ();
				
				if ($user) {

					 // update password and clear the reset ID
					 
					 $merchantUsersTable->updateAll (['passwd' => md5 ($this->request->getData () ['pw']),
																 'reset_id' => null],
																['reset_id' => $resetID]);
					 

					 $this->set ('response', ['status' => 0]);
					 $this->RequestHandler->respondAs ('json');
					 $this->render ('ajax');        
					 return $this->response;
				}
		  }
		  else {
				
				$this->set (['resetID' => $resetID]);
				
				$builder = $this->viewBuilder ()
									 ->setLayout ('user');
		  }
	 }
	 
	 /**
	  *
	  * lookup user by user ID
	  *
	  */
	 
	 public function edit ($id) {

		  $this->debug ("user edit... $id");
		  
		  $user = null;
		  
		  $merchantUsersTable = TableRegistry::get ('MerchantUsers');
		  
		  if ($id) {
				
				$user = $merchantUsersTable->find ()
													->where (['id' => $id])
													->first ();
		  }
		  else {

				$user = ['id' => 0,
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
											'Canada/Yukon' => 'Yukon',
											'Canada/Vancouver' => 'Canada Pacific']];
		  
		  $this->set ($data);
        
        return ($this->response (__ ('Merchant User'),
                                 'User',
                                 'edit',
                                 $data,
                                 false,
                                 'ajax',
                                 false));

	 }
	 
	 public function update () {
		  
		  if (!empty ($this->request->getData ())) {

				$this->debug ($this->request->getData ());
				$merchantUsersTable = TableRegistry::get ('MerchantUsers');
				$user = null;
				$response = ['status' => 0];
				
				$id = $this->request->getData () ['id'];
				$email = $this->request->getData () ['email'];
				
				if ($id > 0) {

					 $user = $merchantUsersTable->find ()
														 ->where (['id' => $id])
														 ->first ();
					 
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
						  
                    $session = $this->request->getSession ();
						  $merchant = $session->read ('merchant');

						  $this->debug ($merchant);
						  
						  $user = ['merchant_id' => $merchant ['merchant_id'],
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
	 }

	 function subscriptionExpire ($days, $date) {

		  $this->debug ("user subscription expire... $days, $date");
		  
		  $builder = $this->viewBuilder ()
                        ->setLayout ('modal')
                        ->disableAutoLayout ()
                        ->setTemplatePath ('User')
								->setTemplate ('subscription_expire');
		  
	     $data = ['days' => $days,
					  'date' => date ('M d', $date)];
		  
	     $this->set ($data);
		  
		  $response = ['status' => 0,
							'html' => $builder->build ()->render ()];
		  
	     $this->set ('response', $response);
		  $this->RequestHandler->respondAs ('json');
		  $this->render ('ajax');        
		  return $this->response;
	 }
	 
	 function freeTrialEnded () {
		  
		  $this->viewBuilder ()->setLayout ('user')
				 ->setTemplate ('free_trial_ended');
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
		  $this->merchant ['bu_list'] = [];
 		  $this->merchant ['bu_map'] = [];
        
        foreach ($query as $businessUnit) {

				if ($buIndex == 0) {
					 
					 $this->merchant ['bu_list'] [$buIndex] = __ ('All locations');
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
        
        $this->debug ('login... ' . $merchant ['uname'] . ' -> ' . $merchant ['merchant_id'] . ' -> ' . $merchant ['role'] . ' -> ' . $buIndex . ' '. $_SERVER ['HTTP_X_FORWARDED_FOR']);
		  
        // intitial setup, point to corp
        
        $this->merchant ['bu_index'] = 0;
 		  $this->merchant ['bu_id'] = $this->merchant ['business_units'] [0] ['id'];
		  
        $session->write ('merchant', $this->merchant);
	 }
}
?>

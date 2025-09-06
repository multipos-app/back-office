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

class MerchantsController extends AppController {
	 
	 private $merchant;
	 private $utcOffsets = ['America/New_York' => 5,
									'America/Chicago' => 6,
									'America/Denver' => 7,
									'America/Los_Angeles' => 8,
									'America/Anchorage' => 9,
									'HST' => 10,
									'Europe/London' => 0,
									'Europe/Copenhagen' => 23];
	 
	 public function index (...$args) {
		  
		  if (isset ($_SERVER ["HTTP_USER_AGENT"]) && str_starts_with ($_SERVER ["HTTP_USER_AGENT"], "ELB-HealthChecker")) {
				
				$this->debug ('health check...');
				exit;
		  }
		  
		  $this->debug ($this->request->getData ());
		  
		  if (!empty ($this->request->getData ())) {

				$this->ajax ($this->login ($this->request->getData ()));
				return;
		  }

		  $this->set (['width' => 50,
							'message' => '']);
		  
		  $this->viewBuilder ()
				 ->setLayout ('merchants')
		  		 ->enableAutoLayout ()
				 ->setTemplate ('index');
	 }

	 private function login ($args) {
		  
		  $user = TableRegistry::get ('MerchantUsers')
									  ->find ()
									  ->where (['uname' => $args ['username'],
													'passwd' => md5 ($args ['password'])])
									  ->first ();
		  
        if ($user) {
				
            $merchant = TableRegistry::get ('Merchants')
												 ->find ()
												 ->where (['id' => $user ['merchant_id']])
												 ->first ();
				
				$dbname = sprintf ('m_%d', $user ['merchant_id']);
            $session = $this->request->getSession ();
				
				$timezone = 'America/New_York';
				if (isset ($this->merchant ['timezone'])) {
					 
					 $timezone = $this->merchant ['timezone'];
				}
				
            $this->merchant = array_merge ($user->toArray (),
														 ['dbname' => $dbname,
														  'merchant_name' => $merchant ['merchant_name'],
														  'category' => $merchant ['category'],
														  'vendor' => $merchant ['vendor'],
														  'alt_merchant_id' => $merchant ['alt_merchant_id'],
														  'access_token' => $merchant ['access_token'],
														  'vendor_status' => $merchant ['vendor_status'],
														  'registration_id' => $merchant ['registration_id'],
														  'params' => $merchant ['params'],
														  'csrf_token' => $merchant ['csrf_token'],
														  'utc_offset' => sprintf ('%02d:00:00', $this->utcOffsets [$timezone]),
														  'utc_offset_hour' => $this->utcOffsets [$timezone],
														  'subscription_level' => $merchant ['subscription_level'],
														  'mqtt_broker' => 'multipos/' . $merchant ['id'],
														  'alert_params' => $merchant ['alert_params'],
														  'video_provider' => $merchant ['video_provider'],
														  'has_video' => false]);
				

				if (strlen ($merchant ['video_provider'])) {  // pickup video settings
					 
					 $this->merchant = array_merge ($this->merchant,
															  ['has_video' => true,
																'video_retention' => $merchant ['video_retention'],
																'video_provider_account_id' => $merchant ['video_provider_account_id'],
																'video_provider_token' => $merchant ['video_provider_token']]);
				}
				
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
				
            return ['status' => 0];
		  }
		  else {
				
				/* $this->set (['width' => 50,
					'message' => __ ('Bad username or password')]);
					
					$this->viewBuilder ()
					->setLayout ('merchants')
					->enableAutoLayout ()
					->setTemplate ('index');*/
				
            return ['status' => 1,
						  'status_text' => __ ('Bad username or password')];
		  }
	 }
	 
	 public function getStarted () {
		  
		  if (!empty ($this->request->getData ())) {

				$this->debug ($this->request->getData ());
				
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

				$message = '<html><body><h3>' . 
							  '<p>' . $contact ['name'] . '</p>' . 
							  '<p>' . $contact ['email'] .'</p>' . 
							  '<p>' . $contact ['business'] .'</p>' . 
							  '<p>' . $contact ['message'] .'</p>' .
							  '</h3></body></html>';
				
				$this->loadComponent ('EMail');
				$result = $this->EMail->sendmail ('multiPOS contact!', 'noreply@multipos.cloud', 'support@multipos.cloud', $message);

				exit;
		  }
		  
 		  $this->set (['width' => 50]);
        $builder = $this->viewBuilder ()
								->setLayout ('merchants')
								->enableAutoLayout ()
								->setTemplate ('get_started');
	 }
	 
	 public function getStartedResponse () {
		  
		  $this->set (['width' => 50]);
	 	  $builder = $this->viewBuilder ()
								->setLayout ('merchants')
								->enableAutoLayout ()
								->setTemplate ('get_started_response');
	 }
	 
	 public function resetPrompt () {

 		  $this->debug ('reset prompt... ');
		  $this->debug ($this->request->getData ());
		  
		  $this->set (['width' => 50]);
        $builder = $this->viewBuilder ()
								->setLayout ('merchants')
								->enableAutoLayout ()
								->setTemplate ('reset_prompt');
	 }

	 public function checkEmail () {

		  $this->debug ('check email... ');
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

 		  $this->set (['width' => 50]);
		  $this->viewBuilder ()
				 ->setLayout ('merchants')
				 ->enableAutoLayout ()
				 ->setTemplate ('check_email');
	 }

	 /**
	  *
	  * manager requested email reset
	  *
	  */

	 public function resetEmail ($email) {

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
		  
		  $this->debug ("email... $email");

		  $merchantUsersTable = TableRegistry::get ('MerchantUsers');

		  $user = $merchantUsersTable->find ()
											  ->where (['uname' => $email])
											  ->first ();
		  
		  $this->debug ($user);
		  
		  if ($user) {
				
				// create and save a reset ID
				
				$resetID = bin2hex (openssl_random_pseudo_bytes (13));
				$user ['reset_id'] = $resetID;
				$merchantUsersTable->updateAll (['reset_id' => $resetID],
														  ['uname' => $email]);
				
				$builder = $this->viewBuilder ();
				$builder->setLayout ('ajax')
						  ->disableAutoLayout ()
						  ->setTemplatePath ('Merchants')
						  ->setTemplate ('create_email');
				
				// email link
				
				$resetURL = $_SERVER ['HTTP_ORIGIN'] . '/merchants/reset/' . $resetID;
				
				$data = ['resetURL' => $resetURL];
				
				$this->set ($data);
				
				$view = $builder->build ();
				$output = $view->render ();

				$this->loadComponent ('EMail');
				$result = $this->EMail->sendmail ('VideoRegister, password reset', 'noreply@myvideoregister.com', $email, $output);

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

		  $statusText = '';
		  
		  if (!empty ($this->request->getData ()) && isset ($this->request->getData () ['reset_id'])) {
				
				$merchantUsersTable = TableRegistry::get ('MerchantUsers');
				
				$resetID = $this->request->getData () ['reset_id'];
				$user = $merchantUsersTable->find ()
													->where (['reset_id' => $resetID])
													->first ();
				
				$this->debug ($this->request->getData () ['reset_id']);
				$this->debug ($user);
				
				if ($user) {

					 // update password and clear the reset ID
					 
					 $merchantUsersTable->updateAll (['passwd' => md5 ($this->request->getData () ['pw1']),
																 'reset_id' => null],
																['reset_id' => $resetID]);

					 return $this->redirect('/');
				}
				else {
					 
					 $statusText = __ ('An error occoured, contact <a href="mailto:support@videoregister.com">videoregister support</a>.');
				}
		  }

		  $this->debug ("reset... $resetID");
		  
		  $this->set (['width' => 50,
							'resetID' => $resetID,
							'status_text' => $statusText]);
		  
		  $this->viewBuilder ()
				 ->setLayout ('merchants')
				 ->enableAutoLayout ()
				 ->setTemplate ('reset');
	 }
	 

	 function subscriptionExpire ($days, $date) {
		  
		  $builder = $this->viewBuilder ()
                        ->setLayout ('modal')
                        ->disableAutoLayout ()
                        ->setTemplatePath ('Merchants')
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
	 
	 /**
     *
     * start a free trial
     *
     */

	 function freeTrial () {

		  $this->debug ('free trial... ');
		  $this->debug ($this->request->getData ());
		  
		  // $99 https://checkout.square.site/merchant/ML0NRPZ6JY7BG/checkout/YYSJPXZBQS4BG4B3W6GWFR4H
		  // $1  https://www.videoregister.com/product/videoregister-monthly-subscription/4?cs=true&cst=custom

		  if (!empty ($this->request->getData ())) {
				
				$post = $this->request->getData ();
				
				$this->debug ($post);

				$merchantsTable = TableRegistry::get ('Merchants');
				$merchant = $merchantsTable->find ()
													->where (['email' => $post ['email']])
													->first ();

				if ($merchant) {

					 $this->set (['width' => 50]);
					 $this->viewBuilder ()
							->setLayout ('merchants')
							->enableAutoLayout ()
							->setTemplate ('merchant_exists');
					 return;
				}
				
				// validate recaptcha against google

				$siteVerify = ['secret' => '6LfGGVAqAAAAADauIzJHWVH9d2HzjLioj-WeuCmH',
									'response' => $post ['g-recaptcha-response']];

				$this->debug ($siteVerify);
				
				$siteVerify = json_encode ($siteVerify);
				
				$headers = ['Content-type: Application/json',
								'Content-length: ' . strlen ($siteVerify)];
				
				$url = 'https://www.google.com/recaptcha/api/siteverify';
				$curl = curl_init ();
				curl_setopt ($curl, CURLOPT_URL, $url);
				curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
				curl_setopt ($curl, CURLOPT_HTTPGET, false);
				curl_setopt ($curl, CURLOPT_POSTFIELDS, $siteVerify);
				curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
				
				$googleResult = json_decode (curl_exec ($curl), true);

				$merchant = null;
				
				// check if merchant exists
				
				if ($merchant) {

					 if ($merchant ['subscription_level'] == 'free_trial') {

						  $expire = time () - strtotime ($merchant ['create_time']);
						  if ($expire < TRIAL_PERIOD) {

								// free trial still active, go to login
								
								$this->viewBuilder ()->setLayout ('merchants')
									  ->setTemplate ('index');
						  }
						  else {

								// free trial has expired
								
								$this->viewBuilder ()->setLayout ('merchants')
									  ->setTemplate ('free_trial_ended');
						  }
						  return;
					 }
					 
					 $this->debug ('Merchant exists... ' . $post ['email']);
					 $this->viewBuilder ()
							->setLayout ('merchants')
							->enableAutoLayout ()
							->setTemplate ('merchant_exists');
					 return;
				}
				
				$registrationID = bin2hex (openssl_random_pseudo_bytes (13));
				$csrfToken = bin2hex (openssl_random_pseudo_bytes (13));
				
				$merchant = $merchantsTable->newEntity (['refresh_flag' => 1,
																	  'registration_id' => $registrationID,
																	  'csrf_token' => $csrfToken,
																	  'merchant_name' => $post ['merchant_name'],
																	  'email' => $post ['email'],
																	  'phone_1' => preg_replace ('/\(|\)|\s+|\-/', '', $post ['phone']),
																	  'passwd' => md5 ($post ['pw1']),
																	  'fname' => $post ['fname'],
																	  'lname' => $post ['lname'],
																	  'merchant_type' => 'square',
																	  'category' => 'vr',
																	  'vendor' => 'square',
																	  'vendor_status' => 'unknown',
																	  'subscription_level' => 'free_trial',
																	  'status' => 0]);
				
				$merchant = $merchantsTable->save ($merchant);
				
				$builder = $this->viewBuilder ()
									 ->setLayout ('ajax')
									 ->disableAutoLayout ()
									 ->setTemplatePath ('Merchants')
									 ->setTemplate ('free_trial_init');
				
				$data = ['registrationID' => $registrationID,
							'link' => 'https://' . $_SERVER ['HTTP_HOST'] . '/square/verify/' . $registrationID];
				
				$builder->setVars ($data);		  
				$view = $builder->build ();
				$output = $view->render ();
				
				$this->loadComponent ('EMail');
				$result = $this->EMail->sendmail ('VideoRegister, free trial', 'noreply@myvideoregister.com', $post ['email'], $output);

				$this->debug ($result);
				
				$this->set (['width' => 50]);
				$this->viewBuilder ()
					  ->setLayout ('merchants')
					  ->enableAutoLayout ()
					  ->setTemplatePath ('Merchants')
					  ->setTemplate ('free_trial_submitted');
				
				return;
		  }

		  $this->set (['width' => 100]);
		  
		  $this->viewBuilder ()
				 ->setLayout ('merchants')
				 ->enableAutoLayout ()
				 ->setTemplate ('free_trial');
	 }

	 function freeTrialEnded () {
		  
		  $this->set (['width' => 50]);
		  
		  $this->viewBuilder ()->setLayout ('merchants')
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

		  $this->debug ($merchant);
		  
        $this->debug ('login... ' .
							 $merchant ['uname'] . ' -> ' .
							 $merchant ['merchant_id'] . ' -> ' .
							 $merchant ['role'] . ' -> ' .
							 $buIndex . ' '.
							 $_SERVER ['HTTP_X_FORWARDED_FOR']);
		  
        // intitial setup, point to corp
        
        $this->merchant ['bu_index'] = 0;
 		  $this->merchant ['bu_id'] = $this->merchant ['business_units'] [0] ['id'];

		  // get a device map

		  $this->merchant ['devices'] = [];
		  $devices = TableRegistry::get ('Devices')
										  ->find ()
										  ->all ();
		  
		  foreach ($devices as $device) {

				$this->merchant ['devices'] [$device ['id']] = $device ['device_name'];
		  }

        $session->write ('merchant', $this->merchant);
	 }
}
?>

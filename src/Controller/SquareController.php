<?php
/**********************************************************************************************
 *
 * Copyright (C) 2024 VideoRegister, LLC
 * <http://www.VideoRegister.com>
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
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Event\EventInterface;
use Cake\Mailer\Mailer;
use Cake\Event\Event;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';
require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'secrets.php';

class SquareController extends PosAppController {

	 private $clientID = SQUARE_CLIENT_ID;
    private $clientSecret = SQUARE_CLIENT_SECRET;

    public function beforeFilter (EventInterface $event) { }
    public function initialize (): void {
        
        parent::initialize ();
    }

	 /**********************************************************************************************
	  *
	  * index
	  *
	  *********************************************************************************************/

	 function index (...$args) {

		  parent::beforeFilter (new Event ('square'));

		  $data = ['merchantID' => $this->merchant ['merchant_id']];

		  switch ($this->merchant ['vendor_status']) {

				case 'access_granted':

					 $data ['revokeAccess'] = true;
					 $data ['oauth'] = null;
					 break;

				case 'unknown':
				case 'access_revoked':
					 
					 $data ['revokeAccess'] = false;
					 $data ['oauth'] = $this->oauth () . $this->merchant ['csrf_token'];
		  }

		  // switch to the merchant db for users
		  
		  $this->dbconnect (MERCHANT_DB);
		  $user = [];
		  $query = TableRegistry::get ('MerchantUsers')
										->find ()
										->where (['merchant_id' => $this->merchant ['merchant_id']])
										->select (['id', 'uname', 'email', 'fname', 'lname', 'role'])
										->all ();
		  
		  foreach ($query as $user) {
				
				$users [] = $user;
		  }

		  $data ['users'] = $users;
		  $data ['merchant'] = $this->merchant;
		  $this->set ($data);
	 }
	 
	 /**********************************************************************************************
	  *
	  * if a valid merchant, send to square to grant access, square will redirect
	  *
	  *********************************************************************************************/
	 
    function verify ($registrationID) {

		  $this->debug ("square verify... $registrationID");
		  
		  $this->dbconnect (MERCHANT_DB);
		  
		  $merchantsTable = TableRegistry::get ('Merchants');
		  $merchant = $merchantsTable->find ()
											  ->where (['registration_id' => $registrationID])
											  ->first ();
		  
		  $this->debug ($merchant);
		  
		  if ($merchant) {

				$merchantUser =  null;
				
				if ($merchant ['subscription_level'] == 'free_trial') {

					 $merchant ['merchant_name'] = $merchant ['merchant_name'];
					 $merchant ['primary_contact'] = $merchant ['fname'] . ' ' . $merchant ['lname'];
					 $merchant ['passwd'] = $merchant ['passwd'];
					 $merchant ['timezone'] = $merchant ['timezone'];
					 $merchant ['status'] = 1;
					 $merchant ['csrf_token'] = bin2hex (openssl_random_pseudo_bytes (13));
					 $merchant ['subscription_key'] = bin2hex (openssl_random_pseudo_bytes (13));
				}
				else if ($this->request->getData ()) {

					 $this->debug ($this->request->getData ());
					 
					 $merchant ['merchant_name'] = $this->request->getData () ['merchant_name'];
					 $merchant ['primary_contact'] = $this->request->getData () ['fname'] . ' ' . $this->request->getData () ['lname'];
					 $merchant ['passwd'] = md5 ($this->request->getData () ['pw1']);
					 $merchant ['timezone'] = $this->request->getData () ['timezone'];
					 $merchant ['status'] = 1;
					 $merchant ['csrf_token'] = bin2hex (openssl_random_pseudo_bytes (13));
					 $merchant ['subscription_key'] = bin2hex (openssl_random_pseudo_bytes (13));
				}
				
				$merchantsTable->save ($merchant);
				
				$merchantUsersTable = TableRegistry::get ('MerchantUsers');
				
				$merchantUser = $merchantUsersTable->newEntity (['merchant_id' => $merchant ['id'],
																				 'uname' => $merchant ['email'],
																				 'email' => $merchant ['email'],
																				 'passwd' => $merchant ['passwd'],
																				 'timezone' => $merchant ['timezone'],
																				 'role' => 'admin']);
				
				$merchantUsersTable->save ($merchantUser);
				
				// start the square oauth...
				
				$data = ['status' => 0,
							'width' => 100,
							'merchant' => $merchant,
							'oauth' => $this->oauth () . $merchant ['csrf_token']];
				
				$this->set ($data);
				
				$this->viewBuilder ()
					  ->setLayout ('merchants')
					  ->setTemplate ('square');
				
				return;
		  }
		  else if ($merchant ['status'] > 0) {
				
				$this->viewBuilder ()
					  ->setLayout ('login')
					  ->setTemplate ('login');
				
				return;
		  }

		  // paid subscription prompt for business info and password
		  
		  $this->set ($data);
		  $this->viewBuilder ()
				 ->setLayout ('verify');
	 }
	 
	 /**********************************************************************************************
	  *
	  * redirect endpoint for square oauth, get the registration id from the result passed in 
	  * connect.squareup.com/oauth2/authorize
	  *
	  *********************************************************************************************/
	 
    function redir () {
        
		  $this->debug ('square redir...');
		  $this->debug ($_SERVER);

		  $this->set ('result', ['status'=> 1,
                               'status_text' => '']);
		  $merchant = null;
		  
		  $url = parse_url ($_SERVER ['REQUEST_URI'], PHP_URL_QUERY);
		  parse_str ($url, $params);

		  $this->debug ($url);
		  $this->debug ($params);

		  if (isset ($params ['state'])) {

				// check the csrf token
				
				$this->dbconnect (MERCHANT_DB);
				$merchantsTable = TableRegistry::get ('Merchants');
				
				$merchant = $merchantsTable
												 ->find ()
												 ->where (['csrf_token' => $params ['state']])
												 ->first ();
				if (!$merchant) {

					 // some error...
					 
					 $this->error ("error in square redir, merchant not found... " . $url);
					 return;
				}
		  }
		  else {
				
				$this->error ("error in square redir, state not set... " . $url);
				return;
		  }
		  
		  // create a new csrf token, square wants a new one for each token
		  
		  /* $merchant ['csrf_token'] = bin2hex (openssl_random_pseudo_bytes (13));
			  $merchantsTable->save ($merchant);*/
		  
		  if (isset ($params ['error']) && ($params ['error'] == 'access_denied')) {
				
				$this->set (['oauth' => $this->oauth (). $merchant ['csrf_token']]);
				$this->viewBuilder ()
					  ->setLayout ('denied');				
				return;
		  }
		  
		  $csrfToken = $params ['state'];

		  $result = ['access_token' => '',
						 'token_type' => '',
						 'merchant_id' => '',
						 'refresh_token' => '',
						 'access_granted' => false];
		  
		  if (isset ($params ['code'])) {
				
				$url = 'https://connect.squareup.com/oauth2/token';
				$post = json_encode (["grant_type" => "authorization_code",
											 "client_id" => $this->clientID,
											 "client_secret" => $this->clientSecret,
											 "code" => $params ['code']], JSON_PRETTY_PRINT);
				
				$headers = ['Square-Version: 2022-10-19',
								'Content-type: Application/json',
								'Content-length: ' . strlen ($post)];
				
				$curl = curl_init ();
				
				curl_setopt ($curl, CURLOPT_URL, $url);
				curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
				curl_setopt ($curl, CURLOPT_HTTPGET, false);
				curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
				curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
				
				$result = json_decode (curl_exec ($curl), true);
				
				$this->debug ('oauth token result...');
				$this->debug ($result);
				
				$resultText = __ ('Authorization Failed');

				$this->debug ('access allowed... ');
				$this->debug ($merchant);
				
				if (isset ($result ['access_token'])) {

					 $result ['access_granted'] = true;
					 
					 $this->loadComponent ('SquareTools');
					 
					 switch ($merchant ['vendor_status']) {

						  case 'access_revoked':
								
								$this->debug ('access granted...');
								
								$merchant ['status'] = 1;
								$merchant ['vendor_status'] = 'access_granted';
								$merchant ['alt_merchant_id'] = $result ['merchant_id'];
								$merchant ['params'] = $this->SquareTools->encrypt (json_encode ($result), $merchant ['registration_id']);
								$merchant ['video_provider'] = 'camcloud';
								$merchantsTable->save ($merchant);
								break;

						  case 'unknown':

								// new merchant
								
								$this->debug ('new mechant... ' . $merchant ['id'] . ' ' . $merchant ['email']);

								$this->merchant ($merchant, $result);
								break;
					 }
				}
		  }

		  if ($merchant ['category'] == 'export') {

				$this->debug ('redir export...');
				return $this->redirect ('https://export.myvideoregister.com');
		  }
		  
		  if ($merchant ['subscription_level'] == 'free_trial') {
				
				$this->debug ('redir free trial...');
				return $this->redirect ('https://myvideoregister.com');
		  }

		  $this->viewBuilder ()
				 ->setLayout ('login')
				 ->setTemplate ('login');
	 } 
	 
	 /**********************************************************************************************
	  *
	  * initialize a new merchant
	  *
	  * create databse
	  * add locations
	  *
	  *********************************************************************************************/
	 
    private function merchant ($merchant, $result) {
		  
        $this->dbconnect (MERCHANT_DB);
		  
		  // encrypt and save tokens

		  if ($result ['access_granted']) {
				
				$merchant ['status'] = 1;
				$merchant ['vendor_status'] = 'access_granted';
				$merchant ['alt_merchant_id'] = $result ['merchant_id'];
				$merchant ['params'] = $this->SquareTools->encrypt (json_encode ($result), $merchant ['registration_id']);
				$merchant ['video_provider'] = 'camcloud';
		  }
		  else {

				$merchant ['vendor_status'] = 'access_revoked';
		  }
		  
		  TableRegistry::get ('Merchants')->save ($merchant);
		  
		  $createDB = getcwd () . "/../sql/m_0.sql";
		  
        // create database and add business units from square

		  if (file_exists ($createDB)) {

				$sql = file_get_contents ($createDB);
				
				$connection = ConnectionManager::get ('default');
				$dbname = 'm_' . $merchant ['id'];
				
				$connection->execute ("create database $dbname");

				// connect to new db and add business units
				
				$this->dbconnect ($dbname);
				$connection = ConnectionManager::get ('default');

				if ($connection->execute ($sql)) {

					 // get basic business info and locations

					 $merchantName = '';

					 if ($result ['access_granted']) {

						  $this->debug ('access granted... ');
						  $this->debug ($merchant);
						  
						  $squareMerchant = $this->get ($result ['access_token'], 'merchants');
						  $squareLocations = $this->get ($result ['access_token'], 'locations');
						  
						  $merchantName = $squareMerchant ['merchant'] [0] ['business_name'];

						  $buTable = TableRegistry::get ('BusinessUnits');

						  // create main business unit

						  $bu = $buTable->newEntity (['business_unit_id' => 0,
																'business_type' => 1,
																'business_name' => $merchantName,
																'locale' => 'us_US',
																'phone_1' => $merchant ['phone_1'],
																'timezone' => $merchant ['timezone']]);
						  
						  $bu = $buTable->save ($bu);
						  $parentID = $bu ['id'];
						  
						  // get the locations from square and add them as business units
						  
						  $this->locations ($result ['access_token'], $parentID, $buTable);

						  // get the items and departments from square
						  
						  // $this->items ($result ['access_token']);
					 }

					 if ($merchant ['subscription_level'] == 'free_trial') {

						  // create a customer in the videoregister merchant database
						  
						  $this->dbconnect ("m_1");
						  $customersTable = TableRegistry::get ('Customers');
						  $customer = $customersTable->newEntity (['business_name' => $merchant ['merchant_name'],
																				 'name' => $merchant ['primary_contact'],
																				 'email' => $merchant ['email'],
																				 'phone' => $merchant ['phone_1'],
																				 'mobile' => $merchant ['phone_1']]);

						  $customersTable->save ($customer);
					 }
				}
		  }
		  
		  return true;
	 }

	 /**********************************************************************************************
	  *
	  * get new access tokens for all merchants
	  *
	  *********************************************************************************************/
	 
	 function refreshToken ($merchantID) {
		  
        $this->dbconnect ("merchants");
		  
		  $response = ['status' => 1,
							'merchant_id' => $merchantID,
							'status_text' => 'refresh, unknown error'];
		  
		  $merchantsTable = TableRegistry::get ('Merchants');
		  $merchant = $merchantsTable
											->find ()
											->where (['id' => $merchantID])
											->first ();
		  
		  if ($merchant) {
				
				$this->loadComponent ('SquareTools');

				$refreshToken = false;

				if ($merchant ['params'] != null) {
					 
					 $params = json_decode ($this->SquareTools->decrypt ($merchant ['params'], $merchant ['registration_id']), true);
					 $refreshToken = $params ['refresh_token'];

				}
				else if ($merchant ['refresh_token'] != null) {
					 
					 $refreshToken = $merchant ['refresh_token'];
				}
				
				if ($refreshToken) {
					 
					 $post = json_encode (["client_id" => $this->clientID,
												  "client_secret" => $this->clientSecret,
												  "grant_type" => "refresh_token",
												  "refresh_token" => $refreshToken]);

					 $this->debug ($post);
					 $tokenResult = null;
					 
					 $headers = ['Square-Version: 2022-10-19',
									 'Content-type: Application/json'];
					 
					 $url = 'https://connect.squareup.com/oauth2/token';
					 $curl = curl_init ();
					 curl_setopt ($curl, CURLOPT_URL, $url);
					 curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
					 curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
					 curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					 curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
					 curl_setopt ($curl, CURLOPT_HTTPGET, false);
					 curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
					 curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
					 
					 $tokenResult = json_decode (curl_exec ($curl), true);
					 
					 if ($tokenResult && isset ($tokenResult ['access_token'])) {
						  
						  $this->debug ($tokenResult);
						  
						  $merchantsTable->updateAll (['params' => $this->SquareTools->encrypt (json_encode ($tokenResult), $merchant ['registration_id']),
																 'token_refresh_time = current_timestamp'],
																['id' => $merchantID]);
						  
						  $response = ['status' => 0,
											'merchant_id' => $merchantID,
											'status_text' => 'success'];
					 }
					 else {
						  
						  $this->error ('refresh failed... ' . $merchantID);
						  $this->error ($tokenResult);
						  
						  $response = ['status' => 2,
											'merchant_id' => $merchantID,
											'status_text' => 'refresh, square error'];
					 }
				}
				else {

					 $this->error ('refresh failed... ' . $merchant ['merchant_name'] . ' ' , $merchant ['id']);
					 $response = ['status' => 2,
									  'merchant_id' => $merchantID,
									  'status_text' => 'refresh, token not found'];
				}
		  }
		  else {

				$this->error ('refresh failed, unknown merchant... ' . $merchantID);
				$response = ['status' => 2,
								 'merchant_id' => $merchantID,
								 'status_text' => 'refresh, unknown merchant'];
				
		  }
		  
		  $this->loadComponent ('EMail');
		  
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Square')
								->setTemplate ('refresh_status');
		  
		  $builder->setVars (['response' => json_encode ($response, JSON_PRETTY_PRINT)]);
		  
		  $view = $builder->build ();
		  $output = $view->render ();
		  
		  // send account action email
		  
		  $view = $builder->build ();
		  $result = $this->EMail->sendmail ("refresh status $merchantID", 'refresh@myvideoregister.com', 'qolson@posappliance.com', $output);

		  $this->set ("response", $response);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('refresh_token');
		  
        $this->RequestHandler->respondAs ('json');
	 }

	 /**********************************************************************************************
	  *
	  * refresh all tokens
	  *
	  *********************************************************************************************/

	 public function refreshAllTokens () {

		  $this->debug ("refreshAllTokens command....");
		  
		  $this->dbconnect (MERCHANT_DB);
		  
		  $query = TableRegistry::get ('Merchants')
										->find ()
										->where (['refresh_flag = 1'])
										->all ();
		  
		  foreach ($query as $merchant) {

				$this->debug ($merchant);
				$this->refreshToken ($merchant ['id']);
		  }	  
	 }
	 
	 /**********************************************************************************************
	  *
	  * prompt to revoke
	  *
	  *********************************************************************************************/
	 
	 public function promptAccess ($merchantID) {

		  parent::beforeFilter (new Event ('square'));
		  
		  $data = ['merchantID' => $merchantID];
		  
		  return ($this->response (__ ('Square'),
											'Square',
											'prompt_access',
											$data));	
		  
	 }
	 /**********************************************************************************************
	  *
	  * revoke access
	  *
	  *********************************************************************************************/
	 
	 public function revokeAccess ($merchantID) {

		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'square.php';

		  parent::beforeFilter (new Event ('square'));
		  $this->loadComponent ('SquareTools');
		  
		  $params = json_decode ($this->SquareTools->decrypt ($this->merchant ['params'], $this->merchant ['registration_id']), true);

		  $url = 'https://connect.squareup.com/oauth2/revoke';

		  $post = json_encode (["access_token" => $params ['access_token'],
										"client_id" => $this->clientID,
										"revoke_only_access_token" => false]);
		  
		  $headers = ['Square-Version: 2023-10-18',
						  'Content-type: Application/json',
						  'Authorization: Client ' . $clientSecret,
						  'Content-length: ' . strlen ($post)];
		  
		  $this->debug ($headers);
		  $this->debug ($post);

		  $curl = curl_init ();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
		  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		  curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
		  curl_setopt ($curl, CURLOPT_HTTPGET, false);
		  curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
		  curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);

		  $result = json_decode (curl_exec ($curl), true);
		  $this->debug ($result);

		  if (isset ($result ['success'])) {
				
				$this->dbconnect ("merchants");
				
				$merchantsTable = TableRegistry::get ('Merchants');
				$merchant = $merchantsTable->find ()
													->where (['id' => $merchantID])
													->first ();

				$this->debug ($merchant);

				$merchant ['access_token'] = null;
				$merchant ['refresh_token'] = null;
				$merchant ['params'] = null;
				$merchant ['vendor_status'] = 'access_revoked';
				$merchant ['refresh_flag'] = 0;
				
				$merchantsTable->save ($merchant);

				$this->debug ($merchant);
		  }
		  
		  $this->debug ("revoke... $merchantID");
		  
		  $this->set ('response', ['status' => 0]);
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
				 ->disableAutoLayout ()
				 ->setTemplate ('ajax');
		  
		  $this->RequestHandler->respondAs ('json');
	 }

	 /**********************************************************************************************
	  *
	  * check for free trial expire
	  *
	  *********************************************************************************************/
	 
	 private function freeTrials () {

		  // expire in 10 days
		  
		  $where = ['subscription_level' => 'free_trial',
						'status' => 1,
						'create_time < curdate() - interval 20 day',
						'create_time > curdate() - interval 21 day'];
		  
		  $merchantsTable = TableRegistry::get ('Merchants');
		  
		  $merchant = $merchantsTable->find ()
											  ->where ($where)
											  ->all ();

		  foreach ($merchant as $merchant) {

				$this->debug ($merchant);
		  }

		  // final notice
		  
		  $where = ['subscription_level' => 'free_trial',
						'status' => 1,
						'create_time < curdate() - interval 30 day',
						'create_time > curdate() - interval 31 day'];

		  // expired, 35 days
		  
		  $where = ['subscription_level' => 'free_trial',
						'status' => 1,
						'create_time < curdate() - interval 35 day'];

		  
	 }
	 
	 /**********************************************************************************************
	  *
	  * import items and locations, get the encrypted access token and 
	  * invoke items and locations
	  *
	  *********************************************************************************************/
	 
    function update () {

		  $locations = 0;
		  $items = 0;
		  $employees = 0;

		  parent::beforeFilter (new Event ('square'));
		  $this->loadComponent ('SquareTools');
		  
		  $params = json_decode ($this->SquareTools->decrypt ($this->merchant ['params'], $this->merchant ['registration_id']), true);

		  $buTable =  TableRegistry::get ('BusinessUnits');
		  $parentBU = $buTable->find ()
									 ->where (['business_type' => BU_CORP])
									 ->first ();
		  
		  if ($parentBU) {
				
				$locations = $this->locations ($params ['access_token'], $parentBU ['id'], $buTable);
		  }
		  
		  $items = $this->items ($params ['access_token'], $parentBU ['id'], $buTable);
		  $employees = $this->employees ($params ['access_token']);
		  
        $this->set ('response', ['status' => 0,
											'item_count' => $items,
											'location_count' => $locations,
											'employee_count' => $employees]);
		  
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**********************************************************************************************
	  *
	  * import/update items
	  *
	  *********************************************************************************************/
	 
    private function items ($accessToken) {

		  parent::beforeFilter (new Event ('square'));
		  $this->loadComponent ('SquareTools');

		  $itemCount = 0;
		  
		  $catalog = $this->get ($accessToken, 'catalog/list/');
		  $itemCount = 0;
		  
		  if (!$catalog) {
		  }
		  else {
				
				if (isset ($catalog ['objects']) && (count ($catalog ['objects']) > 0)) {
					 
					 $departmentsTable = TableRegistry::get ('Departments');
					 $itemsTable = TableRegistry::get ('Items');
					 $itemPricesTable = TableRegistry::get ('ItemPrices');

					 $departments = [];
					 foreach ($catalog ['objects'] as $obj) {

						  switch ($obj ['type']) {

								case 'CATEGORY':

									 $department = $departmentsTable
                                ->find ()
                                ->where (['alt_department_id' => $obj ['id']])
                                ->first ();

									 if (!$department) {

										  $department = $departmentsTable->newEntity (['alt_department_id' => $obj ['id'],
																									  'department_desc' => strtoupper ($obj ['category_data'] ['name'])]);
										  
										  $department = $departmentsTable->save ($department);

										  $departments [$obj ['id']] = $department ['id'];
									 }
									 break;
									 
 								case 'MODIFIER_LIST':
									 
									 $department = $departmentsTable
								          ->find ()
								          ->where (['alt_department_id' => $obj ['id']])
								          ->first ();
									 
									 if (!$department) {
										  
										  $department = $departmentsTable->newEntity (['alt_department_id' => $obj ['id'],
																									  'department_desc' => strtoupper ($obj ['modifier_list_data'] ['name'])]);
										  
										  $department = $departmentsTable->save ($department);
										  $departments [$obj ['id']] = $department ['id'];
									 }
						  }
					 }
					 
					 foreach ($catalog ['objects'] as $obj) {
						  
						  $this->debug ($obj);

						  switch ($obj ['type']) {

								case 'ITEM':

									 $itemCount ++;
									 
									 $item = $itemsTable
                                ->find ()
                                ->where (['alt_item_id' => $obj ['item_data'] ['variations'] [0] ['id']])
                                ->first ();

									 if (!$item) {

										  $departmentID = 0;
										  if (isset ($obj ['item_data'] ['category_id'])) {
												
												$departmentID = $departments [$obj ['item_data'] ['category_id']];
										  }

										  $sku = $obj ['item_data'] ['variations'] [0] ['item_variation_data'] ['item_id'];
										  if (isset ($obj ['item_data'] ['variations'] [0] ['item_variation_data'] ['sku'])) {

												$sku = $obj ['item_data'] ['variations'] [0] ['item_variation_data'] ['sku'];
										  }
										  
										  
										  $item = $itemsTable->newEntity (['alt_item_id' => $obj ['item_data'] ['variations'] [0] ['id'],
																					  'sku' => $sku,
																					  'item_desc' => strtoupper ($obj ['item_data'] ['name']),
																					  'department_id' => $departmentID,
																					  'enabled' => 1]);
										  
										  $item = $itemsTable->save ($item);

										  $price = 0.0;
										  if (isset ($obj ['item_data'] ['variations'] [0] ['item_variation_data'] ['price_money'])) {

												$price = floatval ($obj ['item_data'] ['variations'] [0] ['item_variation_data'] ['price_money'] ['amount']) / 100.0;
										  }
										  
										  $itemPrice = $itemPricesTable->newEntity (['item_id' => $item ['id'],
																									'price' => $price]);
										  
										  $itemPrice = $itemPricesTable->save ($itemPrice);
									 }
									 break;

								case 'MODIFIER_LIST':

									 $name = $obj ['modifier_list_data'] ['name'];
									 
									 foreach ($obj ['modifier_list_data'] ['modifiers'] as $modifier) {
										  
										  $item = $itemsTable
                                ->find ()
                                ->where (['alt_item_id' => $modifier ['id']])
                                ->first ();
										  
										  if (!$item) {

												$departmentID = 0;
												if (isset ($modifier ['modifier_data'] ['modifier_list_id'])) {
													 
													 $departmentID = $departments [$modifier ['modifier_data'] ['modifier_list_id']];
												}
												
												$item = $itemsTable->newEntity (['alt_item_id' => $modifier ['id'],
																							'sku' => $modifier ['id'],
																							'item_desc' => strtoupper ($modifier ['modifier_data'] ['name']),
																							'department_id' => $departmentID,
																							'enabled' => 1]);
												
												$item = $itemsTable->save ($item);

												$price = 0.0;
												if (isset ($modifier ['modifier_data'] ['price_money'])) {

													 $price = floatval ($modifier ['modifier_data'] ['price_money'] ['amount']) / 100.0;
												}
												
												$itemPrice = $itemPricesTable->newEntity (['item_id' => $item ['id'],
																										 'price' => $price]);
												
												$itemPrice = $itemPricesTable->save ($itemPrice);
										  }
									 }
						  }
					 }
				}
		  }
		  return $itemCount;
	 }
	 
	 /**********************************************************************************************
	  *
	  * import/update locations
	  *
	  *********************************************************************************************/
	 
    private function locations ($accessToken, $parentID, $buTable) {

		  $locationsCount = 0;
		  $locations = $this->get ($accessToken, 'locations');
		  
		  $this->debug ($locations);

		  foreach ($locations ['locations'] as $location) {
				

				$bu = $buTable->find ()
								  ->where (['alt_id' => $location ['id']])
								  ->first ();
				
				if ($bu) {
					 
					 $bu ['business_name'] = $location ['name'];
					 $bu ['addr_1'] = $location ['address'] ['address_line_1'];
					 $bu ['city'] =  $location ['address'] ['locality'];
					 $bu ['state'] = $location ['address'] ['administrative_district_level_1'];
					 $bu ['postal_code'] = $location ['address'] ['postal_code'];
					 $bu ['timezone'] = $location ['timezone'];
					 
					 $this->debug ($bu);
				}
				else {
					 
					 $bu = $buTable->newEntity (['business_unit_id' => $parentID,
														  'alt_id' => $location ['id'],
														  'business_type' => 2,
														  'business_name' => $location ['name'],
														  'addr_1' => $location ['address'] ['address_line_1'],
														  'city' => $location ['address'] ['locality'],
														  'state' => $location ['address'] ['administrative_district_level_1'],
														  'postal_code' => $location ['address'] ['postal_code'],
														  'locale' => 'us_US',
														  'timezone' => $location ['timezone']]);
				}
				
				$buTable->save ($bu);
				$locationsCount ++;
		  }
		  
		  return $locationsCount;
	 }
	 
	 /**********************************************************************************************
	  *
	  * import/update items
	  *
	  *********************************************************************************************/
	 
    private function employees ($accessToken) {

		  $employeeCount = 0;
		  $result = $this->get ($accessToken, 'employees');
		  

		  if (isset ($result ['employees'])) {
				
				$employeesTable = TableRegistry::get ('Employees');

				foreach ($result ['employees'] as $e) {
					 
					 $this->debug ($e);

					 $employee = $employeesTable->find ()
														 ->where (['alt_id' => $e ['id']])
														 ->first ();

					 $employeeCount ++;
					 if (!$employee) {

						  $employeesTable->save ($employeesTable->newEntity (['alt_id' => $e ['id'],
																								'email' => $e ['email'],
																								'fname' => $e ['first_name'],
																								'lname' => $e ['last_name']]));
					 }
				}
		  }

		  return $employeeCount;
	 }
	 
	 /**
	  *
	  * utility methods
	  *
	  */

	 private function get ($accessToken, $endpoint) {

		  $headers = ['Square-Version: 2022-10-19',
						  'Content-type: Application/json',
						  'Authorization: Bearer ' . $accessToken];
		  
		  $url = 'https://connect.squareup.com/v2/' . $endpoint;
		  $curl = curl_init ();
		  
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
		  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		  curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
		  curl_setopt ($curl, CURLOPT_HTTPGET, false);
		  curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
		  
		  $result = curl_exec ($curl);
		  $result = json_decode ($result, true);
		  
		  if ($result) {

				return $result;
		  }
		  
		  return ['status' => 1,
					 'status_text' => 'error in post'];
	 }

	 /**
	  *
	  * the oauth url
	  *
	  */

	 private function oauth () {
		  
		  $scope = 'MERCHANT_PROFILE_READ+PAYMENTS_READ+ORDERS_READ+ITEMS_READ+CUSTOMERS_READ+EMPLOYEES_READ+SUBSCRIPTIONS_READ';
		  $clientID = $this->clientID;
		  
		  return 'https://connect.squareup.com/oauth2/authorize?' .
					"client_id=$clientID&" .
					"scope=$scope&" .
					'session=false&' .
					'state=';
	 }
}

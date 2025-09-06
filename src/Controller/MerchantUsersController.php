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

class MerchantUsersController extends PosAppController {
	 
	 public function index (...$args) {

		  $merchantID = $args [0];
		  
		  // switch to the merchant db for users
		  
		  $this->dbconnect (MERCHANT_DB);
		  
		  $users = TableRegistry::get ('MerchantUsers')
										->find ()
										->where (['merchant_id' => $merchantID])
										->select (['id', 'uname', 'email', 'fname', 'lname', 'role'])
										->all ();
		  

		  $this->set (['users' => $users,
							'merchantID' => $args [0]]);
	 }
	 
	 /**
	  *
	  * check if this email is in use
	  *
	  */
	 
	 public function checkEmail ($email) {

		  $this->debug ("check email... $email");
		  
		  $this->dbconnect (MERCHANT_DB);

		  $result = ['status' => 0];
		  
		  $user = TableRegistry::get ('MerchantUsers')
										->find ()
										->where (['email' => $email])
										->first ();
		  if ($user) {

				$result = ['status' => 1,
							  'status_text' => __ ('Email in use')];
		  }
		  
		  $this->ajax ($result);
	 }
	 
	 /**
	  *
	  * edit user
	  *
	  */
	 
	 public function edit ($id, $merchantID = 0) {

		  $this->debug ("user edit... $id , $merchantID");
		  
		  // switch to the merchant db for users
		  
		  $this->dbconnect (MERCHANT_DB);
		  
		  $user = null;
		  $usersTable = TableRegistry::get ('MerchantUsers');
		  
        if (!empty ($this->request->getData ())) {

				$this->update ($id, $this->request->getData (), $usersTable, $merchantID);
				return;
		  }

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
					  'roles' => ['admin' => 'Administrator',
									  'viewer' => 'Reports only'],
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

		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('MerchantUsers')
								->setTemplate ('edit');

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }
	 
    private function update ($id, $user, $usersTable, $merchantID) {

		  $this->debug ($user);
		  
		  $response = ['status' => 0];
		  
		  $id = $user ['user_id'];
		  $email = $user ['email'];
		  
		  if ($id == 0) {
				
				// check if this user exists...
				
				/* $user = $usersTable->find ()
					->where (['email' => $email])
					->first ();
					
					if ($user) {
					
					$response = ['status' => 1,
					'status_text' => __ ('A user with this email already exists')];
					}
					else {*/
				
				$user = ['merchant_id' => $user ['merchant_id'],
							'uname' => $user ['email'],
							'email' => $user ['email'],
							'fname' => $user ['fname'],
							'lname' => $user ['lname'],
							'role' => $user ['role'],
							'timezone' => $user ['timezone']];
				
				$user = $usersTable->newEntity ($user);
				/* }*/
		  }
		  else {
				
				$user = $usersTable->find ()
										 ->where (['id' => $id])
										 ->first ();
				
				$user ['uname'] = $user ['email'];
				$user ['email'] = $user ['email'];
				$user ['fname'] = $user ['fname'];
				$user ['lname'] = $user ['lname'];
				$user ['role'] = $user ['role'];
				$user ['timezone'] = $user ['timezone'];
		  }
		  
		  $usersTable->save ($user);
	 }
}

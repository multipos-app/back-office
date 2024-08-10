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
use Cake\Mailer\Email;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'tools.inc';

class RegisterController extends AppController {
    
    /**
     *
     * init a merchant
     *
     */
    
    function index (...$params) {
        
        $this->debug ("Register...");
		  $this->debug ($this->request->getData ());
		  $accessKey = "vr0csp-ye3NEnwRjusil_V62pjAl03mzU7ezlqwt6OWITxjszA";

		  /*
			* $this->set ('locale', 'en_US');
			* $viewVars = [];
			* 
			* if ($this->request->getData ()) {
			*     
			*     $this->debug ($this->request->getData ());
			*     
			*     // if ($this->request->getData () ['g-recaptcha-response']) {
			*     
			*     if (true) {

			*         $this->debug ('start reg...');

			*         // $post = 'secret=6Le6RfghAAAAAJh4g7jT-0ExfMuNxtZuWHN9fGmm&response=' .  $this->request->getData () ['g-recaptcha-response'];

			*         // $headers = ["Content-Type application/x-www-form-urlencoded; charset=utf-8",
			*         //             "Accept-Charset: utf-8",
			*         //             "Cache-Control: no-cache",
			*         //             "Content-length: " . strlen ($post)];

			*         // $ch = curl_init (); 
			*         // curl_setopt ($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
			*         // curl_setopt ($ch, CURLOPT_POST, true);
			*         // curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			*         // curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
			*         // curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
			*         // $response = curl_exec ($ch);      
			*         // curl_close($ch);

			*         
			*         // $this->debug ('captcha response...');
			*         // $this->debug ($response);

			*         $response = ['status' => -1];
			*         
			*         $merchantsTable = TableRegistry::get ('Merchants');
			*         $merchant = $merchantsTable
			*                   ->find ()
			*                   ->where (['email' => $this->request->getData () ['email']])
			*                   ->first ();
			*         
			*         if ($merchant) {

			*             $response = ['status' => 1,
			*                          'status_text' => __ ('A merchant with this email already exists.')];
			*         }
			*         else {
			*                   
			*             $merchant = $merchantsTable->newEntity (['merchant_name' => $this->request->getData () ['bname'],
			*                                                      'merchant_type' => 1,
			*                                                      'fname' => $this->request->getData () ['fname'],
			*                                                      'lname' => $this->request->getData () ['lname'],
			*                                                      'email' => $this->request->getData () ['email'],
			*                                                      'passwd' => md5 ($this->request->getData () ['passwd1']),
			*                                                      'guid' => guid (),
			*                                                      'registration_id' => bin2hex (openssl_random_pseudo_bytes (13))]);
			*             
			*             $merchant = $merchantsTable->save ($merchant);
			*             
			*             $merchantUsersTable = TableRegistry::get ('MerchantUsers');


			*             // debug...
			*             // $merchant = $merchantUsersTable->newEntity (['merchant_id' => 7000000,
			*             //                                              'uname' => $merchant ['email'],
			*             //                                              'passwd' => $merchant ['passwd'],
			*             //                                              'email' => $merchant ['email'],
			*             //                                              'role' => $merchant ['admin'],
			*             //                                              'locale' => 'America/New_York']);
			*             
			*             // $merchant = $merchantUsersTable->save ($merchant);
			*             
			*             $this->debug ('merchant...');
			*             $this->debug ($merchant);

			*             $email = new Email ();
			*             $email
			*                 ->subject ('Videoregister, email verification')
			*                 ->helpers (['Html'])
			*                 ->setViewVars (['title' => 'Welcome to Videoregister',
			*                                 'link' => $merchant ['guid']])
			*                 ->template ('new_merchant')
			*                 ->emailFormat ('html')
			*                 ->setTo ($this->request->getData () ['email'])
			*                 ->setFrom ('orders@multipos.cloud')
			*                 ->send ();
			*             
			*             $response = ['status' => 0,
			*                          'status_text' => __ ('Sucess, please check your email to complete your registration')];
			*         }
			*         
			*        
			*         $this->set ('response', $response);
			*         $this->viewBuilder ()->setLayout ('ajax');
			*         $this->RequestHandler->respondAs ('json');
			*         return;
			*     }
			*     else {

			*         // $viewVars ['error'] => __ ("Please click, I'm not a robot to continue")];
			*     }
			* }*/

        echo '{"response": 0, "response_text": "Success", "registration_id": "' . bin2hex (openssl_random_pseudo_bytes (13)) . '"}';
		  
        $this->set ('response', ['status' => 0]);
		  $this->viewBuilder ()->setLayout ('ajax');
		  $this->RequestHandler->respondAs ('json');
    }

    function getDevice () {
        
        $response = ['status' => -1,
                     'status_text' => 'unknown'];
        
        if ($this->request->getData ()) {
            
            $this->debug ($this->request->getData ());

            if (isset ($this->request->getData () ['ip_addr'])) {
                
                $merchantDevicesTable = TableRegistry::get ('MerchantDevices');
                $device = $merchantDevicesTable
                        ->find ()
                        ->where (['ip_addr' => $this->request->getData ('ip_addr')])
                        ->first ();

					 $this->debug ("device... ");
					 $this->debug ($device->toArray ());

                if ($device) {
                
                    $response = ['status' => 0,
                                 'status_text' => 'success',
                                 'dbname' => 'm_' . strval ($device ['merchant_id']),
                                 'business_unit_id' => strval ($device ['business_unit_id'])];
						  
						  $this->debug ($response);
                }
            }
            else {
                
                $response = ['status' => 1,
                             'status_text' => 'device not found'];
            }
        }
        
        $this->set ('response', $response);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');
		  
        return;
    }
    
    function verify ($guid) {

        $merchant = TableRegistry::get ('Merchants')
                  ->find ()
                  ->where (['guid' => $guid])
                  ->first ();

        $this->debug ("register verify... $guid");
        
        if ($merchant) {

            $this->debug ($merchant);
            
            $merchantUsersTable = TableRegistry::get ('MerchantUsers');
            
            $merchantUser = $merchantUsersTable->newEntity (['merchant_id' => $merchant ['id'],
                                                             'uname' => $merchant ['email'],
                                                             'fname' => $merchant ['fname'],
                                                             'lname' => $merchant ['lname'],
                                                             'email' => $merchant ['email'],
                                                             'passwd' => $merchant ['passwd'],
                                                             'role' => 'admin']);

            $merchantUsersTable->save ($merchantUser);

            $this->debug ('verify... ' . $guid);
            $this->debug ($merchant);
        
            $createDB = getcwd () . "/../sql/square-merchant-db-create.sql";

            $dbname = 'm_' . $merchant ['id'];
            
            $connection = ConnectionManager::get ('default');
            $create = "create database $dbname";
            $this->debug ('create... ' . $create);

            $connection->execute ("create database $dbname");

            dbconnect ($dbname);
            if (file_exists ($createDB)) {
                
                $sql = file_get_contents ($createDB);
                $connection = ConnectionManager::get ('default');
                $connection->execute ($sql);

                $buTable = TableRegistry::get ('BusinessUnits');
                $bu = $buTable->newEntity (['id' => 1,
                                            'business_unit_id' => 0,
                                            'business_type' => 1,
                                            'business_name' => $merchant ['merchant_name'],
                                            'locale' => 'us_US',
                                            'timezone' => 'America/New_York']);
                
                $bu = $buTable->save ($bu);

                // create at least one location
                
                $bu = $buTable->newEntity (['business_unit_id' => $bu ['id'],
                                            'business_type' => 1,
                                            'business_name' => $merchant ['merchant_name'],
                                            'locale' => 'us_US',
                                            'timezone' => 'America/New_York']);
                $bu = $buTable->save ($bu);
            }
        }

        return $this->redirect (['controller' => 'merchants', 'action' => 'logout']);
    }
}

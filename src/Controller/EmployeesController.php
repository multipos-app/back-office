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
use App\Model\Entity\Employee;
use App\Model\Entity\Profile;

class EmployeesController extends PosAppController {

    var $paginate = array ('limit' => 20);

    public function initialize (): void {
        
        parent::initialize ();
    }

    function index (...$params) {
        
        $q = null;
        $employees = TableRegistry::get ('Employees');
        
        if (count ($params) == 2) {
            
            switch ($params [0]) {
                    
					 case 'username':
                    
						  $q = TableRegistry::get ('Employees')->find ()
                                      ->where (['id' => $params [1]]);
						  break;
                    
					 case 'employee_name':
                    
						  $q = TableRegistry::get ('Employees')->find ()
                                      ->where (['id' => $params [1]]);
						  break;
                    
					 case 'profiles':
                    
						  $q = TableRegistry::get ('Employees')->find ()
                                      ->where (['profile_id' => $params [1]]);
						  break;
            }           
        }
        else {

            $q = TableRegistry::get ('Employees');
        }

		  $profiles = [];
		  
		  $p = TableRegistry::get ('Profiles')
								  ->find ('all');
		  
		  foreach ($p as $profile) {

				$profiles [$profile ['id']] = $profile;
		  }
		  
        $employees = $q->find ('all');
		  
        return ($this->response (__ ('Employees'),
                                 'Employees',
                                 'index',
                                 compact ('employees', 'profiles')));
    }

    function edit ($id = 0) {
		  
        $employee = false;
        if ($id == 0) {
				
            $employee = ['id' => 0,
								 'username' => '',
                         'fname' => '',
                         'lname' => '',
                         'password1' => '',
                         'password2' => '',
                         'profile_id' => 0];
        }
        else {
            
            $query = TableRegistry::get ('Employees')
											 ->find ()
											 ->where (['id' => $id]);
            
            $employee = $query->first ();
        }
		  
        $profiles = [null => 'Profile'];
        $query = TableRegistry::get ('Profiles')->find ();
        foreach ($query as $p) {
				
            $profiles [$p ['id']] = $p ['profile_desc'];
        }

		  $this->debug ($profiles);
        
        return ($this->response ($employee ['fname'] . ' ' . $employee ['lname'],
                                 'Employees',
                                 'edit',
                                 compact ('employee', 'profiles')));
    }

    public function update ($id) {

        $status = -1;
        $employee = false;
        
        if (!empty ($this->request->getData ())) {
            
            $employeeTable = TableRegistry::get ('Employees');

            if ($id > 0) {

                $employee = $employeeTable
                          ->find ()
                          ->where (['id' => $id])
                          ->first ();

                if ($employee) {

                    $employee ['username'] = $this->request->getData () ['username'];
                    $employee ['password'] = md5 ($this->request->getData () ['password1']);
                    $employee ['fname'] = strtoupper ($this->request->getData () ['fname']);
                    $employee ['lname'] = strtoupper ($this->request->getData () ['lname']);
                    $employee ['profile_id'] = $this->request->getData () ['profile_id'];
                }
            }
            else  {
                
                $employee = $employeeTable->newEntity (['username' => $this->request->getData () ['username'],
                                                        'password' => md5 ($this->request->getData () ['password1']),
                                                        'fname' => strtoupper ($this->request->getData () ['fname']),
                                                        'lname' => strtoupper ($this->request->getData () ['lname']),
                                                        'profile_id' => $this->request->getData () ['profile_id']]);

            }

            if ($employee) {
                
                $this->save ('Employees', $employee);
            }        
        }
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);

    }
	 
    public function delete ($id) {

		  $status = 1;
        $employee = $this->Employees->findById ($id)->firstOrFail ();
        if ($this->Employees->delete ($employee)) {

				$status = 0;
		  }
		  
		  $this->ajax (['status' => $status]);
    }
}
?>
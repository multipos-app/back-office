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
use App\Model\Entity\Department;

class DepartmentsController extends PosAppController {

    
    public $paginate = ['limit' => 20,
                        'order' => ['department_desc asc']];

    private $departmentTypes = null;
    
    public function initialize (): void {
    
        parent::initialize ();
        
        $this->departmentTypes = [0 => __ ('Not set'),
                                  1 => __ ('Merchandise'),
                                  2 => __ ('Menu'),
                                  3 => __ ('Bank'),
                                  4 => __ ('Deposit'),
                                  5 => __ ('Other'),
                                  6 => __ ('Labor')];
    }

    public function index (...$params) {
        
         $departments = $this->paginate ();
         $departmentTypes = $this->departmentTypes;

        return ($this->response (__ ('Departments'),
                                 'Departments',
                                 'index',
                                 compact ('departments', 'departmentTypes'),
                                 true,
                                 'ajax',
                                 true));
    }
  
    public function edit ($id = 0) {
    
        $department = null;

        if ($id == 0) {

				$department = ['id' => 0,
									'department_desc' => '',
									'department_no' => 0,
									'department_type' => 1,
									'locked' => false,
									'department_order' => 0,
									'is_negative' => false,
									'pricing' => null];      
        }
        else {
        
            $query = TableRegistry::get ('Departments')
                   ->find ()
                   ->where (['id' => $id]);
      
            $department = $query->first ();
        }
		  
		  $pricing = [];
		  $query = TableRegistry::get ('Pricing')
										->find ('all')
										->order (['name' => 'asc']);
        
		  foreach ($query as $p) {
            
				$pricing [$p ['id']] = $p ['name'];
		  }
		  
		  $departmentTypes = $this->departmentTypes;
		  
		  $isNegative = [0 => __ ('No'),
							  1 => __ ('Yes')];
	 
        return ($this->response (__ ($department ['department_desc']),
                                 'Departments',
                                 'edit',
                                 compact ('department', 'pricing', 'departmentTypes', 'isNegative')));

 		  $this->debug ($department);
    }
   
    public function update () {

        $status = -1;
        
        if (!empty ($this->request->getData ())) {

            $this->debug ($this->request->getData ());

            $departmentsTable = TableRegistry::get ('Departments');
            $dept = $this->request->getData ();
            $department = false;
            
            $dept ['department_desc'] = strtoupper ($dept ['department_desc']);
            
            if ($dept ['department_id'] > 0) {

                $department = $departmentsTable
                            ->find ()
                            ->where (['id' => $dept ['department_id']])
                            ->first ();
                
                $department ['department_desc'] = strtoupper ($dept ['department_desc']);
                $department ['department_type'] = intval ($dept ['department_type']);
                $department ['is_negative'] = intval ($dept ['is_negative']);
            }
            else {

                $department = $departmentsTable->newEntity ($dept);
            }

            if ($department) {
                
                $this->save ('departments', $department);
                $status = 0;
            }
        }
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);
    }
  
    public function delete ($id) {
    
		  $status = 1;
        $department = $this->Departments->findById ($id)->firstOrFail ();
        if ($this->Departments->delete ($department)) {
				
            $status = 0;
        }
		  
		  $this->ajax (['status' => $status]);
    }
}

?>

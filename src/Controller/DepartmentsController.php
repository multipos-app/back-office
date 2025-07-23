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

    
    public $paginate = ['limit' => 20];

    private $departmentTypes = null;
    
    public function initialize (): void {
		  
        parent::initialize ();
        
        $this->departmentTypes = [0 => __ ('Not set'),
                                  1 => __ ('Merchandise'),
                                  2 => __ ('Menu'),
                                  3 => __ ('Bank'),
                                  4 => __ ('Deposit'),
                                  5 => __ ('Modifiers'),
                                  6 => __ ('Labor'),
                                  7 => __ ('Redeem'),
                                  8 => __ ('Payout')];
    }

    public function index (...$params) {
        
		  $departments = $this->paginate ();
        $departmentTypes = $this->departmentTypes;
		  
        $this->set (['departments' => $departments,
							'departmentTypes' => $departmentTypes]);
	 }
	 
    public function edit ($id) {
		  
        $department = null;
		  $departmentsTable = TableRegistry::get ('Departments');
		  
        if (!empty ($this->request->getData ())) {

				$this->update ($id, $this->request->getData (), $departmentsTable);
				return $this->redirect ('/departments');
		  }
		  
		  if ($id == 0) {

				$department = ['id' => 0,
									'department_desc' => '',
									'department_id' => 0,
									'department_type' => 1,
									'locked' => false,
									'department_order' => 0,
									'is_negative' => false,
									'params' => null,
									'inventory' => 0];      
		  }
		  else {
				
				$department = $departmentsTable->find ()
														 ->where (['id' => $id])
														 ->first ();
		  }

		  // get parent department list
		  
		  $departments = [null => __ ('Parent department')];
        $query = TableRegistry::get ('Departments')
										->find ()
										->where (["id != $id"])
										->order (['department_desc' => 'asc']);
        
        foreach ($query as $d) {
            
            $departments [$d ['id']] = $d ['department_desc'];
        }
		  		  
		  $departmentTypes = $this->departmentTypes;
		  
 		  $this->set (['department' => $department,
							'departments' => $departments,
							'departmentTypes' => $departmentTypes,
							'isNegative' => [0 => __ ('No'),
												  1 => __ ('Yes')],
							'inventory' => [0 => __ ('No'),
												 1 => __ ('Yes')]]);
		  
 		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Departments')
								->setTemplate ('edit');

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
		  
    }
    
    private function update ($id, $department, $departmentsTable) {
		  
        $department ['department_desc'] = strtoupper ($department ['department_desc']);
        
        if ($id == 0) {

				$department ['department_desc'] = strtoupper ($department ['department_desc']);
			   $department = $departmentsTable->newEntity ($department);
				$this->save ('departments', $department);
		  }
		  else {

				$isNegative = isset ($department ['is_negative']) ? 1 : 0;
				$inventory = isset ($department ['inventory']) ? 1 : 0;
				
            $departmentsTable->updateAll (['department_desc' => strtoupper ($department ['department_desc']),
														 'department_type' => intval ($department ['department_type']),
														 'is_negative' => $isNegative,
														 'inventory' => $inventory],
														['id' => $id]);
				$this->batch ('departments', $id);
        }
	 }
	 
    public function delete ($id) {
    	  
        $department = $this->Departments->findById ($id)->firstOrFail ();
        if ($this->Departments->delete ($department)) {
		  }
    }
}

?>

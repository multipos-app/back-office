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
use App\Model\Entity\Item;

class TaxGroupsController extends PosAppController {

    var $maxRates = 5;

    public function initialize (): void {
    
        parent::initialize ();
    }

    public function index (...$params) {

        $taxGroups = [];
        $query = TableRegistry::get ('TaxGroups')->find ()->contain (['Taxes']);
        foreach ($query as $tg) {
            $taxGroups [] = $tg;
        }
        
		  $this->set (['merchant' => $this->merchant,
							'taxGroups' => $taxGroups]);
    }

    public function edit ($id) {

		  $taxGroupsTable = TableRegistry::get ('TaxGroups');

        if (!empty ($this->request->getData ())) {

				$this->update ($id, $this->request->getData (), $taxGroupsTable);
				return $this->redirect ('/tax-groups');
		  }

        $taxGroup = null;
        
        if ($id == 0) {
				
            $taxGroup ['id'] = 0;
            $taxGroup ['short_desc'] = '';
            $taxGroup ['taxes'] = [];
           
        }
        else {
				$taxGroup = $taxGroupsTable
                      ->find ()->where (["id = $id"])
                      ->contain (['Taxes'])
                      ->first ();  
        }
        
		  $this->set (['taxGroup' => $taxGroup,
							'taxTypes' => ['percent' => __ ('Percent'),
												'fixed' => __ ('Fixed')]]);
		  
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('TaxGroups')
								->setTemplate ('edit');

		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
    }
	 	 
    public function update ($id, $taxGroup, $taxGroupsTable) {


		  $this->debug ($taxGroup);
		  
        if ($id == 0) {
				
				$taxGroup ['short_desc'] = strtoupper ($taxGroup ['short_desc']);				
				$this->save ('TaxGroups', TableRegistry::get ('TaxGroups')->newEntity ($taxGroup));

		  }
		  else {
				
        }
    }

    public function delete ($id) {
        
        $this->request->allowMethod (['post', 'delete']);
        
        return $this->redirect (['action' => 'index']);
        
    }

	 /**
	  *
	  * format a new tax row and send it back to the tax groups edit dialog
	  *
	  **/
	 
    public function addTax ($row) {
		  
   	  $this->set (['row' => $row,
							'taxTypes' => ['percent' => __ ('Percent'),
												'fixed' => __ ('Fixed')]]);
		  
		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('TaxGroups')
								->setTemplate ('add_tax');
		  
		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
	 }
}

?>

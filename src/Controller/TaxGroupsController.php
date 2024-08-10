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
      
        $merchant = $this->merchant;
        return ($this->response (__ ('Taxes'),
                                 'TaxGroups',
                                 'index',
                                 compact ('merchant', 'taxGroups')));
    }

    public function edit ($id) {

        $this->debug ("tax edit... $id");
        
        $taxGroup = null;
        
        if ($id > 0) {
            
            $taxGroup = TableRegistry::get ('TaxGroups')
                      ->find ()->where (["id = $id"])
                      ->contain (['Taxes'])
                      ->first ();
        }
        else {
            
            $taxGroup = TableRegistry::get ('TaxGroups')->newEntity ();
            $taxGroup ['id'] = null;
            $taxGroup ['short_desc'] = '';
            $taxGroup ['taxes'] = [['id' => null,
                                    'rate' => '',
                                    'alt_rate' => '',
                                    'short_desc' => '',
                                    'type' => 'percent']];
        }
        
        $taxTypes = ['percent' => __ ('Percent'),
                     'fixed' => __ ('Fixed')];
        
        $this->debug ($taxGroup);
        
        return ($this->response ($taxGroup ['short_desc'],
                                 'TaxGroups',
                                 'edit',
                                 compact ('taxGroup', 'taxTypes')));
    }
    
    public function update ($id = 0) {

        $status = -1;
        if (!empty ($this->request->getData ())) {

				
				$taxGroup = $this->request->getData ();

				$this->debug ($taxGroup);
				
				$taxes = $taxGroup ['taxes'];
				unset ($taxGroup ['taxes']);

				$taxGroup ['short_desc'] = strtoupper ($taxGroup ['short_desc']);
				
				$this->save ('TaxGroups', TableRegistry::get ('TaxGroups')->newEntity ($taxGroup));

				foreach ($taxes as $tax) {

					 $tax ['tax_group_id'] = $taxGroup ['id'];
					 $tax ['short_desc'] = strtoupper ($taxGroup ['short_desc']);
                $this->save ('Taxes', TableRegistry::get ('Taxes')->newEntity ($tax));
            }

            $status = 0;
        }
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);

    }

    public function delete ($id) {
        
        $this->request->allowMethod (['post', 'delete']);
        
        return $this->redirect (['action' => 'index']);
        
    }
}

?>

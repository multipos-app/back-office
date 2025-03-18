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
use Cake\I18n\Number;
use App\Controller\PosAppController;
use Cake\Datasource\ConnectionManager;

class ItemsUtilsController extends PosAppController {

	 /**
	  *
	  * utility function, create inventory for all items
	  *
	  */

    function createInvItems ($buID) {

        $itemsTable = TableRegistry::get ('Items');
        $invItemTable = TableRegistry::get ('InvItems');

        $query = $itemsTable->find ('all');
        foreach ($query as $item) {

            $invItem = $invItemTable->newEntity (['item_id' => $item ['id'],
																  'business_unit_id' => $buID]);
            $invItemTable->save ($invItem);
        }
		  
        $this->set (['response' => 0]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
    }
	 
    /**
     *
     * utility function, create item prices for all items
     *
     */

    function createItemPrices () {

        $itemsTable = TableRegistry::get ('Items');
        $itemPricesTable = TableRegistry::get ('ItemPrices');
		  
        $query = $itemsTable
					  ->find ('all')
                 ->contain (['ItemPrices']);

        foreach ($query as $item) {
				
				$itemPrices = [];
				foreach ($this->merchant ['business_units'] as $bu) {

					 if ($bu ['business_type'] == 2) {
						  
						  $present = false;
						  foreach ($item ['item_prices'] as $ip) {
								
								if ($ip ['business_unit_id'] != $bu ['id']) {

									 unset ($ip ['id']);
									 $ip ['business_unit_id'] = $bu ['id'];
									 $itemPrices [$bu ['id']] = $ip->toArray ();
								}
						  }
					 }
				}
				
				$itemPricesTable->deleteAll (['item_id' => $item ['id']]);

				foreach ($itemPrices as $bu => $ip) {

					 $ip = $itemPricesTable->newEntity ($ip);
					 $itemPricesTable->save ($ip);
				}
		  }
		  
        $this->set (['response' => 0]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
    }
	 
    /**
     *
     * utility function, remove inventory and pricing not associated with an item
     *
     */

    function cleanup () {

        $itemsTable = TableRegistry::get ('Items');
        $itemPricesTable = TableRegistry::get ('ItemPrices');
        $invItemTable = TableRegistry::get ('InvItems');

		  foreach ([$itemPricesTable, $invItemTable] as $table) {

				$query = $table->find ('all');
				foreach ($query as $it) {
					 
					 $item = $itemsTable
				->find ()
				->where (['id' => $it ['item_id']])
				->first ();
					 
					 if (!$item) {
						  
						  $table->deleteAll (['id' => $it ['id']]);
					 }
				}
		  }
		  
		  $this->set ('response', ['status' => 0]);
        $this->viewBuilder ()->setLayout ('ajax');
        $this->RequestHandler->respondAs ('json');
    }
}

<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

/**
 *
 * add or update a item
 *
 */

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class ItemComponent extends Component {

	 public function update ($update, $controller) {

	 	  $itemsTable = TableRegistry::get ('Items');

		  $item = $itemsTable->find ()
									->where (['sku' => $update ['sku']])
									->contain (['ItemPrices'])
									->first ();
		  
		  if ($item) {

				$id = $item ['id'];
								
				$item ['item_desc'] = $update ['item_desc'];
				$item ['department_id'] = $update ['department_id'];
				$item ['locked'] = $update ['locked'];
				$item ['enabled'] = $update ['enabled'];

				$itemPricessTable = TableRegistry::get ('ItemPrices');
				for ($i = 0; $i < count ($item ['item_prices']); $i ++) {
									 
					 $item ['item_prices'] [$i] ['price'] = $update ['item_price'] ['price'];
					 $item ['item_prices'] [$i] ['cost'] = $update ['item_price'] ['cost'];
					 $item ['item_prices'] [$i] ['tax_exempt'] = $update ['item_price'] ['tax_exempt'];
					 $item ['item_prices'] [$i] ['tax_group_id'] = $update ['item_price'] ['tax_group_id'];
					 $item ['item_prices'] [$i] ['tax_inclusive'] = $update ['item_price'] ['tax_inclusive'];
					 $item ['item_prices'] [$i] ['class'] = $update ['item_price'] ['class'];
					 $item ['item_prices'] [$i] ['pricing'] = json_encode ($update ['item_price'] ['pricing']);
					 
					 $itemPricessTable->save ($item ['item_prices'] [$i]);
				}
				
				$item = $itemsTable->save ($item);
		  }
		  else {

				// new item, need to create a pricing record for each business location
				
				$bus = TableRegistry::get ('BusinessUnits')
										  ->find ()
										  ->where (['business_type' => BU_LOCATION])
										  ->select (['id'])
										  ->all ();
				
				$itemPrice = $update ['item_price'];
				unset ($itemPrice ['id']);
				unset ($itemPrice ['item_id']);
				unset ($update ['id']);
				unset ($update ['item_price']);
				
				$itemPrices = [];
				$pricing = json_encode ($itemPrice ['pricing']);
								
				foreach ($bus as $bu) {

					 $itemPrice ['business_unit_id'] = $bu ['id'];
					 $itemPrice ['pricing'] = $pricing;
					 $itemPrices [] = $itemPrice;
				}

				$update ['item_prices'] = $itemPrices;
				$item = $itemsTable->save ($itemsTable->newEntity ($update));
		  }

		  if (isset ($update ['deposit_item_id'])) {  // add a deposit
								
				$itemLinksTable = TableRegistry::get ('ItemLinks');

				$itemLinksTable->deleteAll (['item_id' => $id]);
				$itemLink = $itemLinksTable->newEntity (['item_id' => $id,
																	  'item_link_id' => $update ['deposit_item_id']]);
				$itemLink = $itemLinksTable->save ($itemLink);								
		  }
		  
		  return $item ['id'];
	 }
}


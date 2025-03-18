<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

/**
 *
 * add or update a customer
 *
 */

class CustomerComponent extends Component {
	 
    public function update ($update, $ticket, $controller) {
		  
		  $customerID = $update ['id'];
		  
		  $customersTable = TableRegistry::get ('Customers');
		  
		  if ($customerID == 0) {

				$customer = $customersTable->newEntity ($update);
		  }
		  else {

				$customer = $customersTable->find ()
													->where (['id' => $customerID])
													->first ();
				
				foreach (['fname', 'lname', 'phone', 'email', 'addr_1', 'city', 'state', 'postal_code', 'pin', 'uuid'] as $field) {

					 if (isset ($update [$field]) && (strlen ($update [$field]) > 0)) {
						  
						  $customer [$field] = $update [$field];
					 }
				}
		  }
		  
		  // update loyalty fields

		  $customer ['total_visits'] = isset ($update ['total_visits']) ? $update ['total_visits'] + 1 : 1;
		  $customer ['total_sales'] = isset ($update ['total_sales']) ? $update ['total_sales'] + $ticket ['total'] : $ticket ['total'];
		  $customer ['loyalty_points'] = isset ($update ['loyalty_points']) ? $update ['loyalty_points'] + intval ($ticket ['total']) : intval ($ticket ['total']);

		  // save it

		  $customersTable->save ($customer);
		  		  
		  if ($customerID == 0) {

				$customerID = $customersTable->find ()  // retrieve the last insert
													  ->select (['id'])
													  ->order (['id' => 'desc'])
													  ->first ()
													  ->id;
				
				// new customer was created add the id to the ticket
				
				TableRegistry::get ('Tickets')
								 ->updateAll (['customer_id' => $customer ['id']],
												  ['id' => $ticket ['id']]);
				
		  }
		  
		  return $customerID;
	 }
}

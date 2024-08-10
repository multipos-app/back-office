<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class TicketComponent extends Component {
	 
    public function desc () {
		  
		  return [null => __ ('Ticket types'),
					 SALE => __ ('SALE'),
					 VOID => __ ('VOID'),
					 NO_SALE => __ ('NO SALE'),
					 COMP_SALE => __ ('COMP'),
					 LOGIN => __ ('LOG IN'),
					 LOGOUT => __ ('LOG OUT'),
					 X_SESSION => __ ('X REPORT'),
					 Z_SESSION => __ ('Z REPORT'),
					 BANK => __ ('BANK'),
					 MANAGER_OVERRIDE => __ ('MANAGER OVERRIDE'),
					 OPEN_AMOUNT => __ ('OPEN AMOUNT'),
					 DISCOUNTS => __ ('DISCOUNTS'),
					 RETURN_SALE => __ ('RETURN'),
					 VOID_ITEMS => __ ('VOID ITEMS'),
					 SALE_NONTAX => __ ('NON TAX'),
					 REFUND => __ ('REFUND')];
	 }

	 
	 public function type () {

		  return [null => __ ('Ticket type'),
					 'sales' => __ ('STANDARD SALE'),
					 'void_sales' => __ ('VOID'),
					 'no_sales' => __ ('NO SALE'),
					 'comp_sales' => __ ('COMP SALE'),
					 'logins' => __ ('LOG IN'),
					 'logouts' => __ ('LOG OUT'),
					 'x_sessions' => __ ('X REPORT'),
					 'z_sessions' => __ ('Z REPORT'),
					 'bank' => __ ('BANK'),
					 'manager_override' => __ ('MANAGER OVERRIDE'),
					 'open_amount' => __ ('OPEN AMOUNT'),
					 'discounts' => __ ('DISCOUNTS'),
					 'return_items' => __ ('RETURN ITEMS'),
					 'void_items' => __ ('VOID ITEMS')];
	 }
	 
	 public function exception () {

		  return [null => __ ('Ticket contains'),
					 'discounts' => __ ('DISCOUNTS'),
					 'return_items' => __ ('RETURN ITEMS'),
					 'void_items' => __ ('VOID ITEMS')];
	 }
	 
	 public function ticketTypeMap () {

		  return ['sales' => SALE,
					 'void_sales' => VOID,
					 'no_sales' => NO_SALE,
					 'comp_sales' => COMP_SALE,
					 'logins' => LOGIN,
					 'logouts' => LOGOUT,
					 'x_sessions' => X_SESSION,
					 'z_sessions' => Z_SESSION,
					 'bank' => BANK,
					 'manager_override' => MANAGER_OVERRIDE,
					 'open_amount' => OPEN_AMOUNT,
					 'refund_sales' => REFUND];
	 }
	 
	 public function tender () {

		  return [null => __ ('Tender type'),
					 'cash' => __ ('CASH'),
					 'credit' => __ ('CREDIT'),
					 'debit' => __ ('DEBIT'),
					 'account' => __ ('ACCOUNT'),
					 'check' => __ ('CHECK'),
					 'gift_card' => __ ('GIFT CARD'),
					 'gift_certificate' => __ ('GIFT CERTIFICATE'),
					 'split' => __ ('SPLIT'),
					 'no sale' => __ ('NO SALE'),
					 'mobile' => __ ('MOBILE')];
	 }

	 public function pageSize () {

		  return [null => __ ('Tickets per page'),
					 25 => __ ('25'),
					 50 => __ ('50'),
					 100 => __ ('100'),
					 200 => __ ('200')];
	 }
}

?>

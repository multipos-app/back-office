<?php
/**
 *
 * Copyright (C) 2024 VideoRegister, LLC
 * <http://www.videoregister.com>
 *
 * All rights are reserved. No part of the work covered by the copyright
 * hereon may be reproduced or used in any form or by any means graphic,
 * electronic, or mechanical, including photocopying, recording, taping,
 * or information storage and retrieval systems -- without written
 * permission signed by an officer of VideoRegister, LLC.
 *
 */

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Mailer;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class AlertsController extends PosAppController {


    function index (...$args) {
		  
		  $limit = 7;  // report period

		  $alerts = TableRegistry::get ('Alerts')
										 ->find ()
										 ->where (['status' => 1])
										 ->all ()
										 ->toArray ();		  

		  $data = ['alerts' => []];
		  
		  foreach ($alerts as $alert) {
				
				$alert ['params'] = json_decode ($alert ['params'], true);
				
				// report on the last $limit days
				
				$where = [$alert ['params'] ['conditions'],
							 'summary_type' => 1,
							 'business_unit_id' => $this->merchant ['bu_id'],
							 'start_time > curdate() - interval ' . $limit .' day',
							 'start_time < curdate()'];
				
				$totals = TableRegistry::get ($alert ['params'] ['table'])
											  ->find ()
											  ->select (['start_time', 'quantity', 'amount'])
											  ->where ($where)
											  // ->order (['start_time desc'])
											  ->all ()
											  ->toArray ();
				
				$start = time () - ($limit * ONE_DAY);
				$alert ['totals'] = [];
								
				foreach ($totals as $total) {

					 // pad for missing days
					 
					 for ($j = intval (date ('z', $start)); $j < intval (date ('z', strtotime ($total ['start_time']))); $j ++) {

						  $alert ['totals'] [] = ['start_time' => date ('Y-m-d', $start),
														  'quantity' => 0,
														  'amount' => 0];
						  $start += ONE_DAY;
					 }
					 
					 $total ['start_time'] = date ('Y-m-d', strtotime ($total ['start_time']));

					 if (isset ($alert ['params'] ['col']) && ($alert ['params'] ['col'] == 'quantity')) {

						  $total ['quantity'] = $total ['quantity'] * -1;
					 }
					 
					 $alert ['totals'] [] = $total;					 
					 $start += ONE_DAY;
				}

				// pad out the end
				
				while (count ($alert ['totals']) < $limit) {
					 
					 $alert ['totals'] [] = ['start_time' => date ('Y-m-d', $start),
													 'quantity' => 0,
													 'amount' => 0];
					 
					 $start += ONE_DAY;
				}

				if ($alert ['alert_name'] == 'totals') {

					 $data ['totals'] = $alert;
				}
				else {

					 $sorted = [];
					 foreach ($alert ['totals'] as $t) {

						  if ($t [$alert ['params'] ['col']] != 0) {
								
								$sorted [] = $t [$alert ['params'] ['col']];
						  }
					 }

					 // sort the values and pick the median from the center
					 
					 usort ($sorted, function ($a, $b) {
						  
						  if ($a == $b) {
								
								return 0;
						  }
						  
						  return ($a < $b) ? -1 : 1;
					 });

					 if (count ($sorted)) {
						  
						  $alert ['median'] = $sorted [intval (count ($sorted) / 2)];
					 }
					 else {

						  $alert ['median'] = 0;
					 }
					 
					 $alert ['exceptions'] = [];

					 // find the values that exceed the median

					 if ($alert ['params'] ['factor'] > 0) {
						  
						  foreach ($alert ['totals'] as $total) {
								
								if (abs ($total [$alert ['params'] ['col']]) > abs ($alert ['params'] ['factor'] * $alert ['median'])) {
									 
									 $total ['date'] = date ('n/j', strtotime ($total ['start_time']));
									 $total ['timestamp'] = strtotime ($total ['start_time']);
									 $alert ['exceptions'] [] = $total;
								}
						  }

						  usort ($alert ['exceptions'], function ($a, $b) {
						  
								if ($a ['timestamp'] == $b ['timestamp']) {
									 
									 return 0;
								}
								
								return ($a ['timestamp'] < $b ['timestamp']) ? 1 : -1;
						  });
						  
					 }
					 $data ['alerts'] [] = $alert;			  
				}
		  }

		  $dates = [];
		  $start = time () - ($limit * ONE_DAY);
		  for ($i = $limit; $i > 0; $i --) {
				
				$dates [] = date ('n/j', $start);
				$start += ONE_DAY;
		  }

		  $data ['dates'] = $dates;
		  $this->set ($data);
	 }

	 /**********************************************************************************************
	  *
	  * get the tickets associated for the alert day
	  *
	  *********************************************************************************************/

	 function tickets ($timestamp, $ticketType, $index) {
		  
		  $response = ['status' => 1];
		  
		  $start = date ('Y-m-d H:i:s', $timestamp);
		  $end = date ('Y-m-d H:i:s', $timestamp + ONE_DAY);

		  $where = ["complete_time >= '$start'",
						"complete_time < '$end'"];

		  switch ($ticketType) {

				case 'refunds':
					 
					 $where [] = 'ticket_type = ' . REFUND;
					 break;
					 
				case 'discounts':
					 
					 $where [] = 'discounts > 0';
					 break;
					 
				case 'no_sales':
					 
					 $where [] = 'ticket_type = ' . NO_SALE;
					 break;	 
		  }

		  $tickets = TableRegistry::get ('Tickets')
										  ->find ()
										  ->select (['id',
														 'ticket_no',
														 'complete_time',
														 'tender_desc',
														 'total',
														 'discounts',
														 'item_count',
														 'state',
														 'comment',
														 'tip'])
										  ->where ($where)
										  ->all ()
										  ->toArray ();

		  
		  for ($i = 0; $i < count ($tickets); $i ++) {

				$tickets [$i] ['complete_time'] = $this->utcToLocal ($tickets [$i] ['complete_time'], $this->tz (), 'g:i a');
		  }

		  // find the original ticket
		  
		  $t = [];
		  if ($ticketType == 'refunds') {
				
				foreach ($tickets as $ticket) {

					 $t [] = $ticket;
					 
					 $refunded = TableRegistry::get ('Tickets')
													  ->find ()
													  ->select (['id',
																	 'ticket_no',
																	 'complete_time',
																	 'tender_desc',
																	 'total',
																	 'discounts',
																	 'item_count',
																	 'state',
																	 'comment',
																	 'tip'])
													  ->where (['ticket_no' => $ticket ['ticket_no']])
													  ->first ()
													  ->toArray ();

					 $refunded ['complete_time'] = $this->utcToLocal ($refunded ['complete_time'], $this->tz (), 'g:i a');
					 $t [] = $refunded;
				}

				$tickets = $t;
		  }
		  
		  $this->set (['ticketType' => $ticketType,
							'tickets' => $tickets]);
        
        $builder = $this->viewBuilder ()
                        ->setLayout ('ajax')
                        ->disableAutoLayout ()
                        ->setTemplatePath ('Alerts')
                        ->setTemplate ('tickets');

        $view = $builder->build ();
        $html = $view->render ();
		  
		  $html = preg_replace ([ '/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'], [' ', ''], $html);

		  $response = ['status' => 0,
							'ticket_type' => $ticketType,
							'index' => $index,
							'html' => $html];
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->setTemplate ('ajax');
		  
        $this->set ('response', $response);
        $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**********************************************************************************************
	  *
	  * 
	  *
	  *********************************************************************************************/

	 function settings () {

		  $alertsTable = TableRegistry::get ('Alerts');
		  
		  if (!empty ($this->request->getData ())) {

				foreach ($this->request->getData () ['alerts'] as $a) {

					 $alert = $alertsTable
												  ->find ()
												  ->where (['id' => $a ['id']])
												  ->first ();
					 
					 $alert ['params'] = json_decode ($alert ['params'], true);
					 $alert ['params'] ['factor'] = $a ['factor'];
					 
					 $alert ['params'] = json_encode ($alert ['params']);

					 $alertsTable->save ($alert);
				}
		  }

		  $response = ['status' => 0,
							'html' => ''];
		  
		  $alerts = $alertsTable
										 ->find ()
										 ->where (['status' => 1,
													  "alert_name != 'totals'"])
										 ->all ()
										 ->toArray ();

		  
		  if ($alerts) {

				for ($i = 0; $i < count ($alerts); $i ++) {

					 $alerts [$i] ['params'] = json_decode ($alerts [$i] ['params'], true);
				}
				
				$this->set (['alerts' => $alerts]);
				
				$builder = $this->viewBuilder ()
									 ->setLayout ('ajax')
									 ->disableAutoLayout ()
									 ->setTemplatePath ('Alerts')
									 ->setTemplate ('settings');
				
				
				$view = $builder->build ();
				$html = $view->render ();
				$html = preg_replace ([ '/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'], [' ', ''], $html);
				
				$response = ['status' => 0,
								 'html' => $html];
		  }
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->setTemplate ('ajax');
		  
		  $this->set ('response', $response);
		  $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**********************************************************************************************
	  *
	  * gather the exceptions
	  *
	  *********************************************************************************************/

	 private function exceptions ($period, $alert) {
		  
		  $exception = null;
		  
	  	  $limit = 30;  // base median the value in the middle of the $limit
		  
		  $alert ['params'] = json_decode ($alert ['params'], true);

		  if ($alert ['params'] ['factor'] == 0) {

				return false;
		  }
		  
		  // get a sample
		  
		  $totals = TableRegistry::get ($alert ['params'] ['table'])
										 ->find ()
										 ->where (['summary_type' => 1,
													  $alert ['params'] ['conditions']])
										 ->limit ($limit)
										 ->order ([$alert ['params'] ['col'] . ' desc'])
										 ->all ()
										 ->toArray ();
		  
		  
		  if ($totals  && (count ($totals) > 10)) {
				
				// median is the middle of the sample
				
				$median = abs ($totals [$limit / 2] [$alert ['params'] ['col']]);
				
				// scan the last $period days summaries
				
				$where = [$alert ['params'] ['conditions'],
							 'summary_type' => 1,
							 'start_time > curdate() - interval ' . $period . ' day',
							 'start_time < curdate()'];
				
				$salesException = TableRegistry::get ($alert ['params'] ['table'])
														 ->find ()
														 ->select (['start_time', 'exception_key', 'quantity', 'amount'])
														 ->where ($where)
														 ->first ()
														 ->toArray ();
				
				if ($salesException) {
					 
					 if (abs ($salesException [$alert ['params'] ['col']]) >= abs ($median * $alert ['params'] ['factor'])) {

						  $exception  = ['median' => $median,
											  'timestamp' => strtotime ($salesException ['start_time']),
											  'date' => date ('n/j', strtotime ($salesException ['start_time'])),
											  'quantity' => $salesException ['quantity'],
											  'amount' => abs ($salesException [$alert ['params'] ['col']])];
						  
					 }
				}
		  }
		  
		  return $exception;
	 }

	 private function totals ($period) {

		  // scan the last $period days summaries
		  
		  $where = ['summary_type' => 1,
						'sales_key' => 'TAXABLE',
						'start_time > curdate() - interval ' . $period . ' day',
		  				'start_time < curdate()'];

		  $totals = TableRegistry::get ('SalesTotals')
										 ->find ()
										 ->where ($where)
										 ->order (['id desc'])
										 ->all ()
										 ->toArray ();
		  
		  $salesTotals = [];
		  foreach ($totals as $total) {

				$salesTotals []  = ['timestamp' => strtotime ($total ['start_time']),
										  'date' => date ('n/j', strtotime ($total ['start_time'])),
										  'quantity' => $total ['quantity'],
										  'amount' => abs ($total ['amount'])];	
		  }

		  return $salesTotals;
	 }
}

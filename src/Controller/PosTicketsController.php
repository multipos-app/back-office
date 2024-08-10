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

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use \DateTime;
use \DateTimeZone;
use Cake\I18n\I18n;
use App\Controller\PosApiController;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class PosTicketsController extends PosApiController {

    public $ticket;
    public $ticketID;
    public $ticketDetail = ['ticket_items' => 'TicketItems',
                            'ticket_item_addons' => 'TicketItemAddons',
                            'ticket_taxes' => 'TicketTaxes',
                            'ticket_tenders' => 'TicketTenders'];
    
    public $bu = null;
    public $bus = null;
    public $posNo = null;
    public $totalTimes = null;

    private $finance = null;
    private $params = null;
    private $totals = null;
    private $totalTypes = ['totals', 'exceptions', 'departments', 'cards'];

    public function handleTickets ($dbname, $ticketID = 0) {
        
        $count = 0;
        $this->dbconnect ($dbname);
        
        $pendingTicketsTable = TableRegistry::get ('PendingTickets');
        $tickets = TableRegistry::get ('Tickets');
        $ticketItems = TableRegistry::get ('TicketItems');
        $ticketItemAddons = TableRegistry::get ('TicketItemAddons');
        $ticketTaxes = TableRegistry::get ('TicketTaxes');
        $ticketTenders = TableRegistry::get ('TicketTenders');

        /*
         *
         * Get business level processing parameters
         *
         */
        
        $bu = TableRegistry::get ('BusinessUnits')
									->find ()
									->where (["business_unit_id" => 0])
									->first ();
        
        if ($bu) {
            
            $this->params = json_decode ($bu ['params'], true);
        }

        $query = null;

        if ($ticketID > 0) {
            
            $query =
                $pendingTicketsTable
                   ->find ()
                   ->where (['consumed' => 0,
                             'id' => $ticketID]);
        }
        else {
            
            $query =
                $pendingTicketsTable
                   ->find ()
                   ->where (['consumed' => 0])
                   ->order (['id' => 'desc'])
                   ->limit (100);
        }

        foreach ($query as $pending) {
            
            $postProcessing = [];  // additional processing for this ticket
            
            $ticket = json_decode ($pending ['json'], true);
            
            if (isset ($ticket ['ticket'])) {

                $ticket = $ticket ['ticket'];
                $count ++;
                
                if (isset ($ticket ['pos_session_id'])) {
                    
                    $ticket ['pos_session_no'] = $ticket ['pos_session_id'];   // session id in the pos db, session no in the BO
                }
                
                unset ($ticket ['id']);  // get a new id...

                $customer = null;
                if (isset ($ticket ['customer'])) {
						  
                    if (isset ($ticket ['customer'] ['action'])) {  // add/update customer
								
                        $customer = $ticket ['customer'];
                    }
						  
                    unset ($ticket ['customer']);  // so it isn't created in cascade save
                }
                
                foreach (['ticket_items', 'ticket_tenders'] as $table) {
                    
                    if (isset ($ticket [$table])) {
								
                        foreach ($ticket [$table] as &$t) {
                            
                            unset ($t ['id']);
                            
                            if (isset ($t ['data_capture']) && is_array ($t ['data_capture'])) {
                                
                                $t ['data_capture'] = json_encode ($t ['data_capture']);
                            }
                        }
                    }
                }

                if (isset ($ticket ['aux_receipts'])) {

                    $ticket ['ticket_text'] .= "\n\n" . $ticket ['aux_receipts'];
                }

                $this->ticket = $tickets->newEntity ($ticket);
                
                if ($t = $tickets->save ($this->ticket)) {
						  
                    if (isset ($this->ticket ['ticket_items'])) {
                        
                        foreach ($this->ticket ['ticket_items'] as $ti) {

                            // can't get cake to save past 3 levels so do it manually
									 
                            if (isset ($ti ['ticket_item_addons']) && (count ($ti ['ticket_item_addons']) > 0)) {

                                foreach ($ti ['ticket_item_addons'] as $tia) {
												
                                    $tia = $ticketItemAddons->newEntity ($tia);
                                    $tia ['ticket_item_id'] = $ti ['id'];
                                    $ticketItemAddons->save ($tia);
                                }
                            }
                        }
                    }

                    // add/upldate to post processing/finance

                    if ($customer != null) {
                        
                        $customer ['business_unit_id'] = $ticket ['business_unit_id'];
                        
                        $postProcessing [] = ['method' => 'customer_update',
                                              'params' => ['customer' => $customer,
                                                           'ticket' => $t]];
                    }
                }
                else {
                    $this->log ('ticket save failed...', 'error');
                    return false;
                }
            }
            else {
					 
                $this->ticket = $ticket;
            }
				
            $this->initTotals ();
				
            switch ($ticket ['state']) {

					 case SUSPEND:

						  // $ticket = $this->merge ($ticket);
						  break;
            }
            
            $ticketType = $ticket ['ticket_type'];
            
            switch ($ticketType) {

					 case SALE:
					 case COMP_SALE:
					 case RETURN_SALE:
					 case SALE_NONTAX:
					 case CREDIT_REFUND:
					 case CREDIT_REVERSE:

						  // handle ticket sub-components
						  
						  foreach (['ticket_items', 'ticket_taxes', 'ticket_tenders'] as $t) {
								
								$this->$t (false);
						  }
						  
						  break;

					 case LOGIN:
					 case LOGOUT:

						  $status = ($ticketType == LOGIN) ? 1 : 0;
						  
						  if (isset ($this->ticket ['pos_unit_id'])) {
								
								$connection = ConnectionManager::get ('default');
								$posUnitID = $this->ticket ['pos_unit_id'];
								$employeeID = $this->ticket ['employee_id'];
								$query = $connection->execute ("update pos_units set login_time = current_timestamp, employee_id = $employeeID, status = $status where id = $posUnitID");
						  }
						  
						  break;
						  
					 case NO_SALE:
					 case VOID:
						  
						  $this->saleException ($ticket);
						  break;
						  
					 case X_SESSION:
					 case Z_SESSION:

						  $this->session ($this->ticket, ['status' => 0]);
						  
						  // queue this up to the finance system
						  

						  if (isset ($this->params ['z_exports'])) {

								foreach ($this->params ['z_exports'] as $export) {
									 
									 $postProcessing [] = ['method' => $export ['method'],
																  'params' => ['ticket' => $ticket,
																					'params' => $export]];
								}
						  }
                    
						  break;
            }
            
            foreach ($postProcessing as $p) {

                $method = $p ['method'];
                $this->$method ($p ['params']);
            }
            
            $pending ['consumed'] = 1;
            $pendingTicketsTable->save ($pending);
        }
        
        $this->jsonResponse (['status' => 0]);
    }

    private function ticket_items ($reverse) {
        
        $invItems = TableRegistry::get ('InvItems');
        $buID = $this->ticket ['business_unit_id'];

        foreach ($this->ticket ['ticket_items'] as $ti) {

            if ($ti ['complete']) {

                continue;
            }
            
            // Inventory
				
            $invItem = $invItems
                     ->find ()
                     ->where (['item_id' => $ti ['item_id'],
                               'business_unit_id' => $buID])
                     ->first ();

            if ($invItem && ($invItem ['supplier_id'] > 0)) {

                $invItem ['on_hand_quantity'] -= $ti ['quantity'];
                $invItem ['on_hand_count'] -= $ti ['quantity'];
					 
                if ($invItems->save ($invItem)) {

                    if ($invItem ['on_hand_quantity'] < $invItem ['on_hand_req']) {

                        $msg = __ ('Low stock warning: ') . $ti ['sku'] . ' ' . $ti ['item_desc'] . ', ' . __ ('on hand quantity') . ' ' . $invItem ['on_hand_quantity'];

                        $this->message ($invItem ['business_unit_id'], $msg);
                    }
                }
                else {
						  
                    $this->log ('inv update failed... ', 'error');
                    $this->log ($ti, 'error');
                }
            }

            // cashier added an item
				
            if (isset ($ti ['add_item'])) {

                $this->addItem ($ti ['add_item']);
            }

            // sum for each business unit (store, corp...)

            foreach ($this->bus as $businessUnit) {
					 
                $bu = $businessUnit ['id'];
                $summaryType = 0;
                foreach ($businessUnit ['total_times'] as $totalTime) {

                    $total = false;
                    $discounts = 0;

                    if (isset ($ti ['ticket_item_addons'])) {
								
                        $table = TableRegistry::get ('SalesExceptionTotals');
								
                        foreach ($ti ['ticket_item_addons'] as $tia) {
									 
                            $discounts += $tia ['addon_amount'] * $tia ['addon_quantity'];
									 
                            $query = $table->find ()->where (['business_unit_id' => $bu,
                                                              'summary_type' => $summaryType,
                                                              "start_time = '$totalTime'",
                                                              'exception_key' => 'discounts']);
									 
									 
                            if ($total = $query->first ()) {
										  
                                if ($reverse) {
                                    
                                    $total ['quantity'] -= $ti ['quantity'];
                                    $total ['amount'] -= $discounts;
                                }
                                else {
                                    
                                    $total ['quantity'] += $ti ['quantity'];
                                    $total ['amount'] += $discounts;
                                }
                            }
                            else {
										  
                                $total = $table->newEntity (['business_unit_id' => $bu,
                                                             'pos_id' => $this->posNo,
                                                             'start_time' => $totalTime,
                                                             'amount' => $discounts,
                                                             'quantity' => $tia ['addon_quantity'],
                                                             'exception_key' => 'discounts',
                                                             'exception_desc' => $tia ['addon_description'],
                                                             'summary_type' => $summaryType]);
                            }
									 
                            $table->save ($total);
                        }
                    }
						  
                    $table = TableRegistry::get ('SalesDepartmentTotals');
                    $query = $table->find ()->where (['business_unit_id' => $bu,
                                                      'summary_type' => $summaryType,
                                                      "start_time = '$totalTime'",
                                                      'department_id' => $ti ['department_id']]);
                    switch ($ti ['state']) {

								case TICKET_ITEM_STANDARD:
								case TICKET_ITEM_RETURN_ITEM:
									 
									 if ($total = $query->first ()) {
										  
										  if ($reverse) {

												$total ['quantity'] -= $ti ['quantity'];
												$total ['amount'] -= $ti ['amount'] * $ti ['quantity'] + $discounts;
										  }
										  else {
												
												$total ['quantity'] += $ti ['quantity'];
												$total ['amount'] += $ti ['amount'] * $ti ['quantity'] + $discounts;
										  }
									 }
									 else {
										  
										  $total = $table->newEntity (['business_unit_id' => $bu,
																				 'pos_id' => $this->posNo,
																				 'start_time' => $totalTime,
																				 'amount' => ($ti ['amount'] * $ti ['quantity'] + $discounts),
																				 'quantity' => $ti ['quantity'],
																				 'department_id' => $ti ['department_id'],
																				 'summary_type' => $summaryType]);
									 }
									 
									 $table->save ($total);

									 $table = TableRegistry::get ('SalesItemTotals');
									 $query = $table->find ()->where (['business_unit_id' => $bu,
																				  'summary_type' => $summaryType,
																				  "start_time = '$totalTime'",
																				  'sales_item_key' => $ti ['sku']]);

									 if ($total = $query->first ()) {

										  if ($reverse) {
												
												$total ['quantity'] -= $ti ['quantity'];
												$total ['amount'] -= $ti ['amount'] * $ti ['quantity'];
										  }
										  else {
												
												$total ['quantity'] += $ti ['quantity'];
												$total ['amount'] += $ti ['amount'] * $ti ['quantity'];
										  }
									 }
									 else {
										  
										  $total = $table->newEntity (['business_unit_id' => $bu,
																				 'pos_id' => $this->posNo,
																				 'start_time' => $totalTime,
																				 'amount' => $ti ['amount'] * $ti ['quantity'],
																				 'quantity' => $ti ['quantity'],
																				 'sales_item_key' => $ti ['sku'],
																				 'sales_item_desc' => $ti ['item_desc'],
																				 'department_id' => $ti ['department_id'],
																				 'tax_group' => $ti ['tax_group_id'],
																				 'summary_type' => $summaryType]);
									 }
									 
									 $table->save ($total);
									 
									 // do tax/nontax

									 $salesKey = $ti ['tax_exempt'] == 1 ? 'NON_TAXABLE' : 'TAXABLE';

									 $amount = $ti ['amount'] * $ti ['quantity'];
									 
									 if (isset ($ti ['ticket_item_addons']) && (count ($ti ['ticket_item_addons'])) > 0) {

										  foreach ($ti ['ticket_item_addons'] as $tia) {

												$amount += $tia ['addon_amount'] * $tia ['addon_quantity'];
										  }
									 }
									 
									 $totals = TableRegistry::get ('SalesTotals');
									 
									 $query = $totals->find ()->where (['business_unit_id' => $bu,
																					'summary_type' => $summaryType,
																					"start_time = '$totalTime'",
																					'sales_key' => $salesKey]);
									 
									 if ($total = $query->first ()) {
										  
										  if ($reverse) {
												
												$total ['quantity'] -= $ti ['quantity'];
												$total ['amount'] -= $amount;
										  }
										  else {
                                    
												$total ['quantity'] += $ti ['quantity'];
												$total ['amount'] += $amount;
										  }
									 }
									 else {
										  
										  $total = $totals->newEntity (['business_unit_id' => $bu,
																				  'pos_id' => $this->posNo,
																				  'start_time' => $totalTime,
																				  'amount' => $amount,
																				  'quantity' => $ti ['quantity'],
																				  'sales_key' => $salesKey,
																				  'sales_desc' => $salesKey,
																				  'summary_type' => $summaryType]);
									 }
									 
									 $totals->save ($total);
                    }

                    $exceptionKey = '';
                    switch ($ti ['state']) {

								case TICKET_ITEM_RETURN_ITEM:
								case TICKET_ITEM_VOID_ITEM:

									 $exceptionKey = '';
									 switch ($ti ['state']) {

										  case TICKET_ITEM_RETURN_ITEM:

												$exceptionKey = 'return_items';
												break;
												
										  case TICKET_ITEM_VOID_ITEM:

												$exceptionKey = 'void_items';
												break;
									 }
									 
									 $table = TableRegistry::get ('SalesExceptionTotals');
									 $query =$table->find ()->where (['business_unit_id' => $bu,
																				 'summary_type' => $summaryType,
																				 "start_time = '$totalTime'",
																				 'exception_key' => $exceptionKey]);
									 
									 if ($total = $query->first ()) {
										  
										  $total ['amount'] += $ti ['amount'];
										  $total ['quantity'] += $ti ['quantity'];
										  
									 }
									 else {
										  
										  $total = $table->newEntity (['business_unit_id' => $bu,
																				 'pos_id' => $this->posNo,
																				 'start_time' => $totalTime,
																				 'amount' => $ti ['amount'],
																				 'quantity' => $ti ['quantity'],
																				 'exception_key' => $exceptionKey,
																				 'exception_desc' => $exceptionKey,
																				 'summary_type' => $summaryType]);
									 }
                            
									 $table->save ($total);
                    }
						  
                    $summaryType ++;
                }
            }
        }
    }

    private function ticket_taxes ($reverse) {
        
        if (!isset ($this->ticket ['ticket_taxes'])) return;
		  
        foreach ($this->ticket ['ticket_taxes'] as $tt) {
				
            foreach ($this->bus as $businessUnit) {

                $bu = $businessUnit ['id'];
                $summaryType = 0;
				    foreach ($businessUnit ['total_times'] as $totalTime) {
		  
                    $total = false;
                    $query = TableRegistry::get ('SalesTaxTotals')->find ()->where (['business_unit_id' => $bu,
                                                                                     'summary_type' => $summaryType,
                                                                                     "start_time = '$totalTime'",
                                                                                     'tax_group_id' => $tt ['tax_group_id']]);
                    $table = TableRegistry::get ('SalesTaxTotals');
                    $total = null;
						  
                    if ($total = $query->first ()) {
								
                        $total ['quantity'] += 1;
                        $total ['amount'] += $tt ['tax_amount'];


                    }
                    else {
								
                        $total = $table->newEntity (['business_unit_id' => $bu,
                                                     'pos_id' => $this->posNo,
                                                     'start_time' => $totalTime,
                                                     'amount' => $tt ['tax_amount'],
                                                     'quantity' => 1,
                                                     'tax_group_id' => $tt ['tax_group_id'],
                                                     'tax_desc' => $tt ['short_desc'],
                                                     'summary_type' => $summaryType]);
                    }
						  
                    $table->save ($total);
                    $summaryType ++;
                }
            }
        }
    }

    private function ticket_tenders ($reverse) {
        
        if (!isset ($this->ticket ['ticket_tenders'])) return;
		  
        foreach ($this->ticket ['ticket_tenders'] as $tt) {
				
            if ($tt ['complete']) {

                continue;
            }
            
            foreach ($this->bus as $businessUnit) {
                
                $bu = $businessUnit ['id'];
                $summaryType = 0;
					 foreach ($businessUnit ['total_times'] as $totalTime) {

                    $tt ['tender_type'] = strtoupper ($tt ['tender_type']);

                    $total = false;
                    $table = TableRegistry::get ('SalesTotals');
                    $query =$table->find ()->where (['business_unit_id' => $bu,
                                                     'summary_type' => $summaryType,
                                                     "start_time = '$totalTime'",
                                                     'sales_key' => $tt ['tender_type']]);
						  
                    $table = TableRegistry::get ('SalesTotals');
                    $total = null;

						  
                    $amount = ($tt ['tendered_amount'] - $tt ['returned_amount']);
                    $quantity = 1;
                    
                    if ($total = $query->first ()) {
                        
                        if ($reverse) {

                            $total ['quantity'] -= $quantity;
                            $total ['amount'] -= $amount;
                        }
                        else {
                            
                            $total ['quantity'] += $quantity;
                            $total ['amount'] += $amount;
                        }
                    }
                    else {
								
                        $total = $table->newEntity (['business_unit_id' => $bu,
                                                     'pos_id' => $this->posNo,
                                                     'start_time' => $totalTime,
                                                     'amount' => $amount,
                                                     'quantity' => 1,
                                                     'sales_key' => $tt ['tender_type'],
                                                     'sales_desc' => $tt ['tender_type'],
                                                     'summary_type' => $summaryType]);
                    }
						  
                    $table->save ($total);
                    $summaryType ++;
                }
            }
        }
    }

    private function saleException ($ticket) {

        $saleException = '';
        
        switch ($ticket ['ticket_type']) {
				case NO_SALE:
					 
					 $saleException = 'no_sales';
					 break;
					 
				case VOID:
					 
					 $saleException = 'void_sales';
					 break;
					 
        }
        
        foreach ($this->bus as $businessUnit) {

            $bu = $businessUnit ['id'];
            $summaryType = 0;
		      foreach ($businessUnit ['total_times'] as $totalTime) {
					 
                $table = TableRegistry::get ('SalesExceptionTotals');
                $query =$table->find ()->where (['business_unit_id' => $bu,
                                                 'summary_type' => $summaryType,
                                                 "start_time = '$totalTime'",
                                                 'exception_key' => $saleException]);

                if ($total = $query->first ()) {
						  
                    $total ['amount'] += $ticket ['total'];
                    $total ['quantity'] += 1;

                }
                else {
						  
                    $total = $table->newEntity (['business_unit_id' => $bu,
                                                 'pos_id' => $this->posNo,
                                                 'start_time' => $totalTime,
                                                 'amount' => $ticket ['total'],
                                                 'quantity' => 1,
                                                 'exception_key' => $saleException,
                                                 'exception_desc' => $saleException,
                                                 'summary_type' => $summaryType]);
                }
					 
                $table->save ($total);
                $summaryType ++; 
            }
        }
    }

 	 function initTotals () {
		  
        $getSummaryDate = function ($t, $tz) {
				
            $date = new DateTime ($t, new DateTimeZone ('UTC'));
            $date->setTimezone (new DateTimeZone ($tz));
            return $date->format ('Y-m-d');
        };

		  $this->bu = $this->ticket ['business_unit_id'];
        $this->posNo = $this->ticket ['pos_no'];
		  $t = $this->ticket ['complete_time'];

        $this->bus = [];
		  
        $bu = TableRegistry::get ('BusinessUnits')
									->find ()
									->where (['id' => $this->bu])
									->first ();
		  
		  $utc = $getSummaryDate ($t, $bu ['timezone']);

		  $bu ['total_times'] = [date ('Y-m-d H:00:00', strtotime ($t)),
										 $utc . sprintf (' %02d:00:00', $this->tzOffset ($bu ['timezone'])), 
										 substr ($utc, 0, 8) . "01" . sprintf (' %02d:00:00', $this->tzOffset ($bu ['timezone']))];
		  
        $this->bus [] = $bu->toArray ();
        $timezone = $bu ['timezone'];

        while ($bu ['business_unit_id'] != 0) {
				
				$bu = TableRegistry::get ('BusinessUnits')
										 ->find ()
										 ->where (['id' => $bu ['business_unit_id']])
										 ->first ();
				
				$utc = $getSummaryDate ($t, $bu ['timezone']);
				
				$bu ['total_times'] = [date ('Y-m-d H:00:00', strtotime ($t)),
											  $utc . sprintf (' %02d:00:00', $this->tzOffset ($bu ['timezone'])), 
											  substr ($utc, 0, 8) . "01" . sprintf (' %02d:00:00', $this->tzOffset ($bu ['timezone']))];
				
            $this->bus [] = $bu->toArray ();
        }
    }
    
    private function initTotalsSave () {
        
        $this->bu = $this->ticket ['business_unit_id'];
        $this->posNo = $this->ticket ['pos_no'];

        $ticketBu = null;
        $this->bus = [];
		  
        $query = TableRegistry::get ('BusinessUnits')
										->find ()
										->where (['id' => $this->bu]);
		  
        $bu = $query->first ();
        $this->bus [] = $bu->toArray ();
        $timezone = $bu ['timezone'];

        while ($bu ['business_unit_id'] != 0) {
				
            $query = TableRegistry::get ('BusinessUnits')
											 ->find ()
											 ->where (['id' => $bu ['business_unit_id']]);
            $bu = $query
                ->first ();
				
            $this->bus [] = $bu->toArray ();
        }
		  
        /**
         *
         * Day and Month need to take into account if midnight has been crossed (or will be)
         * for the daily and monthly summaries
         *
         */
		  
        $t = $this->ticket ['start_time'];
        $tz = $bu ['timezone'];
		  
        $utcOffsets = ['America/New_York' => 5,
                       'America/Chicago' => 6,
                       'America/Denver' => 7,
                       'America/Los_Angeles' => 8,
                       'America/Anchorage' => 9,
                       'HST' => 10,
                       'Europe/London' => 0,
                       'Europe/Copenhagen' => 23];
        
        $getSummaryDate = function ($t, $tz) {
            
            $date = new DateTime ($t, new DateTimeZone ('UTC'));
            $date->setTimezone (new DateTimeZone ($tz));
            return $date->format ('Y-m-d');
        };
        
        $utc = $getSummaryDate ($t, $tz);
        
        $this->totalTimes = [date ('Y-m-d H:00:00', strtotime ($t)),
                             $utc . sprintf (' %02d:00:00', $utcOffsets [$tz]), 
                             substr ($utc, 0, 8) . "01" . sprintf (' %02d:00:00', $utcOffsets [$tz])];

    }

    /**
     * save the session
     */
    private function session ($ticket, $dataCapture) {
        
        $status = -1;
        if (isset ($dataCapture ['status'])) {

            $status = $dataCapture ['status'];
        }
        
        $posSessionsTable = TableRegistry::get ('PosSessions');
        $posSessionTotalsTable = TableRegistry::get ('PosSessionTotals');
        
        $this->totals = ['business_unit_id' => $ticket ['business_unit_id'],
                         'pos_no' => $ticket ['pos_no'],
                         'ticket_no' => $ticket ['ticket_no'],
                         'session_no' => $ticket ['pos_session_no'],
                         'pos_complete_time' => $ticket ['complete_time'],
                         'status' => $status,
                         'data_capture' => json_encode ($dataCapture)];
        
        $this->totals = $posSessionsTable
                      ->newEntity ($this->totals);
        
        $posSessionsTable->save ($this->totals);

        $this->totals ['totals'] = [];
        
        foreach ($this->totalTypes as $totalType) {
            
            foreach ($ticket [$totalType] as $total) {
                
                $t = ['pos_session_id' => $this->totals ['id'],
                      'total_key' => strtolower ($total ['desc']),
                      'amount' => $total ['amount']];
                
                $this->totals ['totals'] [] = $t;
                
                $t = $posSessionTotalsTable
                   ->newEntity ($t);
                
                $posSessionTotalsTable
                    ->save ($t);
            }
        }

        if (isset ($ticket ['drawer_counts'])) {

            $posSessionCountsTable = TableRegistry::get ('PosSessionCounts');

            foreach ($ticket ['drawer_counts'] as $count) {

                $sessionCount = $posSessionCountsTable
                              ->newEntity (['pos_session_id' => $posSession ['id'],
                                            'denom_id' => $count ['denom_id'],
                                            'denom_count' => $count ['denom_count']]);
					 
                $posSessionCountsTable
                    ->save ($sessionCount);

            }
        }
		  
    }

    private function customer_update ($params) {

        $customer = $params ['customer'];
        $cid = 0;
        $table = TableRegistry::get ('Customers');
        
        switch ($customer ['action']) {

				case 'add':
                
					 $customer = $table->newEntity ($customer);
					 $customer = $table->save ($customer);
					 $cid = $customer ['id'];
					 
                $table->save ($customer);

					 // update the ticket with the customer ID
					 
					 $params ['ticket'] ["customer_id"] = $cid;
					 $table = TableRegistry::get ('Tickets')->save ($params ['ticket']);
					 break;

				case 'update':

					 $c = $table
               ->find ()
               ->where (['phone' => $customer ['phone']])
               ->first ();
                
					 if ($c) {

						  $c ['fname'] = $customer ['fname'];
						  $c ['lname'] = $customer ['lname'];
						  $c ['contact'] = $customer ['contact'];
						  $c ['email'] = $customer ['email'];
						  $c ['phone'] = $customer ['phone'];
						  $cid = $c ['id'];
                    
						  $table->save ($c);
					 }
                
					 break;
        }

        if ($cid > 0) {
            
            $batchEntry = ['business_unit_id' => $customer ['business_unit_id'],
									'update_table' => 'customers',
									'update_id' => $cid,
									'update_action' => 0,
									'execution_time' => time ()];
            
            $batchEntriesTable = TableRegistry::get ('BatchEntries');
            $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
            $batchEntriesTable->save ($batchEntry);
        }
    }

    private function z_to_csv ($params) {

        $csv = "type; sub_type; total; quantity\n";
        foreach ($this->totals ['totals'] as $total) {

            $csv .= strtoupper (str_replace ('.', ',', sprintf ("%s;%s;%.2f;%d\n", $total ['total_type'], $total ['total_sub_type'], $total ['amount'], $total ['quantity'])));
        }
        
        file_put_contents (sprintf ('/home/%s/download/termial_%d_%s.csv', $params ['params'] ['user'], $this->totals ['pos_no'], date ('YmdHi')), $csv);
    }
    

    private function addItem ($item) {
        
        $items = TableRegistry::get ('Items');
        
        $prices = TableRegistry::get ('ItemPrices');

        $tmp = $items->newEntity (['sku'=> $item ['sku'],
                                   'item_desc' => strtoupper ($item ['item_desc']),
                                   'department_id'=> $item ['department_id']]);
        
        $tmp = $this->save ('Items', $tmp);

        $itemPrice = $prices->newEntity (['item_id'=> $tmp ['id'],
                                          'business_unit_id' => $this->bu,
                                          'tax_group_id' => $item ['tax_group_id'],
                                          'tax_inclusive' => 0,
                                          'tax_exempt' => ($item ['tax_group_id'] > 0) ? 1 : 0,
                                          'price' => floatVal ($item ['price']),
                                          'cost' => 0,
                                          'pricing' => json_encode (['class' => 'standard',
                                                                     'amount' => floatVal ($item ['price']),
                                                                     'price' => floatVal ($item ['price']),
                                                                     'cost' => 0])]);

        $this->save ('ItemPrices', $itemPrice);

        if ($item ['deposit_id'] > 0) {

            $links = TableRegistry::get ('ItemPrices');
            
            $itemLink = $links->newEntity (['item_id' => $tmp ['id'],
                                            'item_link_id' => $item ['deposit_id'],
                                            'link_type' => 0]);
            
            $this->save ('ItemLinks', $itemLink);
				
        }
    }

    private function message ($buID, $msg) {

        $message = ['business_unit_id' => $buID,
                    'pos_unit_id' => 0,
                    'method' =>'message',
                    'message' => json_encode (['header' => __ ('Incoming Message'),
                                               'prority' => 0,
                                               'body' => $msg])];
        
        $posMessages = TableRegistry::get ('PosMessages');
        $batchEntriesTable = TableRegistry::get ('BatchEntries');

        $posMessage = $posMessages->newEntity ($message);
        $posMessage = $posMessages->save ($posMessage);

        $m = ['business_unit_id' => $buID,
              'pos_unit_id' => 0,
              'update_table' => 'pos_messages',
              'update_id' =>  $posMessage ['id'],
              'update_action' => 0,
              'execution_time' => time ()];
        
        $batchEntry = $batchEntriesTable->newEntity ($m);
        $batchEntriesTable->save ($batchEntry);
    }

}

function guid () {
	 
	 if (function_exists ('com_create_guid') === true) {
		  
		  return trim (com_create_guid (), '{}');
	 }
	 
	 $data = openssl_random_pseudo_bytes (16);
	 $data[6] = chr (ord ($data[6]) & 0x0f | 0x40); // set version to 0100
	 $data[8] = chr (ord ($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	 return vsprintf ('%s%s-%s-%s-%s-%s%s%s', str_split (bin2hex ($data), 4));
}
<?php
/**
 *
 * Copyright (C) 2022 multiPos, LLC
 * <http://www.multiPos.cloud>
 *
 * All rights are reserved. No part of the work covered by the copyright
 * hereon may be reproduced or used in any form or by any means graphic,
 * electronic, or mechanical, including photocopying, recording, taping,
 * or information storage and retrieval systems -- without written
 * permission signed by an officer of multiPos, LLC.
 *
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Datasource\ConnectionManager;

class DashboardController extends PeriodController {

    public $totals;
    public $date;

    public function initialize (): void {
        
        parent::initialize ();
    }
    
    function index (...$args) {

        $startDate = time ();
        $this->totals = [];
        $summaryType = 1;
        $this->periodType = 'weekly';
        $this->year = '';
        
		  while (count ($args) > 0) {
				
				$key = array_shift ($args);
				$val = array_shift ($args);

            switch ($key) {

					 case 'start_date':

						  if (is_numeric ($val)) {

								$startDate = intval ($val);
						  }
						  else {
						  
								$startDate = strtotime ($val);
						  }
						  break;

					 case 'ytd':

						  $startDate = strtotime ($this->startOfYear ());
						  $this->periodType = 'ytd';
						  $summaryType = 2;
						  $this->year = $val;
						  break;
            }
        }

		  // get the periods structure
		  
        $this->getPeriods ($startDate, $this->periodType);
		  
        $len = count ($this->periods);
        
        // initialize report rows
		  
        $this->totals = ['sales' => ['total' => array_fill (0, $len + 1, 0),
                                     'net' => array_fill (0, $len + 1, 0),
                                     'tax' => array_fill (0, $len + 1, 0),
                                     'cash' => array_fill (0, $len + 1, 0),
                                     'credit' => array_fill (0, $len + 1, 0),
                                     'debit' => array_fill (0, $len + 1, 0),
                                     'wallet' => array_fill (0, $len + 1, 0),
                                     'ebt_foodstamp' => array_fill (0, $len + 1, 0),
                                     'card' => array_fill (0, $len + 1, 0),
                                     'check' => array_fill (0, $len + 1, 0),
                                     'other' => array_fill (0, $len + 1, 0),
                                     'gift_card' => array_fill (0, $len + 1, 0),
                                     'account' => array_fill (0, $len + 1, 0),
                                     'split' => array_fill (0, $len + 1, 0),
                                     'taxable' => array_fill (0, $len + 1, 0),
                                     'non_taxable' => array_fill (0, $len + 1, 0),
                                     'avg_sale' => array_fill (0, $len + 1, 0),
                                     'customer_count' => array_fill (0, $len + 1, 0),
                                     'discounts' => array_fill (0, $len + 1, 0),
                                     'tips' => array_fill (0, $len + 1, 0),
                                     'void_sales' => array_fill (0, $len + 1, 0),
                                     'void_items' => array_fill (0, $len + 1, 0),
                                     'refunds' => array_fill (0, $len + 1, 0),
                                     'no_sales' => array_fill (0, $len + 1, 0)]];
        
        $minYear = date ('Y');
        
        $query = TableRegistry::get ('Departments')
										->find ()
										->order (['department_order' => 'asc']);
        
        $departments = [];
        foreach ($query as $department) {
				
            $department ['totals'] = array_fill (0, $len + 1, 0);
            $departments [] = $department;      
        }
        
        $this->totals ['department_totals'] = array_fill (0, $len + 1, 0);
		  
        $col = 0;
        foreach ($this->periods as $period) {
				
            $start = $period ['start'];
            $end = $period ['end'];
				
            $where = ["start_time = '$start'",
                      "summary_type = $summaryType",
                      'business_unit_id = ' . $this->merchant ['bu_id']];
            
            $query = TableRegistry::get ('SalesTotals')
											 ->find ()
											 ->where ($where);
				
            foreach ($query as $salesTotal) {
                
                switch ($salesTotal ['sales_key']) {
								
						  case 'TAXABLE':
						  case 'NON_TAXABLE':
						  case 'ROUND_DIFF':
								
								switch ($this->merchant ['locale']) {

									 case 'da_DK':
										  break;

									 default:
										  $this->totals ['sales'] ['net'] [$col] += $salesTotal ['amount'];
										  $this->totals ['sales'] ['net'] [$len] += $salesTotal ['amount'];
										  break;
								}
								
								break;
								
						  case 'TIPS':
								break;

						  default:

								$this->totals ['sales'] ['total'] [$col] += $salesTotal ['amount'];
								$this->totals ['sales'] ['total'] [$len] += $salesTotal ['amount'];
                }
					 
                switch ($salesTotal ['sales_key']) {
								
						  case 'CASH':
						  case 'CHECK':
						  case 'CREDIT':
						  case 'CARD':
						  case 'DEBIT':
						  case 'WALLET':
						  case 'EBT_FOODSTAMP':
						  case 'MOBILE':
						  case 'GIFT_CARD':
						  case 'GIFT_CERT':
						  case 'ACCOUNT':
						  case 'OTHER':
						  case 'BUY_NOW_PAY_LATER':
						  case 'SQUARE_ACCOUNT':
						  case 'EXTERNAL':

								$this->totals ['sales'] [strtolower ($salesTotal ['sales_key'])] [$col] += $salesTotal ['amount'];
								$this->totals ['sales'] [strtolower ($salesTotal ['sales_key'])] [$len] += $salesTotal ['amount'];
								break;
								
						  case 'TAXABLE':

								switch ($this->merchant ['locale']) {
										  
									 case 'da_DK':
										  break;

									 default:

										  $this->totals ['sales'] ['taxable'] [$col] += $salesTotal ['amount'];
										  $this->totals ['sales'] ['taxable'] [$len] += $salesTotal ['amount'];
										  break;
								}

								break;
								
						  case 'NON_TAXABLE':
								
								switch ($this->merchant ['locale']) {

									 case 'da_DK':
										  break;

									 default:

										  $this->totals ['sales'] ['non_taxable'] [$col] += $salesTotal ['amount'];
										  $this->totals ['sales'] ['non_taxable'] [$len] += $salesTotal ['amount'];
										  break;
								}
                }
            }
								
				$query = TableRegistry::get ('SalesExceptionTotals')
											 ->find ()
											 ->where ($where);
				
				foreach ($query as $salesExceptionTotal) {
					 
					 switch ($salesExceptionTotal ['exception_key']) {
								
						  case 'void_sales':
						  case 'void_items':
						  case 'discounts':
						  case 'refunds':
								
								$this->totals ['sales'] [$salesExceptionTotal ['exception_key']] [$col] = $salesExceptionTotal ['amount'];
								$this->totals ['sales'] [$salesExceptionTotal ['exception_key']] [$len] += $salesExceptionTotal ['amount'];
								break;
								
						  default:
								break;
					 }
				}
				
            switch ($this->merchant ['locale']) {
                    
					 case 'da_DK':
						  
						  $query = TableRegistry::get ('SalesTaxTotals')
														->find ()
														->where (["start_time = '$start'",
																	 "summary_type = $summaryType",
																	 'business_unit_id = ' . $this->merchant ['bu_id']]);
						  
						  foreach ($query as $salesTotal) {
								
								$this->totals ['sales'] ['tax'] [$col] = $salesTotal ['amount'];
								$this->totals ['sales'] ['tax'] [$len] += $salesTotal ['amount'];
						  }
						  break;
						  
					 default:
						  
						  $query = TableRegistry::get ('SalesTaxTotals')
														->find ()
														->where (["start_time = '$start'",
																	 "summary_type = $summaryType",
																	 'business_unit_id = ' . $this->merchant ['bu_id']]);
						  
						  foreach ($query as $salesTotal) {
								
								$this->totals ['sales'] ['tax'] [$col] = $salesTotal ['amount'];
								$this->totals ['sales'] ['tax'] [$len] += $salesTotal ['amount'];
						  }

            }
				

            /**
             * departments
             */
            
            foreach ($departments as &$dept) {
                
                $query = TableRegistry::get ('SalesDepartmentTotals')
												  ->find ()
												  ->where (["start_time = '$start'",
																"department_id = ".$dept ['id'],
																"summary_type = $summaryType",
																'business_unit_id = '.$this->merchant ['bu_id']]);
                
                foreach ($query as $salesTotal) {
                    
                    $dept ['totals'] [$col] += $salesTotal ['amount'];
                    $dept ['totals'] [$len] += $salesTotal ['amount'];

                    $this->totals ['department_totals'] [$col] += $salesTotal ['amount'];
                    $this->totals ['department_totals'] [$len] += $salesTotal ['amount'];
                }
            }

            /**
             * ticket based summary
             */

            $start = $period ['start'];
            $end = $period ['end'];
            $ticketsTable = TableRegistry::get ('Tickets');

				$where = ["start_time between '$start' and '$end'",
                      "total != 0",
                      "ticket_type in (0, 3, 8, 11)"];

				if ($this->merchant ['bu_index'] > 0) {

					 $where [] = "business_unit_id = " .  $this->merchant ['bu_id'];
				}
				
				$count = $ticketsTable
                   ->find ()
                   ->where ($where)
						 ->count ();
				
            if ($count > 0) {
					 
                $this->totals ['sales'] ['customer_count'] [$col] += $count;
                $this->totals ['sales'] ['customer_count'] [$len] += $count;
            }
				
            if ($this->totals ['sales'] ['customer_count'] [$col] > 0) {
                
                $this->totals ['sales'] ['avg_sale'] [$col] = $this->totals ['sales'] ['total'] [$col] / $this->totals ['sales'] ['customer_count'] [$col];
                $this->totals ['sales'] ['avg_sale'] [$len] = $this->totals ['sales'] ['total'] [$len] / $this->totals ['sales'] ['customer_count'] [$len];
            }

            /**
             * No Sale/open drawer
             */

				$where = ["start_time between '$start' and '$end'",
                      "ticket_type = 2"];
				
				if ($this->merchant ['bu_index'] > 0) {

					 $where [] = ["business_unit_id" => $this->merchant ['bu_id']];
				}

            $noSales = $ticketsTable
                   ->find ()
                   ->where ($where)
						 ->count ();
				
            if ($noSales > 0) {
					 
                $this->totals ['sales'] ['no_sales'] [$col] += $noSales;
                $this->totals ['sales'] ['no_sales'] [$len] += $noSales;
            }

				$where = ["start_time between '$start' and '$end'",
							 "tip > 0"];

				if ($this->merchant ['bu_index'] > 0) {

					 $where [] = ["business_unit_id" => $this->merchant ['bu_id']];
				}
				
				$tips = $ticketsTable->find ()
											->where ($where);
				
				foreach ($tips as $t) {
					 
					 $this->totals ['sales'] ['tips'] [$col] += $t ['tip'];
					 $this->totals ['sales'] ['tips'] [$len] += $t ['tip'];
				}
				
            $col ++;
				
        }  // end of periods....
		  
        /*
         * throw out the departments with no sales
         */
        
        $this->totals ['departments'] = [];
        
        foreach ($departments as $department) {
            
            if ($department ['totals'] [$len] == 0) { continue; }  // skip departments with no sales...

            $this->totals ['departments'] [] = $department;
        }
		  
        /*
         * get the earliest year of reporting
         */

        $min = TableRegistry::get ('SalesTotals')
									 ->find ('all')
									 ->select (['min' => $query->func ()->min ('start_time')])
									 ->where (['business_unit_id = ' . $this->merchant ['bu_id']])
									 ->first ();
		  
        $curYear = $minYear = intVal (date ('Y'));
        $years = [0 => __ ('Year')];
        for ($y=$curYear; $y >= $minYear; $y --) { $years [$y] = $y; }
		  
		  $this->set (['prev' => $startDate - ONE_WEEK,
							'next' => $startDate + ONE_WEEK,
							'len' => $this->len,
							'periods' => $this->periods,
							'minYear' => $minYear,
							'totals' => $this->totals,
							'year' => $this->year,
							'years' => $years]);
	 }

	 function startOfYear () {
		  
        $start = TableRegistry::get ('SalesTotals')
										->find ()
										->where (['summary_type = 2'])
										->order (['id asc'])
										->first ();

		  return $start ['start_time'];
	 }
	 
}

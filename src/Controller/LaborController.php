<?php
/**
 *
 * Copyright (C) 2022 multiPos, LLC
 * <http://www.posAppliance.com>
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
use Cake\ORM\TableRegistry;

class LaborController extends PeriodController {

    public function initialize (): void {
        
        parent::initialize ();
    }

    public function index (...$args) {

        $this->debug ("labor...");
		  $this->debug ($args);
		  
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
		  
        $laborDepartment = TableRegistry::get ('Departments')
                         ->find ()
                         ->where (['department_type' => LABOR_DEPARTMENT])
                         ->first ();

        if (!$laborDepartment) {

            $this->debug ("no labor department...");
            return;
        }
		  
        $laborDepartmentID = $laborDepartment ['id'];

        $employees = [];
        $e = TableRegistry::get ('Employees')
								  ->find ('all');
		  
        foreach ($e as $employee) {

            $employees [] = $employee->toArray ();
        }
                
        $summaryType = 1;
        $this->periodType = 'weekly';
        $this->year = '';

        $ticketsTable = TableRegistry::get ('Tickets');

		  $this->getPeriods ($startDate, $this->periodType);
		  
        foreach ($employees as &$employee) {

            $i = 0;
            $periodCount = count ($this->periods) + 1;
            
            $employee ['periods'] = array_fill (0, count ($this->periods) + 1, ['amount' => 0, 'quantity' => 0, 'cost' => 0]);
                     
            foreach ($this->periods as $period) {
                
                $start = $period ['start'];
                $end = $period ['end'];
					 
                $tickets = $ticketsTable->find ()
													 ->where (['clerk_id' => $employee ['id'],
																  "complete_time >= '$start'",
																  "complete_time < '$end'"])
													 ->join (['table' => 'ticket_items',
																 'type' => 'right',
																 'conditions' => ['ticket_items.metric > 0.0',
																						'Tickets.id = ticket_items.ticket_id']])
													 ->contain (['TicketItems']);
					 					 
                foreach ($tickets as $ticket) {

						  foreach ($ticket ['ticket_items'] as $ticketItem) {
																
								$employee ['periods'] [$i] ['amount'] += $ticketItem ['amount'];
								$employee ['periods'] [$i] ['cost'] += $ticketItem ['cost'] * $ticketItem ['metric'];
								$employee ['periods'] [$i] ['quantity'] += $ticketItem ['quantity'];
								
								$employee ['periods'] [$periodCount - 1] ['amount'] += $ticketItem ['amount'];
								$employee ['periods'] [$periodCount - 1] ['cost'] += $ticketItem ['cost'] * $ticketItem ['metric'];
								$employee ['periods'] [$periodCount - 1] ['quantity'] += $ticketItem ['quantity'];
						  }
                }
					 
					 $i ++;  // next period
           }
        }
		  
        $this->set (['periods' => $this->periods,
							'employees' => $employees,
							'len' => $this->len,
							'prev' => $startDate - ONE_WEEK,
							'next' => $startDate + ONE_WEEK]);
    }
}


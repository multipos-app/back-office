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
use Cake\ORM\TableRegistry;
use App\Controller\PosAppController;

class ItemHIstoryController extends PeriodController {

    public $date;
    public $departments;
	 
    public function initialize (): void {
        
        parent::initialize ();
    }

    function index (...$args) {
		  
        $this->departments = [];
        $this->periodType = 'weekly';
		  $this->conditions = ['summary_type = 1',
									  'business_unit_id = ' . $this->merchant ['bu_id']];
 
		  date_default_timezone_set ("UTC");

        $startDate = time ();
		  
        $this->debug ($args);
		  
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
						  
					 case 'department_id':
						  
						  $this->conditions [] = 'department_id = ' . $val;
						  break;
						  
					 case 'item_desc':
						  
						  $this->conditions [] = "sales_item_desc like '%" . $val . "%'";
						  break;
            }
        }
        
		  $this->debug ('item hist index... ' . time () . ' ' . $startDate . ' ' . date ('Y-m-d H:i', $startDate));

        $this->getPeriods ($startDate, $this->periodType);
        $len = count ($this->periods);
		  
        $tmp = [];
        for ($i = 0; $i < $this->len; $i ++) {
            $tmp [] = ['amount' => 0,
                       'quantity' => 0];
        }
        $tmp [] = ['amount' => 0,
                   'quantity' => 0];  // total for row

        $col = 0;
        foreach ($this->periods as $period) {

				$this->conditions ["start_time"] = $period ['start'];
				
            $query = TableRegistry::get ('SalesItemTotals')
											 ->find ()
											 ->where ($this->conditions)
											 ->order (['sales_item_desc' => 'asc']);
            
            foreach ($query as $salesTotal) {
					 
                if (!isset ($this->departments [$salesTotal ['department_id']])) {

                    $department = TableRegistry::get ('Departments')
															  ->find ()
															  ->where (['id' => $salesTotal ['department_id']])
															  ->first ();
						  
                    if ($department) {
								
                        $this->departments [$salesTotal ['department_id']] = ['department_desc' => $department ['department_desc'],
                                                                              'items' => []];
                    }
                }

                if (!isset ($this->departments [$salesTotal ['department_id']] ['items'] [$salesTotal ['sales_item_key']])) {
						  
                    $this->departments [$salesTotal ['department_id']] ['items'] [$salesTotal ['sales_item_key']] ['totals'] = $tmp;
                    $this->departments [$salesTotal ['department_id']] ['items'] [$salesTotal ['sales_item_key']] ['item_desc'] = $salesTotal ['sales_item_desc'];
						  
                }

                $quantity = $salesTotal ['quantity'];
                if ($salesTotal ['amount'] < 0) {

                    $quantity = $salesTotal ['quantity'] * -1;
                }
					 
                $this->departments [$salesTotal ['department_id']] ['items'] [$salesTotal ['sales_item_key']] ['totals'] [$col] = ['amount' => $salesTotal ['amount'],
                                                                                                                                   'quantity' => $quantity];
					 
                $this->departments [$salesTotal ['department_id']] ['items'] [$salesTotal ['sales_item_key']] ['totals'] [$this->len] ['amount'] += $salesTotal ['amount'];
                $this->departments [$salesTotal ['department_id']] ['items'] [$salesTotal ['sales_item_key']] ['totals'] [$this->len] ['quantity'] += $quantity;
					 
            }
				
            $col ++;
        }
        
        $depts = [null => __ ('Department')];
        $query = TableRegistry::get ('Departments')
										->find ()
										->order (['department_desc' => 'asc']);
        
        foreach ($query as $d) {
            
            $depts [$d ['id']] = $d ['department_desc'];
        }
		  
		  $this->set (['prev' => $startDate - ONE_WEEK,
							'next' => $startDate + ONE_WEEK,
							'len' => $this->len,
							'periods' => $this->periods,
							'departments' => $this->departments,
							'depts' => $depts]);
    }
}
?>

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
use App\Controller\PosAppController;
use Cake\I18n\Time;
use \DateTime;
use \DateTimeZone;

class HourlyController extends PeriodController {

    function index (...$args) {

        if (!$this->session) {

            return $this->logout ();
        }
		  
        Time::setToStringFormat('Y-m-d H:i:s');
        
        date_default_timezone_set ($this->tz ());
		  
        $location = null;
        $dow = $this->dow ();
        $open = 0;
        $close = 23;
        $this->periodType = 'weekly';
		  
        $businessUnitHours = [];
        $query = TableRegistry::get ('BusinessUnitHours')
										->find ()
										->where (['business_unit_id' => $this->merchant ['bu_id']]);
        $open = 8;
        $close = 23;

        $hourly = [];
        $times = [];
        $dates = [];
        $count = 7;
		  
        $startDate = time ();

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
                                        
                default:
                    
                    $conditions [] = $args [$i] . " = " . $val;
                    break;
            }
        }

		  $this->getPeriods ($startDate, $this->periodType);
		  
		  $open = 0;
		  $close = 24;

		  $hourly ['hid'] = $close - $open;
		  $hourly ['dow'] = $dow;
		  
		  for ($hour=$open; $hour<$close; $hour++) {
				
				$times [] = sprintf ("%s %02d:00:00", date ('Y-m-d'), $hour);
		  }
		  
		  $hourly ['times'] = $times;
		  
		  $col = 0;
		  foreach ($this->periods as $period) {
				
				$periodTotal = 0;
				$hourly [] = [];
				$date = date ('Y-m-d', $period ['date']);

				$t = strtotime ($period ['start']);
				// $t = $period ['date'];
				
				for ($index = 0, $hour = 0; $hour < 24; $hour++, $index++) {

					 /* $start = $this->isDST () ?  $t + ($hour * 60 * 60) : $t + (($hour - 1) * 60 * 60);
						 $start = $t + ($hour * 60 * 60);*/
					 
					 $start = $t + (($hour + $this->tzOffset ($this->tz ())) * 60 * 60);			 
					 $date = date ('Y-m-d H:00:00', strtotime ($period ['start']) + (ONE_HOUR * $index));
					 
					 $conditions = ['summary_type = 0',
										 'business_unit_id = ' . $this->merchant ['bu_id'],
										 "start_time = '$date'"];
					 
					 $query = TableRegistry::get ('SalesTotals')
												  ->find ()
												  ->where ($conditions)
												  ->limit (24);
					 
					 $amount = 0;
					 foreach ($query as $total) {
						  
						  switch ($total ['sales_key']) {
									 
								case 'TAXABLE':
								case 'NON_TAXABLE':
								case 'ROUND_DIFF':
									 break;
									 
								default:
									 
									 $amount += $total ['amount'];
									 break;
						  }
					 }
					 
					 if ($amount != null) {
						  
						  $hourly [$col] [$index] = ['amount' => sprintf ('%0.2f', $amount)];

						  switch ($this->merchant ['subscription_level']) {

								case 'trial':
								case 'standard':

									 $hourly [$col] [$index] ['link'] = ['start' => date ("Y-m-d H:i:s", $start),
																					 'end' => date ("Y-m-d H:i:s", $start + (60 * 60)),
																					 'business_unit_id' => $this->merchant ['bu_id']];
									 break;
						  }
						  
						  $periodTotal += $amount;
					 }
					 else {
						  
						  $hourly [$col] [$index] = ['amount' => 0];
					 }
				}
				
				$col ++;
		  }
		  
		  $data = ['prev' => $startDate - ONE_WEEK,
					  'next' => $startDate + ONE_WEEK,
					  'len' => $this->len,
					  'periods' => $this->periods,
					  'hourly' => $hourly,
					  'snap' => true,
					  'dow' => $this->dow ()];
		  
		  return ($this->response (__ ('Hourly Sales'),
											'Hourly',
											'index',
											$data));
	 }
}

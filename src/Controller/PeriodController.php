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
use Cake\I18n\I18n;

class PeriodController extends PosAppController {

    public $dates;
    public $len;
    public $periodDateFormat = 'D n/j';
	 public $periods;
	 public $currPeriod;
	 public $conditions = [];
	 public $join = [];

    public function initialize (): void {

        parent::initialize ();
    }

    public function getPeriods ($startDate = null, $type = 'weekly', $clear = true) {

        $this->periods = [];
        $utcOffset = 5; // $this->tzOffset ($this->tz ());
        
        switch ($type) {

				case 'weekly':

					 $this->len = 7;
					 $startOffset = ($this->len - $this->merchant ['business_units'] [$this->merchant ['bu_index']] ['start_of_week'] + $this->dow ($startDate)) % $this->len;
					 $this->currPeriod = -1;
					 
					 $date = $startDate - ($startOffset * ONE_DAY);
					 
					 for ($period=0; $period < $this->len; $period++) {

						  $diff = $startDate - $date;
						  
						  $this->periods [] = ["date" => strtotime (date ('Y-m-d ', $date) .sprintf ('%02d:00:00', $utcOffset)),
													  'period' => date ($this->periodDateFormat, $date),
													  "start" => date ('Y-m-d ', $date) .sprintf ('%02d:00:00', $utcOffset),
													  "end" => date ('Y-m-d ', $date + ONE_DAY) .sprintf ('%02d:00:00', $utcOffset),
													  "utc_start" => $this->toUtc (date ('Y-m-d 00:00:00', $date), $this->tz ()),
													  "utc_end" => $this->toUtc (date ('Y-m-d 00:00:00', $date + ONE_DAY), $this->tz ())];
						  

						  if (($startDate - $date) == 0) {

								$this->currPeriod = $period;
						  }
						  
						  $date += ONE_DAY;
					 }

					 $session = $this->request->getSession ();
					 $session->write ('periods', $this->periods);
					 $session->write ('curr_period', $this->currPeriod);
					 				 
					 break;

				case 'ytd':

					 $this->len = 12;
					 $this->set ('headerFormat', '%b');
					 
					 $soy = $this->startOfYear ();
					 // $soy = sprintf ("%04d-01-01 00:00:00", $type);
					 
					 for ($period=0; $period < $this->len; $period++) {
						  						  
						  $this->periods [] = ['date' => strtotime (sprintf ('%04d-%02d-01 %02d:00:00', $this->year, $period + 1, $utcOffset)),
													  'period' => date ('M Y', strtotime (sprintf ('%04d-%02d-01 %02d:00:00', $this->year, $period + 1, $utcOffset))),
													  'start' => sprintf ('%04d-%02d-01 %02d:00:00', $this->year, $period + 1, $utcOffset),
													  'end' => sprintf ('%04d-%02d-01 %02d:00:00', $this->year, (($period + 2) % 12), $utcOffset),
													  'utc_start' => sprintf ('%04d-%02d-01 %02d:00:00', $this->year, $period + 1, $utcOffset),
													  'utc_end' => sprintf ('%04d-%02d-01 %02d:00:00', $this->year, (($period + 2) % 12), $utcOffset)];  
					 }
					 
					 break;
					 
        }
    }

	 public function today () {

		  return $this->periods [$this->dow (time ())];
	 }
	 
    public function reset () {
		  
        $session = $this->request->getSession ();

		  if ($clear) {

				if ($session->check ('periods')) {

					 $session->delete ('periods');
					 $session->delete ('curr_period');
					 
					 $this->debug ('clear session periods...');
				}
		  }
		  $this->getPeriods ();
	 }
	 
}

<?php
/**
 *
 * Copyright (C) 2022 multiPos, LLC
 * <http://www.multipos.com>
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
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use \DateTime;
use \DateTimeZone;
use Cake\I18n\I18n;

const STANDARD_SALE = 0;
const VOID_SALE = '1';
const NO_SALE = '2';
const COMP_SALE = '3';
const X_REPORT = '6';
const Z_REPORT = '7';
const RETURN_SALE = '8';
const RECEIPT_REPRINT = '10';
const DISCOUNTS = '101';
const VOID_ITEMS = '102';
const RETURN_ITEMS = '103';

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class PosAppController extends AppController {

    public $merchant;
	 
    public $decimalPoint;
    public $currency;
    
    public $headerFormat = '%a %m/%e';
    public $dateFormat = 'm/d Y';
    public $csvSep = ',';
    public $updateCount;
    public $locale = 'en_US';
    public $session = false;
    public $controller = null;

    public $ticketTypes;
    public $ticketTypeMap;
    public $tenderTypes;
    public $tenderSearch;
    public $ticketStatus;
    public $limit = 25;

    /**
     *
     * initialize, check if logged in setup merchant info
     *
     */
    
    public function initialize (): void {

        parent::initialize ();
		  
        $this->ticketTypes = [null => __ ('Ticket type'),
                              SALE => __ ('SALE'),
                              VOID => __ ('VOID'),
                              NO_SALE => __ ('NO SALE'),
                              COMP_SALE => __ ('COMP'),
                              LOGIN => __ ('LOG IN'),
                              LOGOUT => __ ('LOG OUT'),
                              X_SESSION => __ ('X REPORT'),
                              Z_SESSION => __ ('Z REPORT'),
                              DRAWER_COUNT => __ ('DRAWER COUNT'),
										BANK => __ ('BANK'),
                              MANAGER_OVERRIDE => __ ('MANAGER OVERRIDE'),
                              OPEN_AMOUNT => __ ('OPEN AMOUNT'),
                              DISCOUNTS => __ ('DISCOUNTS'),
                              RETURN_SALE => __ ('RETURN'),
                              VOID_ITEMS => __ ('VOID ITEMS'),
                              SALE_NONTAX => __ ('NON TAX'),
                              REFUND => __ ('REFUND')];

        $this->ticketTypeMap = ['void_sales' => VOID,
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

        $this->tenderTypes = [null => __ ('ticket type'),
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
		  
        $this->tenderSearch = [null => __ ('Tender type'),
                               'cash' => __ ('CASH'),
                               'credit' => __ ('CREDIT'),
                               'account' => __ ('ACCOUNT'),
                               'check' => __ ('CHECK'),
                               'gift_card' => __ ('GIFT CARD'),
                               'gift_certificate' => __ ('GIFT CERTIFICATE'),
                               'split' => __ ('SPLIT'),
                               'mobile' => __ ('MOBILE')];
		  
        $this->ticketStates = [null => __ ('Ticket state'),
                               __ ('OPEN'),
                               __ ('COMPLETE'),
                               __ ('ERROR'),
                               __ ('SUSPEND'),
                               __ ('CREDIT PENDING'),
                               __ ('KITCHEN PENDING'),
                               __ ('VOIDED'),
                               __ ('REFUND'),
                               __ ('REVERSE'),
                               __ ('RECALLED')];
        
        $this->pageSize = [null => __ ('Tickets per page'),
                           25 => __ ('25'),
                           50 => __ ('50'),
                           100 => __ ('100'),
                           200 => __ ('200')];

		  $this->conditions = [];
		  $this->join = [];
    }
    
    /**
     *
     * detect session timeout, redir to logout
     *
     */
    
    public function beforeFilter (EventInterface $event) {
		  
        parent::beforeFilter ($event);
		  
        $this->session = $this->request->getSession ();
		  
        if ($this->session->check ('merchant')) {

            $serverName = explode ('.', $_SERVER ['SERVER_NAME']);
            $this->merchant = $this->session->read ('merchant');
            
            ConnectionManager::drop ('default');
            ConnectionManager::setConfig ('default', ['className' => 'Cake\Database\Connection',
                                                      'driver' => 'Cake\Database\Driver\Mysql',
                                                      'persistent' => false,
                                                      'host' => 'localhost',
                                                      'username' => 'vr',
                                                      'database' => $this->merchant ['dbname'],
                                                      'encoding' => 'utf8',
                                                      'timezone' => 'UTC',
                                                      'cacheMetadata' => true]);
            
            switch ($this->merchant ['locale']) {
						  
					 case 'da_DK':
						  
						  setLocale (LC_ALL, $this->merchant ['locale'] . '.UTF-8');
						  I18n::setLocale ($this->merchant ['locale']);
						  break;
						  
					 default:
						  
						  I18n::setLocale ('en_US');
						  break;
            }
            
            $this->set ('dbname',  $this->session->read ('merchant') ['dbname']);
            $this->set ('role',  $this->session->read ('merchant') ['role']);
            $this->set ('locale',  $this->session->read ('locale'));
				
            $loc = 'en-US';
            $this->currency = 'USD';
				
            switch ($this->merchant ['locale']) {
						  
					 case 'da_DK':
						  
						  $this->headerFormat = '%a %e/%m';
						  $this->dateFormat = 'd/m Y';
						  $this->csvSep = ';';
						  $this->currency = 'DKK';
						  break;
						  
					 default:
						  
						  $this->headerFormat = '%a %m/%e';
						  $this->dateFormat = 'm/d Y';
						  $this->currency = 'USD';
						  break;
            }
            
            setLocale (LC_ALL, $loc . '.UTF-8');
            I18n::setLocale ($loc);
            
            $this->merchant ['header_format'] = $this->headerFormat;
            $this->merchant ['currency'] = $this->currency;
				
            $this->merchant ['version'] = '';
            if ($serverName [0] === 'd') {
                
                $this->merchant ['version'] = '<p>' . $this->merchant ['dbname'];;
            }

            $this->set ('merchant',  $this->merchant);
        }
        else {

				// redir to login

				$this->debug ('session timeout...');
				$this->session = false;
				$this->redirect ('/');
		  }
    }
    
    function index (...$args) { 
		  
        $session = $this->request->getSession ();
        if ($session && $session->check ('merchant')) {

            $this->merchant ['controller'] = 'sales';  // initial report
				
            if ((count ($args) > 0) && ($args [0] == 'params')) {

                $this->merchant ['controller'] = '';
                $sep = '';
                for ($i=1; $i<count ($args); $i ++) {
                    
                    $this->merchant ['controller'] .= $sep . $args [$i];
                    $sep = '/';
                }
            }
				
            $locale = $this->merchant ['locale'];
            $merchant = $this->merchant;

            $this->set ('controller', $this->merchant ['controller']);
        }
        else {

				return $this->redirect ('/');
        }
    }
	 
	 function buSelect ($index = 0) {
		  
		  // change bu_id in the merchant session and save it
		  
		  $this->merchant ['bu_index'] = $index;
		  $this->merchant ['bu_id'] = $this->merchant ['business_units'] [$index] ['id'];
		  
		  $this->request->getSession ()->write ('merchant', $this->merchant);
		  
        $this->ajax (['status' => 0]);
	 }
	 
    function tz () {
		  
		  if ($this->merchant ['bu_index'] > 0) {
				
				return $this->merchant ['business_units'] [$this->merchant ['bu_index']] ['timezone'];
		  }
		  else {

				return $this->merchant ['timezone'];
		  }
    }
	 
    protected function tzOffset ($tz) {
		  
        $originDateTimezone = new DateTimeZone ('UTC');
        $targetDateTimezone = new DateTimeZone ($tz);
        $originDateTime = new DateTime ("now", $originDateTimezone);
        $targetDateTime = new DateTime ("now", $targetDateTimezone);
        $offset = $originDateTimezone->getOffset ($originDateTime) - $targetDateTimezone->getOffset ($targetDateTime);
		  
        return intVal ($offset / 60 / 60);
    }
	 
    public function dow ($t = null) {

		  if (gettype ($t) == 'string') {

				$t = strtotime ($t);
		  }

		  $t -= $this->tzOffset ($this->merchant ['timezone']) * ONE_HOUR;
		  
        if ($t) {
            return date ("w", $t);
        }
        else {
            return date ("w");
        }
    }
    
    public function toUtc ($str = null) {

        date_default_timezone_set ('America/New_York'); // $this->merchant ['timezone']);

        $time = time ();
        
        if ($str != null) {

            $time = strtotime ($str . " UTC");
        }
        
        $d = date ('Y-m-d H:i:s', $time);
        date_default_timezone_set ('UTC');

        return $d;
    }

    protected function utcToLocal ($t, $timezone, $format = null) {

        $t = str_replace ('.', ':', $t);  // where is this . coming from?
        
        $f = 'Y-m-d H:i:s';
        if ($format != null) {
				
            $f = $format;
        }
		  
        $date = new DateTime ($t, new DateTimeZone ('UTC'));
        $date->setTimezone (new DateTimeZone ($timezone));
        return $date->format ($f);
		  
    }

    protected function localToUTC ($t, $timezone) {
		  
        $t = str_replace ('.', ':', $t);  // where is this . coming from?

        $date = new DateTime ($t, new DateTimeZone ($timezone));
        $date->setTimezone (new DateTimeZone ('UTC'));
        return $date->format ('Y-m-d H:i:s');
    }
    
    public function timestamp ($t, $fmt = null) {

        if ($t == null) {

            return '';
        }

        $strTime = null;
        if (is_integer ($t)) {
            
            $strTime = date ('Y-m-d H:i:ss', $t);
        }
        else {
            $strTime = $t->i18nFormat ('yyyy-MM-dd HH:mm:ss');
        }

        if (strlen ($strTime) > 0) {
            
            return date ('D n/j g:i:s a', strtotime ($this->utcToLocal ($strTime, 'America/New_York')));  // $this->tz ())));
        }

        return '';
    }
    
    protected function isDST () {
        
        return intVal (date ('I')) == 1;
    }

	 function utcDate () {

        date_default_timezone_set ('UTC');
        return date ('Y-m-d H:i:s', time ());
    }
	 /**
     *
     * Generic save, add entry in bo_updates for the POS
     *
     */
	 
	 public function save ($table, $entity) {
        
        $saveTable = $table;
		  
        $table = TableRegistry::get ($table);
		  
        $res = $table->save ($entity);

        $batchID = 0;
        $batchTable = TableRegistry::get ('Batches');
        $batch = $batchTable
               ->find ()
               ->order (['id' => 'desc'])
               ->first ();
        
        if ($batch) {
            
            $batchID = $batch ['id'];
        }
        else {
            
            $batch = ['batch_desc' => __ ('Auto batch'),
                      'batch_type' =>  0,
                      'business_unit_id' => $this->merchant ['bu_id']];
            
            $batch = $batchTable->save ($batchTable->newEntity ($batch));
            $batchID = $batch ['id'];
        }
        
        $batchEntry = ['business_unit_id' => $this->merchant ['bu_id'],
                       'batch_id' => $batchID,
                       'update_table' => $table->getTable (),
                       'update_id' => $res ['id'],
                       'update_action' => 0,
                       'execution_time' => time ()];
        
        
        $batchEntriesTable = TableRegistry::get ('BatchEntries');
        $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
        $batchEntriesTable->save ($batchEntry);

        $batch ['update_count'] += 1;
        $batchTable->save ($batch);

		  $this->notifyPos ();  // send an mttq message
		  
        return $res;
    }
	 
	 /**
     *
     * tell the POS something has changed
     *
     */

    public function notifyPOS () {
		  
		  $exec = 'mosquitto_pub -h localhost ' .
					 ' -m \'{"method": "download"}\'' .
					 ' -t \'multipos/' . $this->merchant ['merchant_id'] . '\'';

		  $this->debug ($exec);
		  
		  shell_exec ($exec);
	 }
	 
	 /**
     *
     * change the location
     *
     */
	 
	 public function bu ($index) {

		  // change bu_id in the merchant session and save it
		  
		  $this->merchant ['bu_index'] = $index;
		  $this->merchant ['bu_id'] = $this->merchant ['business_units'] [$index] ['id'];
		  
		  $this->request->getSession ()->write ('merchant', $this->merchant);
		  
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => 0]);
        $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**
     *
     * printable phone number
     *
     */
	 
    public function phoneFormat ($p) {

		  if ($p == null) {
				
				return '----------';
		  }
		  
        switch ($this->merchant ['locale']) {

            case 'da_DK':

                return $p;

            default:

                if (strlen ($p) == 10) {

                    return '(' . substr ($p, 0, 3) . ') ' . substr ($p, 3, 3) . '-' . substr ($p, 6, 4);
                }
                else if (strlen ($p) == 7) {

                    return substr ($p, 0, 3) . '-' . substr ($p, 3, 4);
                }
        }

        return '----------';
    }

	 public function clearPhone ($input) {
		  
		  return preg_replace ('/\(|\)|\s+|\-/', '', $input);
	 }

	 public function uuid () {
    
		  if (function_exists ('com_create_guid') === true) {
        
				return trim (com_create_guid (), '{}');
		  }
    
		  $data = openssl_random_pseudo_bytes (16);
		  $data[6] = chr (ord ($data[6]) & 0x0f | 0x40); // set version to 0100
		  $data[8] = chr (ord ($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		  return vsprintf ('%s%s-%s-%s-%s-%s%s%s', str_split (bin2hex ($data), 4));
	 }
}

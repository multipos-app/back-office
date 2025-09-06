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
use Cake\I18n\Time;
use Cake\Event\EventInterface;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class TicketsController extends PosAppController {
    
    public function initialize (): void {
		  
        parent::initialize ();
	 }
    
	 /**
	  *
	  *
	  *
	  */
	 
    function index (...$args) {
		  
		  if (!empty ($this->request->getData ())) {
								
				$tmp = [];
				foreach ($this->request->getData () as $key => $val) {

					 if (strlen ($val) > 0) {
						  
						  $tmp [] = $key;
						  $tmp [] = $val;
					 }
				}
				
				$args = $tmp;
		  }

		  $this->conditions ($args);
		  
        $timestamp = function ($t) {

				$this->debug ('timestamp... ' . $t . ' ' . get_class ($t));
				
            return date ('m/d g:i a', strtotime ($this->utcToLocal ($t->i18nFormat ('yyyy-MM-dd H:mm:ss'),
																						  $this->merchant ['business_units'] [$this->merchant ['bu_index']] ['timezone'])));
        };

		  // check to see if we are only looking at one store
		  
		  if ($this->merchant ['business_units'] [0] ['id'] != $this->merchant ['bu_id']) {

				$this->conditions [] = ['business_unit_id' => $this->merchant ['bu_id']];
		  }
		  
		  $this->paginate = ['conditions' => $this->conditions,
									'limit' => 25];
		  
		  $q = TableRegistry::get ('Tickets')
								  ->find ()
								  ->join ($this->join)
        						  ->order (['Tickets.complete_time' => 'desc']);
								  //->distinct (['Tickets.ticket_no']);
		  
		  $t = $this->paginate ($q);
		  
        $tickets = [];
        
        foreach ($t as $ticket) {
								
            $ticket ['complete_time'] = date ('m/d g:i a', $this->localtime (strtotime ($ticket ['complete_time'])));
				$t = $ticket->toArray ();            
            $tickets [] = $t;
        }
        
        $this->set (['ticketTypes' => $this->ticketTypes,
							'tenderTypes' => $this->tenderSearch,
							'tickets' => $tickets]);
    }
	 
	 /**
	  *
	  *
	  *
	  */
	 
    function detail ($id = null) {
		  
        \Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd H:mm:ss');

		  $buid = 1;
		  $posNo = 0;
		  
        if ($id == null) {
            
            return $this->redirect (['action' => 'index']);
        }
		  
        $ticket = TableRegistry::get ('Tickets')
										 ->find ()
										 ->where (['id' => $id])
										 ->contain (['TicketItems', 'TicketTaxes', 'TicketTenders'])
										 ->first ();
        
        if (isset ($ticket ['ticket_items'])) {

            $i = 0;
            foreach ($ticket ['ticket_items'] as $ti) {

                $ticketItemID = intval ($ti ['id']);

                $ticket ['ticket_items'] [$i] ['ticket_item_addons'] = [];
					 
                $query = TableRegistry::get ('TicketItemAddons')->
                find ()->
                where (["ticket_item_id = $ticketItemID"]);
					 
                foreach ($query as $ticketItemAddon) {
						  
                    $ticket ['ticket_items'] [$i] ['ticket_item_addons'] [] = $ticketItemAddon;
                }
					 
                $i ++;
            }
        }

 		  $timestamp = strtotime ($ticket ['start_time']);
 		  $startTime = strtotime ($ticket ['start_time']);
		  $ticketTime = date ('m/d g:i a', $this->localtime (strtotime ($ticket ['complete_time'])));

        $ticket ['start_time'] = $ticketTime;
        $ticket ['complete_time'] = $ticketTime;
		  
        $ticket ['details'] = [['desc' => __ ('TRANSACTION NUMBER'),
                                'value' => $ticket ['id'] . ' - ' . $ticket ['ticket_no']],
                               ['desc' => __ ('START TIME'),
                                'value' => $ticket ['start_time']],
                               ['desc' => __ ('TRANSACTION TYPE'),
                                'value' => $this->ticketTypes [$ticket ['ticket_type']]]];
		  
		  if ($ticket ['ticket_type'] == REFUND) {

				$ticket ['details'] [] = ['desc' => __ ('REASON'),
												  'value' => strtoupper ($ticket ['comment'])];
        }

		  $flag = [null => __ ('Flag this transaction'),
					  'suspicious' => __ ('Suspicious'),
					  'excessive_tip' => __ ('Excessive tip'),
					  'employee_theft' => __ ('Employee theft'),
					  'customer_theft' => __ ('Customer theft')];

		  $camera = null;
		  $clipUrl = null;
		  $accessToken = null;
		  
		  switch ($this->merchant ['video_provider']) {
		  
				case 'camcloud':
					 
					 $camera = TableRegistry::get ('Devices')
													->find ()
													->where (['device_type' => 'camera'])
													->first ();
					 
					 if ($camera) {
						  
						  $cameraID = $camera ['alt_device_id'];
						  $accessToken = $this->merchant ['video_provider_token'];
						  $len = 2 * 60;  // 2 minute clips
						  $timestamp = ($startTime - 10);
						  
						  $this->debug ("timestamp... " . date ("Y-m-d H:i:s", $timestamp));
								
						  $clipUrl = "https://vod.hostedcloudvideo.com/$cameraID/vod.m3u8?segment_type=FMp4&duration=$len&timestamp=$timestamp";
								
						  $this->debug ($camera);
						  $this->debug ($clipUrl);
					 }

					 $device = TableRegistry::get ('Devices')
													->find ()
													->where (["id" => $ticket ['pos_no']])
													->first ();
					 /* 
					  * 					 if ($device) {
					  * 						  
					  * 						  $this->debug ($device);
					  * 						  
					  * 						  $camera = TableRegistry::get ('Devices')
					  * 														 ->find ()
					  * 														 ->where (["id" => $device ['device_id'],
					  * 																	  'device_type' => 'camera'])
					  * 														 ->first ();
					  * 
					  * 						  if ($camera) {
					  * 								
					  * 								$cameraID = $camera ['alt_device_id'];
					  * 								$accessToken = $this->merchant ['video_provider_token'];
					  * 								$len = 2 * 60;  // 2 minute clips
					  * 								$timestamp = (strtotime ($ticket ['start_time']) - 10);
					  * 								
					  * 								$clipUrl = "https://vod.hostedcloudvideo.com/$cameraID/vod.m3u8?segment_type=FMp4&duration=$len&timestamp=$timestamp";
					  * 								
					  * 								$this->debug ($camera);
					  * 								$this->debug ($clipUrl);
					  * 						  }
					  * 						  else {
					  * 
					  * 								$this->debug ('camera not found...');
					  * 						  }
					  * 					 }
					  * 					 else {
					  * 
					  * 						  $this->debug ('device not found...');
					  * 					 }
					  * 					 */
					 break;
		  }
		  
		  $this->set (['ticket' => $ticket,
							'camera' => $camera,
							'timestamp' => $timestamp,
							'clipUrl' => $clipUrl,
							'accessToken' => $accessToken,
							'flag' => $flag]);

		  $builder = $this->viewBuilder ()
								->setLayout ('ajax')
								->disableAutoLayout ()
								->setTemplatePath ('Tickets')
								->setTemplate ('detail');
		  
		  $view = $builder->build ();
		  $html = $view->render ();
		  
		  $this->ajax (['status' => 0,
							 'html' => $html]);
    }
	 
	 /**
	  *
	  *
	  *
	  */

	 function nav ($action, $ticketID, $buID, $posNo) {
 

		  $this->debug ("action... $action, $ticketID, $buID, $posNo");

		  $where = ["business_unit_id = $buID",
						"pos_no = $posNo"];
		  
		  $order = [];
		  
		  switch ($action) {
					 
				case 'ticket_forward':

					 $where [] = "id > $ticketID";
					 $order = ['id asc'];
					 break;
					 
				case 'ticket_backward':
					 
					 $where [] = "id < $ticketID";
					 $order = ['id desc'];
					 break;
		  }
					 
		  $this->debug ($where);
					 
		  $t = TableRegistry::get ('Tickets')
								  ->find ()
								  ->where ($where)
								  ->order ($order)
								  ->select (['id'])
								  ->limit (1)
								  ->first ();

		  $this->debug ($t);

		  return $this->detail ($t ['id']);

		  // $this->ajax (['status' => 0]);
	 }
	 
	 /**
	  *
	  *
	  *
	  */
	 
    function flag ($id, $flag) {

		  TableRegistry::get ('Tickets')
		               ->updateAll (['tag' => $flag],
											 ['id' => $id]);

        $this->set ('data', ['status' => 0]);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('json');
		  
        $this->RequestHandler->respondAs ('json');

	 }
	 
	 /**
	  *
	  *
	  *
	  */
	 
    function video ($videoID, $dir) {
        
        $order = '';
        switch ($dir) {

				case 'prev':

					 $dir = '<';
					 $order = 'desc';
					 break;

				case 'next':

					 $dir = '>';
					 $order = 'asc';
					 break;
        }
        
        $video = TableRegistry::get ('Videos')
										->find ()
										->where (['id ' . $dir . ' ' . $videoID])
										->order (['id ' . $order])
										->limit (1)
										->first ();
		  
		  $data = ['status' => 1];
		  
        if ($video) {
				
            $clip = '/files/clips/' . $this->merchant ['dbname'] . '/' . $video ['business_unit_id'] . '/0/' . $video ['file_name'];
				$data = ['status' => 0,
							'video_id' => $video ['id'],
							'clip' => $clip,
							'offset' => 0,
							'path' => '/files/clips/' . $this->merchant ['dbname'] . '/' . $video ['business_unit_id'] . '/0/' . $video ['file_name']];
		  }
		  
		  $this->set ($data);
		  
        $this->set ('data', $data);
        $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->disableAutoLayout ()
             ->setTemplate ('json');
		  
        $this->RequestHandler->respondAs ('json');		          
    }

	 /**
	  *
	  *
	  *
	  */
	 
	 function search () {

		  $data = [];

		  $this->set (['tender' => [null => 'Tender type',
											 'cash' => 'Cash',
											 'credit' => 'Credit'],
							'type' => [null => 'Ticket type',
										  0 => 'Sale',
										  1 => 'Void',
										  2 => 'No Sale',
										  3 => 'Comp Sale',
										  4 => 'Log-in',
										  5 => 'Log-out',
										  6 => 'X Report',
										  7 => 'Z Report',
										  8 => 'Return Sale',
										  9 => 'Non-tax',
										  10 => 'Bank/Drop'],
							'exceptions' => [null => 'Exceptions',
												  'refunds' => 'Refunds',
												  'discounts' => 'Discounts']]);
        
        return ($this->response (__ ('Tickets'),
                                 'Tickets',
                                 'search',
                                 $data,
                                 false,
                                 'ajax',
                                 false));
	 }
	 
	 /**
	  *
	  * ticket search conditions
	  *
	  */
	 
    private function conditions ($args) {
		  
		  $this->conditions = [];
        $this->join = [];
		  $session = $this->request->getSession ();

 		  if ($this->request->getQuery ('page')) {

				$this->conditions = $session->read ('conditions');
				$this->join = $session->read ('join');
				return;
		  }
		  else if (count ($args) == 0) {
				
 				$session->write ('conditions', $this->conditions);
 				$session->write ('join', $this->join);
				return;
		  }

		  while (count ($args) > 0) {
				
				$key = array_shift ($args);
				$val = array_shift ($args);

            switch ($key) {
						  
					 case 'period':

						  $startDate = intval ($val);
						  
						  $args [] = 'start_date';
						  $args [] = $startDate;
						  
						  $args [] = 'end_date';
						  $args [] = $startDate + ONE_DAY;
						  
						  break;

					 case 'index':
						  break;
						  
					 case 'page_size':
                    
						  $this->paginate = ['Tickets' => ['limit' => $val]];
						  break;
						  
					 case 'start_date':

						  if (gettype ($val) == 'string') {

								$val = strtotime ($val);
						  }

						  $startDate = $val;
						  $this->conditions [] = "complete_time >= '" . date ('Y-m-d', $val) . ' ' . $this->merchant ['utc_offset'] . "'";
						  $this->conditions [] = "complete_time < '" . date ('Y-m-d', $val + ONE_DAY) . ' ' . $this->merchant ['utc_offset'] . "'";
						  break;
						  
					 case 'end_date':
						  
						  $time = strtotime ($val);
						  // $this->conditions [] = "complete_time < '" . date ('Y-m-d', $time) . ' ' . $this->merchant ['utc_offset'] . "'";
						  break;

					 case 'start_hour':

						  $this->conditions [] = "complete_time >= '" . $val . "'";
						  
						  break;
						  
					 case 'end_hour':

						  $this->conditions [] = "complete_time < '" . $val . "'";

						  break;
						  
					 case 'gt': 
						  $this->conditions [] = "total >= " . floatval ($val);
						  break;
						  
					 case 'lt': 
						  $this->conditions [] = "total <= " . floatval ($val);
						  break;

					 case 'cash': 
					 case 'credit':
					 case 'account':
						  
						  $this->conditions [] = "tender_desc = " . "'" . $key . "'";
						  break;
						  
					 case 'tender_desc':
						  
						  $this->conditions [] = "tender_desc = " . "'" . $val . "'";
						  break;

					 case 'ticket_contains': 
						  
						  $this->conditions [] = $val . " != 0";
						  break;

					 case 'tender_desc':

						  $this->conditions [] = "tender_desc = '" . $val . "'";
						  break;

						  
					 case 'exceptions':

						  switch ($val) {

									 
								case 'refunds':
									 
									 $this->conditions [] = "return_items > 0";
									 break;
									 
								case 'discounts':
									 
									 $this->conditions [] = "discounts > 0";
									 break;
						  }
						  break;
						  
					 case 'clerk_id':

						  $this->conditions [] = "clerk_id = '" . $val . "'";
						  break;
						  
					 case 'ticket_no':

						  $this->conditions [] = "ticket_no like '%$val%'";
						  break;
						  
					 case 'tag':

						  $this->conditions [] = "length(tag) > 0";
						  break;
						  
					 case 'ticket_type':
						  
						  $this->conditions [] = "$key = $val";
						  break;
						  
						  switch ($val) {
									 
								case 'return_items':
								case 'void_items':
									 
									 $this->conditions [] = $val . " > 0";
									 break;
						  }
                    
						  break;
						  
					 case 'card_search':
						  
						  
						  $this->conditions [] = "ticket_tenders.cc_no like '%$val%'";
						  
						  $this->join = ['table' => 'ticket_tenders',
											  'type' => 'right',
											  'conditions' => 'Tickets.id = ticket_tenders.ticket_id'];
						  break;
						  
					 case 'item_id':
						  
						  
						  $this->conditions [] = 'ticket_items.item_id = ' . $val;
						  
						  $this->join = ['table' => 'ticket_items',
											  'type' => 'right',
											  'conditions' => 'Tickets.id = ticket_items.ticket_id'];
						  break;
						  
					 case 'department_id':

						  $this->conditions [] = 'ticket_items.department_id = ' . $val;
						  
						  $this->join = ['table' => 'ticket_items',
											  'type' => 'right',
											  'conditions' => 'Tickets.id = ticket_items.ticket_id'];
						  break;

					 case 'item_search':

						  $this->conditions [] = "ticket_items.item_desc like '%" . $val . "%'";
						  
						  $this->join = ['table' => 'ticket_items',
											  'type' => 'right',
											  'conditions' => 'Tickets.id = ticket_items.ticket_id'];
						  break;

					 default:

						  $this->conditions [] = $key . " = " . $val;
						  break;
            }
        }
		  
		  if (count ($this->conditions)) {
				
				$session->write ('conditions', $this->conditions);
 				$session->write ('join', $this->join);
		  }

		  return;
    }
}

?>

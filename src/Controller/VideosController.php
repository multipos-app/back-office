<?php
/**********************************************************************************************
 *
 * Copyright (C) 2024 VideoRegister, LLC
 * <http://www.VideoRegister.com>
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
use Cake\I18n\Time;

require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';

class VideosController extends PosAppController {
    
    public $limit = 25;

    public function initialize (): void {
		  
        parent::initialize ();
    }

	 /**
	  *
	  * main video page, list of locations and cameras, capable of live stream, clips and adding and editing cammeras
	  *
	  */
	 
    public function index (...$params) {
		  
        if (count ($params) > 0) {

            for ($i = 0; $i < count ($params); $i += 2) {
                
                switch ($params [$i]) {

						  case 'start':

								break;
					 }
            }
        }

		  $template = 'index';
		  switch ($this->merchant ['video_provider']) {

				case 'camcloud':
					 
					 require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'constants.php';
					 
					 $accessToken = $this->merchant ['video_provider_token'];
					 
					 $locations = [];

					 $query = TableRegistry::get ('BusinessUnits')
												  ->find ()
												  ->contain (['Devices'])
												  ->where (['business_type' => BU_LOCATION]);

					 $cameras = 0;
					 foreach ($query as $bu) {
						  
						  // foreach ($this->merchant ['business_units'] as $bu) {
						  
						  $location = ['business_unit_id' => $bu ['id'],
											'location_name' => $bu ['business_name'],
											'addr' => $bu ['addr_1'],
											'timezone' => $bu ['timezone'],
											'timezone_offset' => $this->utcOffsets [$bu ['timezone']],
											'cameras' => []];

						  foreach ($bu ['devices'] as $device) {

								if ($device ['device_type'] == 'camera') {

									 $device ['clip_url'] = 'https://vod.hostedcloudvideo.com/' . $device ['alt_device_id'] . '/vod.m3u8?segment_type=FMp4';
									 $location ['cameras'] [] = $device;
									 $cameras ++;
								}
						  }

						  $locations [$location ['business_unit_id']] = $location;
					 }

					 $cameraTypes = [null => __ ('Add camera'),
										  'amcrest' => __ ('Amcrest'),
										  'axis' => __ ('Axis')];
					 
					 /*	
						 'foscam' => __ ('Foscam'),
						 'hanwha_techwin' => __ ('Hanwha Techwin'),
						 'hikvision' => __ ('Hikvision'),
						 'speco' => __ ('Seco'),
						 'vivotech' => __ ('Vivotech'),
						 'generic_mjpeg' => __ ('Generic MJPEG'),
						 'generic' => __ ('Generic')];
					  */
								
					 $this->set (['accessToken' => $accessToken,
									  'locations' => $locations,
									  'cameraTypes' => $cameraTypes]);
		  }
	 }

	 /**
	  *
	  * get a live stream url for the web client
	  *
	  */
	 
	 function stream ($cameraID) {

		  $response = ['status' => 1];
		  
		  $url = 'https://api.hostedcloudvideo.com/v1/cameras/' . $cameraID . '/stream/hls';
		  $headers = ['Content-type: Application/json',
						  "Authorization: Bearer " . $this->merchant ['video_provider_token']];
		  
		  $curl = curl_init ();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
		  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		  curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
		  curl_setopt ($curl, CURLOPT_HTTPGET, true);
		  curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
		  
		  $result = curl_exec ($curl);

		  $result = json_decode ($result, true);
		  
		  if ($result) {
				
				$response ['status'] = 0;
				$response ['url'] = $result ['uri'];
		  }
		  
		  $this->set ('response', $response);
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
				 ->disableAutoLayout ()
				 ->setTemplate ('ajax');
	 }
	 
	 /**
	  *
	  * add/edit a camera
	  *
	  */
	 
	 function edit ($buID, $id, $cameraType) {

		  $camera = null;

		  if ($id > 0) {

				$camera = TableRegistry::get ('Devices')
											  ->find ()
											  ->where (['id' => $id])
											  ->first ();

				$camera ['params'] = json_decode ($camera ['params'], true);
		  }
		  else {
				
				$camera = ['business_unit_id' => $buID,
							  'id' => 0,
							  'alt_device_id' => '',
							  'device_name' => '',
							  'timezone' => $this->buByID ($buID) ['timezone'],
							  'params' => ['camera_type' => $cameraType,
												'serial_number' => '',
												'username' => '',
												'password' => '']];
		  }
		  
		  $timezone = $this->buByID ($buID) ['timezone'];
		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'timezones.php';
		  
		  return ($this->response (__ ('Edit camera'),
											'Videos',
											'edit_' . $cameraType,
											compact (['buID', 'camera', 'timezones', 'timezone'])));
	 }
	 
	 protected function buByID ($buID) {
		  
		  foreach ($this->merchant ['business_units'] as $bu) {
				
				if ($bu ['id'] == $buID) {
					 
					 return $bu;
				}
		  }
		  
		  return null;
	 }

	 /* function edit ($cameraID) {
		 
		 $timezone = null;
		 
		 if ($cameraID > 0) {
		 
		 $camera = TableRegistry::get ('Devices')
		 ->find ()
		 ->where (['id' => $cameraID])
		 ->first ()
		 ->toArray ();

		 if ($camera) {
		 
		 $camera ['params'] = json_decode ($camera ['params'], true);
		 $cameraType = $camera ['params'] ['camera_type'];
		 $timezone = $camera ['timezone'];
		 }
		 }

		 require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'timezones.php';
		 
		 return ($this->response (__ ('Edit cameras'),
		 'Videos',
		 'edit_' . $cameraType,
		 compact (['camera', 'timezones', 'timezone'])));
		 }*/
	 
	 /**
	  *
	  *
	  *
	  */
	 
	 function update () {
		  
		  $camcAccountID = $this->merchant ['video_provider_account_id'];
		  
		  $status = 1;
		  $statusText = __ ('Unknown error configuring camera, please contact <a href="mailto:support@videoregister.com">VideoRegister Support</a>.');

        if (!empty ($this->request->getData ())) {
				
				$devicesTable = TableRegistry::get ('Devices');

				$camera = $this->request->getData () ['camera'];
				
				$device = null;

				if ($camera ['id'] > 0) {

					 $device = $devicesTable->find ()
													->where (['id' => $camera ['id']])
													->first ();

					 if ($device) {
						  
						  $camera ['camera_name'] = strtoupper ($camera ['camera_name']);
						  
						  $device ['device_name'] = $camera ['camera_name'];
						  $device ['timezone'] = $camera ['timezone'];
						  $device ['params'] = json_encode ($camera ['params']);
						  $device = $devicesTable->save ($device);
					 }

					 $status = 0;
					 $statusText = __ ('SUCCESS');
				}
				else {

					 // add to camcloud

					 $status = 1;
					 $statusText = __ ('Unknown error configuring camera, please contact <a href="mailto:support@videoregister.com">VideoRegister Support</a>.');
					 
					 $post = ['name' => $camera ['camera_name'],
								 'protocol' => $camera ['protocol'],
								 'timezone' => $camera ['timezone'],
								 'connection' => ['token' => $camera ['params'] ['serial_number'],
														'username' => $camera ['params'] ['username'],
														'password' => $camera ['params'] ['password'],
														'type' => $camera ['params'] ['type']]];
					 
					 $post = json_encode ($post);
					 
					 $url = 'https://api.hostedcloudvideo.com/v1/users/' . $camcAccountID . '/cameras';
					 $headers = ['Content-type: Application/json',
									 "Authorization: Bearer " . $this->merchant ['video_provider_token'],
									 'Content-length: ' . strlen ($post)];
					 
					 $curl = curl_init ();
					 curl_setopt ($curl, CURLOPT_URL, $url);
					 curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
					 curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
					 curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					 curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
					 curl_setopt ($curl, CURLOPT_POST, true);
					 curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
					 curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
					 
					 $result = curl_exec ($curl);

					 $result = json_decode ($result, true);
					 
					 if (isset ($result ['id'])) {

						  // save the camera
						  
						  $device = ['id' => null,
										 "business_unit_id"=> $camera ['business_unit_id'],
										 "device_id" => null,
										 "alt_device_id" => $result ['id'],
										 "device_name" => strtoupper ($camera ['camera_name']),
										 "device_type" => "camera",
										 "device_sub_type" => "",
										 "status" => 0,
										 "params" => json_encode ($camera ['params'])];
						  
						  $device = $devicesTable->newEntity ($device);
						  $device = $devicesTable->save ($device);

						  // complete camcloud config

						  sleep (2);  // pause??

						  $cameraID = $result ['id'];
						  $token = $this->merchant ['video_provider_token'];
						  
						  $curlExec = "curl -X 'PUT' -s 'https://api.hostedcloudvideo.com/v1/cameras/$cameraID/recording/cloud' " .
										  "-H 'accept: application/json' -H 'Authorization: Bearer $token' " .
										  "-d '{\"recordMode\":\"continuous\",\"recordEnabled\":true,\"events\":[\"motion\"],\"nasId\":null}'";
						  
						  $result = exec ($curlExec, $output, $retval);

						  if ($result) {

								$status = 0;
								$statusText = __ ('SUCCESS');
						  }
						  else {

								$this->debug ('error in camera config...');
								$this->debug ($curlExec);
								
								$status = 1;
								$statusText = __ ('Unknown error configuring camera, please contact <a href="mailto:support@videoregister.com">VideoRegister Support</a>.');
						  }
					 }
					 else {

						  if ($result) {

								if (isset ($result ['status'])) {
									 
									 switch ($result ['status']) {

										  case 409:

												$statusText = __ ('Unknow error, ' . json_encode ($result));
												
												switch ($result ['type']) {

													 case 'TestFailed':

														  switch ($result ['details'] ['messageCodes'] [0]) {

																case 'credentials.rejected':

																	 $statusText = __ ('Incorrect camera credentials');
																	 break;

																case '':
																	 
																	 $statusText = __ ('Camera not connected to the cloud, try rebooting it, wait 5 minutes and try again');
																	 break;
														  }
														  
														  break;
														  
													 case 'ConnectionClaimed':
														  
														  $statusText = __ ('Camera is already claimed');
														  break;
														  
													 case 'BillingRestriction':
														  
														  $statusText = __ ('You have reacthed the limit of cameras on your account.');
														  break;
												}
									 }
								}
								else if (isset ($result ['messageCodes'])) {
									 
									 $statusText = $result ['messageCodes'];
								}
						  }
						  else {

								$this->debug ("bad camcloud token");
								$this->debug ($this->merchant);
						  }
					 }
				}
		  }
		  
		  $this->set ('response', ['status' => $status,
											'status_text' => $statusText]);
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
 				 ->setTemplate ('ajax');
        $this->RequestHandler->respondAs ('json');
	 }

	 /**
	  *
	  * reboot/restart a camera
	  *
	  */
	 
	 function restart ($cameraID) {
		  
		  $response = ['status' => 1];
		  
		  $url = 'https://api.hostedcloudvideo.com/v1/cameras/' . $cameraID . '/reboot';
		  $headers = ['Content-type: Application/json',
						  "Authorization: Bearer " . $this->merchant ['video_provider_token']];
		  
		  $curl = curl_init ();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
		  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		  curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
		  curl_setopt ($curl, CURLOPT_HTTPGET, false);
		  curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
		  curl_setopt ($curl, CURLOPT_POSTFIELDS, []);
		  
		  $response = curl_exec ($curl);
		  $response = json_decode ($response, true);
		  
		  if ($response) {
				
				$response ['status'] = 0;
		  }
		  else {

				$response = ['status' => 1,
								 'status_text' => __ ('Something went wrong')];
		  }
		  
		  $this->set ('response', $response);
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
 				 ->setTemplate ('ajax');
        $this->RequestHandler->respondAs ('json');
		  
		  $this->set ('response', ['status' => 0,
											'status_text' => __ ('success')]);
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
 				 ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');
	 }
	 
	 /**
	  *
	  * delete a camera
	  *
	  */
	 
	 function delete ($cameraID) {
		  
		  $response = ['status' => 0];
		  
		  $url = 'https://api.hostedcloudvideo.com/v1/cameras/' . $cameraID;
		  $headers = ['Content-type: Application/json',
						  "Authorization: Bearer " . $this->merchant ['video_provider_token']];
		  
		  $curl = curl_init ();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
		  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		  curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
		  curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		  curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
		  
		  $response = curl_exec ($curl);

		  TableRegistry::get ('Devices')->deleteAll (['alt_device_id' => $cameraID]);
		  
		  $this->set ('response', ['status' => 0,
											'status_text' => __ ('success')]);
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
 				 ->setTemplate ('ajax');
		  
        $this->RequestHandler->respondAs ('json');
	 }

	 /**********************************************************************************************
	  *
	  * get a snapshot
	  *
	  *********************************************************************************************/
	 
	 function snapshot ($timestamp, $offset, $ticketID, $ticketNo) {
		  
		  $date = date ("Ymd", time ());
		  $merchantID = $this->merchant ['id'];
		  $buID = $this->merchant ['bu_id'];
		  
		  $cameras = TableRegistry::get ('Devices')
										  ->find ()
										  ->where (['device_type' => 'camera'])
										  ->all ();

		  foreach ($cameras as $camera) {
				
				$date = date ("Ymd", time ());
				$cameraID = $camera ['id'];
				$altID = $camera ['alt_device_id'];
				
				$dir = "/data/snapshots/$merchantID/$date/$buID/$cameraID";
				
				if (!file_exists ($dir)) {
					 
					 mkdir ($dir, 0755, true);
				}

				$headers = ['accept: image/*',
								'Authorization: Bearer ' . $this->merchant ['video_provider_token']];	
				
				$timestamp = date ('Y-m-d\TH:i:s.000\Z', $timestamp + intval ($offset));
				$localTime = $this->utcToLocal ($timestamp, $this->tz (), 'YmdHis');
				
				$url = "https://api.hostedcloudvideo.com/v1/cameras/$altID/snapshot?time=$timestamp";
				
				$curl = curl_init ();
				
				curl_setopt ($curl, CURLOPT_URL, $url);
				curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($curl, CURLOPT_BINARYTRANSFER, 1);
				curl_setopt ($curl, CURLOPT_HTTPGET, true);
				
				$raw = curl_exec ($curl);
				curl_close  ($curl);
				$t = strtotime ($timestamp);

				$output = "$dir/$ticketNo-$localTime.jpg";

				$fp = fopen ($output, 'w');
				fwrite ($fp, $raw);
				fclose ($fp);
		  }
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->setTemplate ('ajax');
		  
        $this->set ('response', ['status' => 0,
											'snapshot' => $output]);
        $this->RequestHandler->respondAs ('json');
		  
	 }
	 
	 /**********************************************************************************************
	  *
	  * start a download clip
	  *
	  *********************************************************************************************/
	 
	 function clip ($timestamp, $offset, $ticketID) {
		  
		  $date = date ("Ymd", time ());
		  $merchantID = $this->merchant ['id'];
		  $buID = $this->merchant ['bu_id'];
		  
		  $cameras = TableRegistry::get ('Devices')
										  ->find ()
										  ->where (['device_type' => 'camera'])
										  ->all ();
		  
		  foreach ($cameras as $camera) {
				
				$cameraID = $camera ['id'];
				$altID = $camera ['alt_device_id'];
				
				$dir = "/data/clips/$merchantID/$date/$buID/$cameraID";
				
				if (!file_exists ($dir)) {
					 
					 mkdir ($dir, 0777, true);
					 chmod ($dir, 0777);
				}

				$timestamp = $timestamp + intval ($offset);
				$start = $timestamp - 30;
				$end = $timestamp + 30;

				$post = json_encode (['start' => date ('Y-m-d\TH:i:s.000\Z', $timestamp - 30),
											 'end' => date ('Y-m-d\TH:i:s.000\Z', $timestamp + 30),
											 'type' => 'Recording']);
				
				$headers = ['Content-type: Application/json',
								'Authorization: Bearer ' . $this->merchant ['video_provider_token'],
								'Content-length: ' . strlen ($post)];	
				
				$url = "https://api.hostedcloudvideo.com/v1/cameras/$altID/exports";
				
				$curl = curl_init ();
				curl_setopt ($curl, CURLOPT_URL, $url);
				curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
				curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
				
				$result = curl_exec ($curl);

				// $result = '{"status": 0}';
				
				if ($result) {

					 $videosTable = TableRegistry::get ('Videos');
					 $video = $videosTable->newEntity (['clip_time' => $timestamp,
																	'business_unit_id' => $this->merchant ['bu_id'],
																	'device_id' => $camera ['id'],
																	'ticket_id' => $ticketID,
																	'status' => 'pending',
																	'file_name' => "$dir/$ticketID-$timestamp.mp4"]);
					 
					 $video = $videosTable->save ($video);
					 
					 $result = array_merge (json_decode ($result, true),
													['timestamp' => $timestamp,
													 'ticket_id' => $ticketID,
													 'video_id' => $video ['id'],
													 'merchant_id' => $this->merchant ['id'],
													 'merchant_name' => $this->merchant ['merchant_name'],
													 'email' => $this->merchant ['email'],
													 'business_unit_id' => $this->merchant ['bu_id'],
													 'camera_id' => $camera ['alt_device_id'],
													 'access_token' => $this->merchant ['video_provider_token']]);
					 
					 $output = "$dir/$ticketID-$timestamp.json";
					 
					 $fp = fopen ($output, 'w');
					 fwrite ($fp, json_encode ($result));
					 fclose ($fp);
				}
				else {

					 $this->debug ('video export failed... ');
				}
		  }

		  $response = ['status' => 0];
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->setTemplate ('ajax');
		  
        $this->set ('response', $response);
        $this->RequestHandler->respondAs ('json');

	 }
	 
	 /**********************************************************************************************
	  *
	  * get video clips
	  *
	  *********************************************************************************************/
	 
	 function clips () {
		  
		  $videos = TableRegistry::get ('Videos')
										 ->find ();
		  
		  $videos = $this->paginate = ['conditions' => [],
												 'limit' => $this->limit];
		  
		  $q = TableRegistry::get ('Videos')
								  ->find ();

		  $videos = $this->paginate ($q);
		  
		  return ($this->response (__ ('Videos'),
											'Videos',
											'clips',
											compact (['videos']),
											true,
											'ajax',
											'videos'));
	 }
	 
	 /**********************************************************************************************
	  *
	  * get missing video clips
	  *
	  *********************************************************************************************/
	 
	 function missingClips ($start, $end) {
		  
		  \Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd H:mm:ss');
		  		  
		  $tickets = TableRegistry::get ('Tickets')
										  ->find ()
										  ->where (['complete_time >=' => $start,
														'complete_time <=' => $end])
										  ->select (['id', 'ticket_no', 'business_unit_id', 'complete_time'])
										  ->all ();
		  
		  $merchantID = $this->merchant ['id'];
		  
		  $cameras = TableRegistry::get ('Devices')
										  ->find ()
										  ->where (['device_type' => 'camera'])
										  ->all ();
		  
		  foreach ($cameras as $camera) {
				
				foreach ($tickets as $ticket) {
					 
					 $date = date ('Ymd', strtotime ($ticket ['complete_time']));
					 $timestamp = strtotime ($ticket ['complete_time']);
					 $buID = $ticket ['business_unit_id'];
					 $cameraID = $camera ['id'];
					 $ticketID = $ticket ['id'];
					 
					 $fname = "/data/snapshots/$merchantID/$date/$buID/$cameraID/$ticketID-$timestamp.jpg";
					 $jname = "/data/snapshots/$merchantID/$date/$buID/$cameraID/$ticketID-$timestamp.json";

					 if (!file_exists ($fname)) {

						  $json = ["timestamp" => $timestamp,
									  "utc" => date ('Y-m-d\TH:i:s.000\Z', $timestamp),
									  "date" => $date,
									  "ticket_id" => $ticketID,
									  "merchant_id" => $this->merchant ['id'],
									  "business_unit_id" => $buID,
									  "camera_id" => $cameraID,
									  "alt_camera_id" => $camera ['alt_device_id'],
									  "access_token" => $this->merchant ['video_provider_token']];
					 }
				}
		  }
		  
		  $response = ['status' => count ($tickets),
							'tickets' => $tickets];
		  
		  $this->viewBuilder ()
				 ->setLayout ('ajax')
             ->setTemplate ('ajax');
		  
        $this->set ('response', $response);
        $this->RequestHandler->respondAs ('json');
	 }

	 function configCamera () {

		  /* $post = ['recordMode' => 'continuous',
			  'recordEnabled' => true,
			  'cloudRecordEnabled' => true,
			  'eventsEnabled' => false,
			  'events' => [],
			  'nasId' => null];

			  $url = 'https://api.hostedcloudvideo.com/v1/cameras/' . $result ['id'] . '/recording/cloud';
			  
			  $post = json_encode ($post);

			  $headers = ['Content-type: Application/json',
			  "Authorization: Bearer " . $this->merchant ['video_provider_token'],
			  'Content-length: ' . strlen ($post)];
			  
			  $this->debug ($url);
			  $this->debug ($headers);
			  $this->debug (json_decode ($post));
			  
			  $curl = curl_init ();
			  curl_setopt ($curl, CURLOPT_URL, $url);
			  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
			  curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
			  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			  curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
			  curl_setopt ($curl, CURLOPT_POST, true);
			  curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
			  curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
			  
			  $result = curl_exec ($curl);

			  $this->debug ($result);
			  
			  if ($result) {

			  
			  $this->debug (json_decode ($result));

			  $response = ['status' => 0,
			  'status_text' => 'SUCCESS'];
			  }
			  else {
			  
			  $statusText = __ ('Unknown error configuring camera, please contact <a href="mailto:support@videoregister.com">VideoRegister Support</a>.');
			  $response = ['status' => 1,
			  'status_text' => $statusText];
			  }*/
	 }
}
?>

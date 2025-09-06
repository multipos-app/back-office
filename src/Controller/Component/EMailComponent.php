<?php

/**********************************************************************************************
 *
 * Copyright (C) 2024 VideoRegister, LLC
 * <http://www.videoregister.com.com>
 *
 * All rights are reserved. No part of the work covered by the copyright
 * hereon may be reproduced or used in any form or by any means graphic,
 * electronic, or mechanical, including photocopying, recording, taping,
 * or information storage and retrieval systems -- without written
 * permission signed by an officer of VideoRegister, LLC.
 *
 */

namespace App\Controller\Component;

use Cake\Controller\Component;

class EMailComponent extends Component {

	 function sendmail ($subject, $from, $to, $html) {

		  require_once ROOT . DS . 'src' . DS  . 'Controller' . DS . 'Component' . DS . 'secrets.php';
		  
		  $result = ['mailgun' => []];
		  
		  $headers [] = 'MIME-Version: 1.0';
		  $headers [] = 'Content-type: text/html; charset=iso-8859-1';
		  $headers [] = "From:  $from";
		  
	  	  $email = ['from'=> $from,
						'to' => $to,
						'subject' => $subject,
						'html' => $html,
						/* 'o:tracking' => 'yes',
							'o:tracking-clicks' => 'yes',
							'o:tracking-opens' => 'yes',
							'o:tag' => 'tag',*/
						'h:Reply-To' => 'subscribed@myvideoregister.com'];
		  
		  $curl = curl_init ('https://api.mailgun.net/v3/mail.myvideoregister.com/messages');  // to mailgun
		  
		  curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		  curl_setopt ($curl, CURLOPT_USERPWD, MAILGUN_PW);
		  curl_setopt ($curl, CURLOPT_POST, true);
		  curl_setopt ($curl, CURLOPT_POSTFIELDS, $email);
		  curl_setopt ($curl, CURLOPT_HEADER, false);
		  curl_setopt ($curl, CURLOPT_ENCODING, 'UTF-8');
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, false);
		  
		  $response = curl_exec ($curl);
		  
		  curl_close ($curl);
		  
		  $result ['mailgun'] = json_decode ($response, true);

		  return $result;
	 }

	 function phpSendmail ($subject, $from, $to, $html) {
		  
		  $result = ['php' => []];
		  
		  $headers [] = 'MIME-Version: 1.0';
		  $headers [] = 'Content-type: text/html; charset=iso-8859-1';
		  $headers [] = "From:  $from";
		 
		  if (mail ($to, $subject . '*', $html, implode("\r\n", $headers))) {
				
				$result ['php'] = ['to' => $to,
										 'subject' => $subject,
										 'status_text' => 'php mail',
										 'status' => 'sent'];
		  }
		  else {
				
				$result ['php'] = ['to' => $to,
										 'subject' => $subject,
										 'status_text' => 'php mail',
										 'status' => 'fail'];
		  }

		  return $result;
	 }
}

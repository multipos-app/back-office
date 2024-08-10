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
use App\Model\Entity\BusinessUnit;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class IntuitController extends PosAppController {


    public function intuitOauth () {
        
        $session = $this->request->getSession ();
        $this->debug ($session->read ('merchant'));

        $this->set ('oauthUrl', 'https://appcenter.intuit.com/app/connect/oauth2/companyselection' .
										  '?client_id=ABuoH57CCK4EAKBORDFyH77G7XfaVq8gAEDwyUwMUBlkkADgC8' .
										  '&scope=com.intuit.quickbooks.accounting' .
										  '&redirect_uri=https://multipos.cloud/business-units/intuit-redir' . 
										  '&response_type=code' .
										  '&state=PlaygroundAuth');
        
    }

    public function intuitRedir () {

        $session = $this->request->getSession ();
        $this->debug ($_SERVER);
        $this->debug ($session->read ('merchant'));

        $this->set ('oath', explode ('&', $_SERVER ['QUERY_STRING']));

        $result = [];
        parse_str ($_SERVER ['QUERY_STRING'], $result);
        $this->debug ($result);

        if (isset ($result ['code'])) {
            
            $clientID = 'ABuoH57CCK4EAKBORDFyH77G7XfaVq8gAEDwyUwMUBlkkADgC8';
            $clientSecret = 'j0aRM4IMUt0hJZEjdOIi8IfYW9gf8IEJZnZOlKHm';
            $auth = base64_encode ($clientID . ':' . $clientSecret);

            $url = 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer';

            $post = json_encode (['grant_type' => 'authorization_code',
                                  'code' => $result ['code'],
                                  'redirect_uri' => 'https://multipos.cloud/business-units/intuit-redir']);

            $post =
                'grant_type=authorization_code'.
                '&code=' . $result ['code'] .
                '&redirect_uri=https://multipos.cloud/business-units/intuit-redir';
            
            $this->debug ($url);
            
            $headers = ["Content-type: application/x-www-form-urlencoded",
                        "Host: oauth.platform.intuit.com",
                        "Accept-Charset: utf-8",
                        "Accept: application/json",
                        "Cache-Control: no-cache",
                        "authorization: Basic " . $auth,
                        "Content-length: " . strlen ($post)];

            $curl = curl_init ();
				
            curl_setopt ($curl, CURLOPT_URL, $url);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt ($curl, CURLOPT_POST, true);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
				
            $response = curl_exec ($curl);
            $this->debug ($response);

            $response = json_decode ($response, true);
            if ($response) {

                $this->debug ($response);
            }
        }
    }

}

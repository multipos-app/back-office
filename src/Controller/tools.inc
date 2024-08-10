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

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

function dbconnect ($dbname) {
    
    ConnectionManager::drop ('default');
    ConnectionManager::setConfig ('default', ['className' => 'Cake\Database\Connection',
                                              'driver' => 'Cake\Database\Driver\Mysql',
                                              'persistent' => false,
                                              'host' => 'localhost',
                                              'username' => 'posappliance',
                                              'database' => $dbname,
                                              'encoding' => 'utf8',
                                              'timezone' => 'UTC',
                                              'cacheMetadata' => true,
    ]);
}

function guid () {
        
    if (function_exists ('com_create_guid') === true) {
        
        return trim (com_create_guid (), '{}');
    }
    
    $data = openssl_random_pseudo_bytes (16);
    $data[6] = chr (ord ($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr (ord ($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf ('%s%s-%s-%s-%s-%s%s%s', str_split (bin2hex ($data), 4));
}

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

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

// ticket types
    
define ("SALE", 0);
define ("VOID", 1);
define ("NO_SALE", 2);
define ("COMP_SALE", 3);
define ("LOGIN", 4);
define ("LOGOUT", 5);
define ("X_SESSION", 6);
define ("Z_SESSION", 7);
define ("RETURN_SALE", 8);
define ("SALE_NONTAX", 9);
define ("BANK", 10);
define ("DRAWER_COUNT", 11);
define ("CREDIT_REFUND", 16);
define ("CREDIT_REVERSE", 17);
define ("OPEN_AMOUNT", 21);
define ("MANAGER_OVERRIDE", 22);
define ("ORDER_ITEMS", 23);
define ("REFUND", 24);

define ("DISCOUNTS", 101);
define ("VOID_ITEMS", 102);

// ticket states
    
define ("OPEN", 0);
define ("COMPLETE", 1);
define ("ERROR", 2);
define ("SUSPEND", 3);
define ("CREDIT_PENDING", 4);
define ("KITCHEN_PENDING", 5);
define ("VOIDED", 6);
define ("REFUNDED", 7);
define ("REVERSED", 8);
define ("RECALLED", 9);

define ("EXCHANGE_ITEM", 1);

define ("TICKET_ITEM_STANDARD", 0);
define ("TICKET_ITEM_VOID_ITEM", 1);
define ("TICKET_ITEM_COMP_ITEM", 2);
define ("TICKET_ITEM_RETURN_ITEM", 3);
define ("TICKET_ITEM_SERVICE_CHARGE", 4);
define ("TICKET_ITEM_MODIFIER", 5);

// department types

define ("MERCHANDISE_DEPARTMENT", 1);
define ("MENU_DEPARTMENT",  2);
define ("BANK_DEPARTMENT",  3);
define ("DEPOSIT_DEPARTMENT",  4);
define ("OTHER_DEPARTMENT",  5);
define ("LABOR_DEPARTMENT", 6);

define ("ONE_DAY", 24 * 60 * 60);
define ("ONE_HOUR", 60 * 60);
define ("ONE_WEEK", 7 * 24 * 60 * 60);

define ("BU_CORP", 1);
define ("BU_LOCATION", 2);
define ("BU_REGION", 3);

?>

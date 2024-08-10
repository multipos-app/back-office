<?php

/**
 *
 * Usage: bin/cake ConsumeTickets <dbname>
 *
 */

namespace App\Shell;
use App\Controller\PosController;
use Cake\Console\Shell;

class DelItemsShell extends Shell {

    public function main ($dbname, $itemID) {

        $posController = new PosController ();
        $posController->delItems ($dbname, $itemID);
    }
}

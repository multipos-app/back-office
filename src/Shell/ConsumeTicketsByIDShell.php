<?php

/**
 *
 * Usage: bin/cake ConsumeTickets <dbname>
 *
 */

namespace App\Shell;
use App\Controller\PosTicketsController;
use Cake\Console\Shell;

class ConsumeTicketsByIDShell extends Shell {

    public function main ($dbname, $ticketID) {

        $posController = new PosTicketsController ();
        $posController->handleTickets ($dbname, $ticketID);
    }
}

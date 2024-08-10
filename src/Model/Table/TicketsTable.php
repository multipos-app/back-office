<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TicketsTable extends Table {

    public function initialize (array $config): void {
        
      $this->hasMany ('TicketItems');
      $this->hasMany ('TicketTaxes');
      $this->hasMany ('TicketTenders');
    }
}

?>

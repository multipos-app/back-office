<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TaxGroupsTable extends Table {
	 
    public function initialize (array $config): void {
        
        $this->hasMany ('Taxes');
   }
}

?>

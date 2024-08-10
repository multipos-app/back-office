<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CurrenciesTable extends MerchantsTable {
	 
    public function initialize (array $config):void {
        
        $this->setTable ('currencies');
        $this->hasMany ('CurrencyDenoms')->setDependent (true)->setSaveStrategy ('replace');
    }
}

?>

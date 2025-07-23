<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;
    
class ItemsTable extends Table {
    
    public function initialize (array $config): void {

        $this->hasMany ('ItemPrices');
		  $this->hasMany ('ItemLinks');
		  $this->hasMany ('InvItems');
		  $this->hasMany ('SalesItemTotals');
   }
}

?>

<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;
    
class ItemsTable extends Table {
    
    public function initialize (array $config): void {
        
        /* $this->hasMany ('ItemPrices', ['dependent' => true, 'cascadeCallbacks' => true]);
			* $this->hasMany ('ItemLinks', ['dependent' => true, 'cascadeCallbacks' => true]);
			* $this->hasMany ('InvItems', ['dependent' => true, 'cascadeCallbacks' => true]);
			* $this->hasMany ('SalesItemTotals', ['dependent' => true, 'cascadeCallbacks' => true]);
			*/

        $this->hasMany ('ItemPrices');
		  $this->hasMany ('ItemLinks');
		  $this->hasMany ('InvItems');
		  $this->hasMany ('SalesItemTotals');
   }
}

?>

<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;

class ItemAddonsTable extends Table {
	 
    public function initialize (array $config): void {
        
        $this->hasMany ('ItemAddonLinks', ['dependent' => true, 'cascadeCallbacks' => true]);
   }
}
?>

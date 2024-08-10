<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;

class AddonsTable extends Table {
	 
    public function initialize (array $config): void {
        
        $this->hasMany ('AddonLinks', ['dependent' => true, 'cascadeCallbacks' => true]);
   }
}
?>

<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;

class BatchesTable extends Table {
    
    public function initialize (array $config): void {
		  
        $this->hasMany ('BatchEntries', ['dependent' => true,
													  'cascadeCallbacks' => true]);
    }
}

?>

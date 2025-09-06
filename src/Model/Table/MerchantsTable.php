<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MerchantsTable extends Table {
	 
    public function initialize (array $config): void {
        
        $this->hasMany ('MerchantUsers', ['dependent' => true, 'cascadeCallbacks' => true]);
        $this->hasMany ('MerchantActions', ['dependent' => true, 'cascadeCallbacks' => true]);
        $this->hasMany ('MerchantUserActions', ['dependent' => true, 'cascadeCallbacks' => true]);
	 }
}

?>

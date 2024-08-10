<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ProfilesTable extends MerchantsTable {
    
    public function initialize (array $config): void {

        $this->hasMany ('ProfilePermissions');
        $this->hasMany ('Employees');
    }
}

?>

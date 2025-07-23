<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;

class PosControlCategoriesTable extends MerchantsTable {

    public function initialize (array $config): void {
        
        $this->setTable ('pos_control_categories')
             ->hasMany ('PosControls');
    }
}

?>

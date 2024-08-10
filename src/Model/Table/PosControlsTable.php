<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\EntityInterface;

class PosControlsTable extends MerchantsTable {

    public function initialize (array $config): void {
        
        $this->belongsTo ('PosControlCategories',
                          ['foreignKey' => 'pos_control_category_id']);
    }
}

?>

<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

const POS_BATCH = 0;
const FULL_BATCH = 1;
const EMPLOYEE_BATCH = 1;
const CONFIG_BATCH = 3;

class BatchComponent extends Component {

	 public function createBatch ($batchType = 0, $businessUnitID = 0, $batchDesc = '', $submit = null) {
		  
        $batch = ['batch_desc' => $batchDesc,
                  'submit_date' => $submit,
                  'batch_type' =>  $batchType,
                  'business_unit_id' => $businessUnitID];

        $batchTable = TableRegistry::get ('Batches');
        $batch = $batchTable->save ($batchTable->newEntity ($batch));
        
        $batchID = $batch ['id'];
        $this->updateCount = 0;
		  
        $models = [['model' => 'BusinessUnits'],
                   ['model' => 'PosConfigs'],
                   ['model' => 'TaxGroups',
                    'contain' => ['Taxes']],
                   ['model' => 'Customers'],
                   ['model' => 'Departments'],
                   ['model' => 'Employees'],
                   ['model' => 'Profiles',
                    'contain'=> ['ProfilePermissions']],
                   ['model' => 'Items'],
                   ['model' => 'ItemLinks'],
                   ['model' => 'Suppliers'],
                   ['model' => 'Tenders'],
                   ['model' => 'Currencies',
                    'contain' => ['CurrencyDenoms']],
                   ['model' => 'Addons',
                    'contain' => ['AddonLinks']]];

        switch ($batchType) {

				case 2:
                
					 $models = [['model' => 'BusinessUnits'],
									['model' => 'Employees'],
									['model' => 'Profiles',
									 'contain'=> ['ProfilePermissions']]];
                // ['model' => 'Currencies',
                //  'contain' => ['CurrencyDenoms']]];
					 break;

				case 3:
                
					 $models = [['model' => 'BusinessUnits'],
									['model' => 'PosConfigs'],
									['model' => 'TaxGroups',
									 'contain' => ['Taxes']],
									['model' => 'Customers'],
									['model' => 'Departments'],
									['model' => 'Employees'],
									['model' => 'Customers'],
									['model' => 'Profiles',
									 'contain'=> ['ProfilePermissions']],
									['model' => 'Suppliers'],
									['model' => 'Tenders'],
									['model' => 'Currencies',
									 'contain' => ['CurrencyDenoms']]];
					 
					 break;
        }

		  // $this->getController->debug ($models);
		  
        foreach ($models as $model) {
            
            $this->getUpdates ($model, $batch ['id'], $businessUnitID);
        }

        /* $this->posUpdate (['method' => 'update',
			*                    'text' => 'Downloading updates...']);
			*/

		  $batch ['update_count'] = $this->updateCount;
        $batch = $batchTable->save ($batch);
        
        return $batch ['id'];
    }

    public function getUpdates ($model, $batchID, $businessUnitID) {
        
        $query = TableRegistry::get ($model ['model']);
        
        $table = $query->getTable ();

        if (isset ($model ['contain'])) {

				if ($model ['model'] == 'Items') {

					 $query = $query
                   ->find ()
                   ->contain ($model ['contain']);
				}
				else {
					 
					 $query = $query
                   ->find ()
                   ->contain ($model ['contain']);
				}
        }
        else {

            $query = $query
                   ->find ();
        }

        foreach ($query as $m) {
            
            $this->addBatchEntry ($m->toArray (), $table, $batchID, $businessUnitID);
        }
    }

    public function addBatchEntry ($m, $table, $batchID, $businessUnitID) {

		  // $this->getController ()->debug ($m);
		  
        $batchEntry = ['business_unit_id' => $businessUnitID,
                       'update_table' => $table,
                       'batch_id' => $batchID,
                       'update_id' => $m ['id'],
                       'update_action' => 0,
                       'execution_time' => time ()];
        
        $batchEntriesTable = TableRegistry::get ('BatchEntries');
        $batchEntry = $batchEntriesTable->newEntity ($batchEntry);
        $batchEntriesTable->save ($batchEntry);
		  
        $this->updateCount ++;

        foreach ($m as $colName => $col) {

            if (is_array ($col)) {
                
                foreach ($col as $relation) {
                    
                    $this->addBatchEntry ($relation, $colName, $batchID, $businessUnitID);
                }
            }
        }
    }
}

?>

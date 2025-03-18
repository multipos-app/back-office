<?php
/**
 * Copyright (C) 2023 multiPos, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Employee;
use App\Model\Entity\Profile;
use App\Model\Entity\ProfilePermission;

class ProfilesController extends PosAppController {

    public function index (...$params) {

        $query = TableRegistry::get ('Profiles')->find ('all');
        $profiles = $this->paginate ($query);
		  $this->set (['merchant' => $this->merchant,
							'profiles' => $profiles]);
	 }

    function edit ($id = null) {

        $profileDesc = '';
        $permissions = [];
        $profiles = TableRegistry::get ('Profiles');
        
        if ($id != null) {
       
            $profile = $profiles
                     ->find ()
                     ->where (['id' => $id])
                     ->contain (['ProfilePermissions'])
                     ->first ();

            if ($profile) {
                
                $profileDesc = $profile ['profile_desc'];
                
                foreach ($profile ['profile_permissions'] as $permission) {

                    $permissions [] = str_replace ('cloud.multipos.pos.controls.', '', $permission ['profile_class']);
                }
            }
            
            $this->debug ($profile ['profile_desc']);
        }
        else {
            $id = 0;
        }
        
        $query = TableRegistry::get ('PosControlCategories')
               ->find ()
               ->where (['enabled' => 1])
               ->contain (['PosControls']);

        $categoryID = 0;
        $categories = [];
        foreach ($query as $category) {

            if ($categoryID == 0) {

                $categoryID = $category ['id'];
            }
            
            $categories [$category ['id']] = $category->toArray ();
                      
            for ($i = 0; $i < count ($categories [$category ['id']] ['pos_controls']); $i ++) {

                $categories [$category ['id']] ['pos_controls'] [$i] ['checked'] = 1;
                    
                foreach ($permissions as $permission) {
                        
                    if ($permission == $categories [$category ['id']] ['pos_controls'] [$i] ['class'])  {
                            
                        $categories [$category ['id']] ['pos_controls'] [$i] ['checked'] = 0;
                    }
                }
            }
        }

		  $this->set (['profile' => $profile,
							'profileDesc' => $profileDesc,
							'categoryID' => $categoryID,
							'categories' => $categories,
							'permissions' => $permissions]);
    }
  
    public function update ($id) {
        
        $profiles = TableRegistry::get ('Profiles');
        $status = -1;
        
        if (!empty ($this->request->getData ())) {

            $this->debug ("id... " . $id);
            $this->debug ($this->request->getData ());

            if ($id == null) {

                $profile = $profiles->newEntity (['profile_desc' => strtoupper ($this->request->getData () ['profile_desc'])]);
                $profiles->save ($profile);
                $id = $profile ['id'];
            }
            else {

                $profile = $profiles
                         ->find ()
                         ->where (['id' => $id])
                         ->first ();
                
                $profile ['profile_desc'] = strtoupper ($this->request->getData () ['profile_desc']);
                $profiles->save ($profile);
            }

            if (isset ($this->request->getData () ['permissions'])) {  // any permissions?
                
                $profilePermissions = TableRegistry::get ('ProfilePermissions');

                $id = intVal ($id);
                $profilePermissions->deleteAll (['profile_id' => $id]);
                
                foreach ($this->request->getData () ['permissions'] as $c => $permission) {
                    
                    $profilePermission = $profilePermissions->newEntity (['profile_id' => $id,
                                                                          'profile_class' => $permission]);
                    $profilePermissions->save ($profilePermission);
                }
                
                $batchEntriesTable = TableRegistry::get ('BatchEntries');
                $batchEntry = $batchEntriesTable->newEntity (['business_unit_id' => $this->merchant ['bu_id'],
																				  'update_table' => 'profiles',
																				  'update_id' => $id,
																				  'update_action' => 0,
																				  'execution_time' => time ()]);
                
                $batchEntriesTable->save ($batchEntry);
                $status = 0;
            }
        }
        
        $this->viewBuilder ()->setLayout ('ajax');
        $this->set ('response', ['status' => $status]);

    }
}
?>

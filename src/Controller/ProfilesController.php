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

    function edit ($id) {

		  if (!empty ($this->request->getData ())) {
				
				$update = $this->update ($id, $this->request->getData ());
				
				$this->ajax (['status' => 0,
								  'item' => $update]);
				return;
		  }
		  
        $profileDesc = '';
        $permissions = [];
        $profiles = TableRegistry::get ('Profiles');
        
        if ($id > 0) {
				
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
        }
        else {
            $profile = ['id' => 0,
								'profile_desc' => ''];
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
	 
    public function update ($id, $profile) {

		  $permissions = $profile ['permissions'];
        $profilesTable = TableRegistry::get ('Profiles');
        $status = -1;
		  
        if ($id == 0) {

            $profile = $profilesTable->newEntity (['profile_desc' => strtoupper ($profile ['profile_desc'])]);
            $profilesTable->save ($profile);
            $id = $profile ['id'];
        }
        else {

            $profile = $profilesTable
                         ->find ()
                         ->where (['id' => $id])
                         ->first ();
            
            $profile ['profile_desc'] = strtoupper ($profile ['profile_desc']);
            $profilesTable->save ($profile);
        }
		  
        $profilePermissioinsTable = TableRegistry::get ('ProfilePermissions');
		  
        $id = intVal ($id);
        $profilePermissioinsTable->deleteAll (['profile_id' => $id]);
        
        foreach ($permissions as $javaClass => $permission) {
				
				
            if ($permission == 'off') {
					 					 
					 $profilePermission = $profilePermissioinsTable->newEntity (['profile_id' => $id,
																									 'profile_class' => $javaClass]);
					 $profilePermissioinsTable->save ($profilePermission);
				}
        }
    }
}

?>

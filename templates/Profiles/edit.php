<style>

 .category-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: repeat(4, 1fr);
	  grid-column-gap: 0px;
	  margin-top: 25px;
 }
 
</style>

<form id="profile_edit" name="profile_edit" method="post" action="/profiles/edit/<?= $profile ['id'] ?>">

	 <div class="row g-1 m-3">
		  <label for="profile_desc" class="col-sm-1 form-label"><?= __('Profile') ?></label>
		  <div class="col-sm-2">

				<?= 
				$this->Form->input ('profile_desc', 
										  ['id' => 'profile_desc', 
											'value' => $profile ['profile_desc'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="category-grid">
		  
		  <?php
		  foreach ($categories as $id => $category) {

				$this->debug ("category... " . $category ['name'] . ' ' . count ($category ['pos_controls']));
				
				if (count ($category ['pos_controls']) == 0) continue;
				
		  ?>
				<div class="grid-span-all text-center mt-3"><h5><?= $category ['name'] ?></h5></div>
				
				<?php
				foreach ($category ['pos_controls'] as $control) {
					 
					 if ($control ['approval'] == 1) {
						  
						  $checked = ' checked';
						  if (in_array ($control ['class'], $permissions)) {
								
								$checked = '';
						  }
					 }
				?>
				
				<div class="form-check form-switch">
					 
					 <input type="checkbox" class="form-check-input" id="<?= $control ['class'] ?>" name="<?= $control ['class'] ?>"<?= $checked ?>>
					 <label class="grid-label" for="<?= $control ['class'] ?>"><?= $control ['description'] ?></label>
					 
				</div>
				
		  <?php
		  }
		  }
		  ?>

		  <div class="text-center grid-span-all mt-3">
				<button type="submit" class="btn btn-success">Save</button>
		  </div>
	 </div>
	 

</form>

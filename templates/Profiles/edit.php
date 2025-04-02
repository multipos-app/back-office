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

<form id="profile_form" name="profile_form" method="post" ">

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
					 
					 <input type="hidden" name="permissions[<?= $control ['class'] ?>]" value="off">
					 <input type="checkbox" class="form-check-input profile-modify" id="<?= $control ['class'] ?>" name="permissions[<?= $control ['class'] ?>]"<?= $checked ?>>
					 <label class="grid-label" for="<?= $control ['class'] ?>"><?= $control ['description'] ?></label>
					 
				</div>
				
		  <?php
		  }
		  }
		  ?>
	 </div>
	 
	 <!-- save changes -->
	 
	 <div class="grid-span-all">
		  <div class="row g-3 mt-5">
				<div class="col-sm-9"></div>
 				<div class="col-sm-3 d-grid">
					 <button type="submit" class="btn btn-secondary" id="save_profile"><?= __ ('Save changes') ?></button>
				</div>
		  </div>
	 </div>

</form>

<script>

 let formModified = false;
 let profileID = <?= $profile ['id'] ?>;

 $('form :input').change (function() {
     formModified = true;
 	  $('#save_profile').removeClass ('btn-secondary');
	  $('#save_profile').addClass ('btn-success');
 });

 window.onbeforeunload = function(event) {
     if (formModified) {
			return "Are you sure you want to leave? Changes you made may not be saved.";
     }
 };

 $('#profile_form').submit (function (e) {
	  
	  e.preventDefault ();
	  formModified = false;

	  let data = new FormData (this);

	  console.log (data);

	  $.ajax ({url: `/profiles/edit/${profileID}`,
				  type: 'POST',
				  data: data,
				  processData: false,
				  contentType: false,
				  success: function (data) {
						
						window.location = '/profiles';
				  }
		  });
 });

</script>

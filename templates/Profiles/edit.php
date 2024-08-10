<?= $this->Html->css ("Profiles/edit") ?>

<script>
 
 var profile = <?php echo json_encode ($profile, true); ?>;
 var permissions = <?php echo json_encode ($permissions, true); ?>;
 
 console.log (profile);
 console.log (permissions);

</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= __ ('Employee profile edit') ?>
</div>

<form id="profile_edit" name ="profile_edit">

	 <div class="form-grid profile-name-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __ ('Profile name')?></div>
		  <div class="form-cell form-control-cell">
				<input type="text" id="profile_desc" name="profile_desc" value="<?= $profile ['profile_desc'] ?>" class="form-control" placeholder="<?= __ ('Profile description') ?>"/>
		  </div>
	 </div>
	 
	 <div class="category-grid">
		  
		  <?php
		  foreach ($categories as $id => $category) {
		  ?>
				
				<div class="grid-cell grid-cell-left grid-cell-separator grid-span-all"><?= $category ['name'] ?></div>
				
				<?php
				foreach ($category ['pos_controls'] as $control) {

					 if ($control ['approval'] == 1) {
						  
						  $checked = ' checked';
						  if (in_array ($control ['class'], $permissions)) {
								
								$checked = '';
						  }
					 }
				?>
				
				<div class="grid-cell grid-cell-left">
					 
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" id="<?= $control ['class'] ?>" name="<?= $control ['class'] ?>" type="checkbox"<?= $checked ?>>
						  <label class="grid-label" for="<?= $control ['class'] ?>"><?= $control ['description'] ?></label>
					 </div>
				</div>
				
		  <?php
		  }
		  }
		  ?>
	 </div>
</div>

<div class="form-submit-grid">
	 
	 <div>
		  <button type="submit" id="profile_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div>
		  <button type="button" class="btn btn-warning" onclick="del ('profiles', <?= $profile ['id']?>, '<?= __ ('Delete') ?> <?= $profile ['profile_desc'] ?>')"><?= __ ('Delete') ?></button>
	 </div>
	 
</div>

</form>
<?= $this->Html->script ("Profiles/edit") ?>


<?= $this->Html->css ("Profiles/index") ?>

<div class="controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>

	 <div></div>
	 
	 <div class="form-cell form-right">
		  <a onclick="javascript:add ()" class="btn btn-secondary"><?= __ ('Add profile'); ?></a>
	 </div>
	 
</div>

<div class="profile-grid">

	 <div class="grid-cell grid-cell-left grid-cell-separator"></div>	 
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description') ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator "><?= __ ('Employees') ?></div>

	 <?php 
	 foreach ($profiles as $profile) {
		  
		  $action = 'onclick="openForm (' . $profile ['id'] . ',\'/profiles/edit/' . $profile ['id'] . '\')"';

	 ?>
		  
		  <div class="grid-row-wrapper" <?= $action ?>>

				<div id="tag_<?= $profile ['id'] ?>" class="grid-cell grid-cell-left"></div>
				<div class="grid-cell grid-cell-left"><?= $profile ['profile_desc'] ?></div>
				<div class="grid-cell grid-cell-center"><i class="far fa-magnifying-glass fa-med text-left"></i></div>
				
		  </div>
		  
	 <?php
	 }
	 ?>

</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("Profiles/index") ?>

<?= $this->Html->css ("Employees/index") ?>

</style>

<div class="controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
 
	 
	 <div></div>
	 
	 <div class="grid-cell grid-cell-right">
		  <a onclick="openForm ('0','/employees/edit/0')" class="btn btn-secondary"><?= __ ('Add employee'); ?></a>
	 </div>
	 
</div>

<div class="employee-grid">
	 
	 <div class="grid-cell grid-cell-left grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator "><?= __ ('Name') ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Employee Number') ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator "><?= __ ('Profile') ?></div>

	 <?php 

	 $this->debug ($employees);
	 $this->debug ($profiles);
	 
	 foreach ($employees as $employee) {
		  
		  $action = 'onclick="openForm (' . $employee ['id'] . ',\'/employees/edit/' . $employee ['id'] . '\')"';
		  $profileDesc =  '';
		  if ($employee ['profile_id']) {

				$profileDesc = $profiles [$employee ['profile_id']] ['profile_desc'];
		  }
		  
	 ?>
		  
		  <div class="grid-row-wrapper" <?= $action ?>>

				<div id="tag_<?= $employee ['id'] ?>" class="grid-cell grid-cell-left"></div>
				<div class="grid-cell grid-cell-left"><?= $employee ['fname'].' '.$employee ['lname'];?></div>
				<div class="grid-cell grid-cell-left"><?= $employee ['username'] ?></div>
				<div class="grid-cell grid-cell-left"><?= $profileDesc ?></div>
				
		  </div>

	 <?php
	 }
	 ?>
	 
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("Employees/index") ?>

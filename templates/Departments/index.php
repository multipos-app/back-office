<?= $this->Html->css ("Departments/index") ?>

<div class="controls-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
	 
	 <div></div>

	 <div class="form-cell form-right">
		  <a id="add_department" class="btn btn-secondary" onclick="openForm(0, '/departments/edit/0')"><?= __ ('Add department'); ?></a>
	 </div>
	 
</div>

<div class="department-grid">

	 <div class="grid-cell grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Department'); ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Items'); ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Type'); ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Negative/Deposits'); ?></div>
	 <div class="grid-cell grid-cell-separator"></div>

<?php

foreach ($departments as $department) {
	 
	 $departmentType = $departmentTypes [$department ['department_type']];
	 $isNegative = $department ['is_negative'] ? __('Yes') : __ ('No');
	 $action = '';
	 $locked = '<i class="fa fa-lock fa-med"></i>';
	 if ($department ['locked'] == 0) {
				
		  $action = 'onclick="openForm (' . $department ['id'] . ',\'/departments/edit/' . $department ['id'] . '\')"';
		  $locked = '';
	 }
?>

	 <div class="grid-row-wrapper" <?= $action ?>>
		  
		  <div id="tag_<?= $department ['id'] ?>" class="grid-cell grid-cell-left"></div>
		  <div class="grid-cell grid-cell-left tag_<?= $department ['id'] ?>"><?= $department ['department_desc'] ?></div>
		  <div class="grid-cell grid-cell-center"><i class="far fa-magnifying-glass fa-med text-left" onclick="javascript:items (<?= $department ['id'] ?>)"></i></div>
		  <div class="grid-cell grid-cell-center"><?= $departmentType ?></div>
		  <div class="grid-cell grid-cell-center"><?= $isNegative ?></div>
		  <div class="grid-cell grid-cell-center"><?= $locked ?></div>
	 </div>
<?php
}
?>

</div>
<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("Departments/index") ?>


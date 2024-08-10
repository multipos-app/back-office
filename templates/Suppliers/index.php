<?= $this->Html->css ("Suppliers/index") ?>

<div class="form-grid controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
 
	 <div class="form-cell form-control-cell">
		  <?php echo $this->Form->control ('contact',  ['id' => 'contact', 'class' => 'form-control typeahead', 'label' => false, 'placeholder' => __ ('Contact')]); ?>
	 </div>

	 <div class="form-cell form-control-cell">
		  <?php echo $this->Form->control ('email',  ['id' => 'email', 'class' => 'form-control typeahead', 'label' => false, 'placeholder' => __ ('Email')]); ?>
	 </div>

	 <div class="form-cell form-control-cell">
		  <?php echo $this->Form->control ('phone',  ['id' => 'phone', 'class' => 'form-control typeahead', 'label' => false, 'placeholder' => __ ('Phone')]); ?>
	 </div>

	 <div class="form-cell"></div>

	 <div class="form-cell form-control-cell form-right">
		  <a onclick="openForm ('0','/suppliers/edit/0')" class="btn btn-secondary"><?= __ ('Add supplier'); ?></a>
	 </div>

</div>

<div class="supplier-grid">
	 
	 <div class="grid-cell grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= ('Supplier name') ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Contact') ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Phone') ?></div>

	 <?php

	 foreach  ($suppliers as $supplier) {
		  
		  $action = 'onclick="openForm (' . $supplier ['id'] . ',\'/suppliers/edit/' . $supplier ['id'] . '\')"';
	 ?>
		  
		  <div class="grid-row-wrapper" <?= $action ?>>

				<div id="tag_<?=$supplier ['id'] ?>" class="grid-cell grid-cell-left"></div>
				
				<div class="grid-cell grid-cell-left">
					 <?= $supplier ['supplier_name'] ?>
				</div>

				<div class="grid-cell grid-cell-left">
					 <?= $supplier ['contact1'] ?>
				</div>

				<div class="grid-cell grid-cell-left">
					 <?php echo $supplier ['phone1']  ?>
				</div>
				
		  </div>
	 <?php
	 }
	 
	 ?>
	 
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("Suppliers/index") ?>

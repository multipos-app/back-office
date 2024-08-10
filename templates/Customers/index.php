<?= $this->Html->css ("Customers/index") ?>

<div class="form-grid controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
 
	 <div class="form-cell form-control-cell">
		  <?php echo $this->Form->control ('phone',  ['id' => 'phone', 'class' => 'form-control typeahead', 'label' => false, 'placeholder' => __ ('Phone')]); ?>
	 </div>

	 <div class="form-cell form-control-cell">
		  <?php echo $this->Form->control ('contact',  ['id' => 'contact', 'class' => 'form-control typeahead', 'label' => false, 'placeholder' => __ ('Contact')]); ?>
	 </div>

	 <div class="form-cell form-control-cell">
		  <?php echo $this->Form->control ('email',  ['id' => 'email', 'class' => 'form-control typeahead', 'label' => false, 'placeholder' => __ ('Email')]); ?>
	 </div>

	 <div class="form-cell"></div>

	 <div class="form-cell form-control-cell form-right">
		  <a onclick="openForm ('0','/customers/edit/0')" class="btn btn-secondary"><?= __ ('Add customer'); ?></a>
	 </div>

</div>

<div class="customer-grid">
	 
	 <div class="grid-cell grid-cell-separator grid-cell-left"></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Email') ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Phone') ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Name') ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-center"></div>

	 <?php
	 
	 foreach  ($customers as $customer) {
		  
		  $action = 'onclick="openForm (' . $customer ['id'] . ',\'/customers/edit/' . $customer ['id'] . '\')"';
	 ?>
		  
		  <div class="grid-row-wrapper" <?= $action ?>>

				<div id="tag_<?= $customer ['id'] ?>" class="grid-cell"></div>

				<div class="grid-cell grid-cell-left">
					 <?= $customer ['email'] ?>
				</div>

				<div class="grid-cell grid-cell-left">
					 <?php echo $customer ['phone']  ?>
				</div>
				
				<div class="grid-cell grid-cell-left">
					 <?= $customer ['fname'] ?>&nbsp;<?= $customer ['lname']?>
				</div>

				<div class="grid-cell grid-cell-center">
					 <a onclick="controller ('tickets', false)" class="report-link"> <?= __ ('Tickets') ?></a>
				</div>
												
		  </div>
	 <?php
	 }
	 
	 ?>
	 
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("Customers/index") ?>

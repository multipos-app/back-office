<?= $this->Html->css ("Discounts/index") ?>

<div class="discount-controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>

	 <?php
    echo $this->input ('fa-text-size',
                       ['id' =>'addon_typeahead',
								'class' =>'form-control',
							  'placeholder' => __ ('Discount description')]);
	 ?>
	 
	 <div class="grid-cell grid-cell-left action-icon" onclick="search ('addon_desc_search')">
		  <i class="far fa-search fa-med"></i>
	 </div>

	 <div class="form-cell form-right">
		  
		  <?php echo $this->Form->select ('add_discount',
													 $addonTypes,
													 ['id' => 'add_discount',
													  'class' => 'custom-dropdown',
													  'label' => false]); ?>
	 </div>
</div>	 

<div class="discount-grid">

	 <div class="grid-cell grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description'); ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Print Description'); ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Start Date'); ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('End Date'); ?></div>
	 
	 <?php
	 
	 foreach  ($addons as $addon) {

		  foreach (['start_time', 'end_time'] as $date) {

				$addon [$date] = strlen ($addon [$date]) > 0 ? $addon [$date] : '---';
		  }
		  
		  $action = 'onclick="openForm (' . $addon ['id'] . ',\'/discounts/' . $addon ['addon_type'] . '/' . $addon ['id'] . '\')"';
	 ?>
	 
	 <div class="grid-row-wrapper" <?= $action ?>>
		  
		  <div class="grid-cell grid-cell-left tag_<?= $addon ['id'] ?>"></div>

		  <div class="grid-cell grid-cell-left">
				<?= $addon ['description'] ?>
		  </div>

		  <div class="grid-cell grid-cell-left">
				<?= $addon ['print_description'] ?>
		  </div>

		  <div class="grid-cell grid-cell-center">
				<?= $addon ['start_time'] ?>
		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				<?= $addon ['end_time'] ?>
		  </div>
		  
	 </div>
		  <?php
		  }
		  ?>
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script (['Discounts/index']); ?>

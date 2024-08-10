<?= $this->Html->css ("Pricing/index") ?>

<div class="controls-grid">

	 <div></div>
	 
	 <div class="form-cell form-right">
		  
		  <?php
		  
		  echo $this->Form->select ('add_pricing',
											 $pricingTypes,
											 ['id' => 'add_pricing',
											  'class' => 'custom-dropdown',
											  'label' => false]);
		  ?>
	 </div>
	 
</div>

<div class="pricing-grid">

	 <div class="grid-cell grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Name'); ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description'); ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"></div>
	 
	 <?php
	 
	 
	 foreach  ($pricing as $p) {

		  $action = '';
		  $locked = '<i class="fa fa-lock fa-med"></i>';
		  if ($p ['locked'] == 0) {
				
				$action = 'onclick="openForm (' . $p ['id'] . ',\'/pricing/' . $p ['class'] . '/' . $p ['id'] . '\')"';
				$locked = '';
		  }
	 ?>
	 
	 <div class="grid-row-wrapper" <?= $action ?>>
	 	  <div id="tag_<?= $p ['id'] ?>" class="grid-cell grid-cell-left"></div>
		  <div class="grid-cell grid-cell-left"><?= $p ['name']?></div>
		  <div class="grid-cell grid-cell-left"><?= $p ['description'] ?></div>
		  <div class="grid-cell grid-cell-left"><?= $locked ?></div>
	 </div>
	 
		  <?php
		  }
		  ?>
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("Pricing/index") ?>

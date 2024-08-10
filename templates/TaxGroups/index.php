
<?= $this->Html->css ("TaxGroups/index") ?>

<div class="controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>

	 <div></div>
	 
	 <div class="grid-cell grid-cell-right">
		  <a onclick="javascript:edit (0)" class="btn btn-secondary"><?= __ ('Add tax group'); ?></a>
	 </div>
</div>

<div class="tax-grid">
	 
	 <div class="grid-cell grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-separator"><?= __ ('Description') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Rate', true) ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Alt rate', true) ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Fixed rate', true) ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Fixed alt rate', true) ?></div>
	 
	 <?php
	 
	 foreach  ($taxGroups as $taxGroup) {
	 	  
		  $rate = 0;
		  $altRate = 0;
		  $fixedRate = 0;
		  $fixedAltRate = 0;
		  
		  $action = 'onclick="openForm (' . $taxGroup ['id'] . ',\'/tax-groups/edit/' . $taxGroup ['id'] . '\')"';
		  
		  foreach ($taxGroup ['taxes'] as $tax) {  // sum the rates

				switch ($tax ['type']) {

					 case 'percent':
						  
						  $rate += $tax ['rate'];
						  $altRate += $tax ['alt_rate'];
						  break;

					 case 'fixed':
						  
						  $fixedRate += $tax ['rate'];
						  $fixedAltRate += $tax ['alt_rate'];
						  break;
				}
		  } 
		  
	 ?>
	 
	 <div class="grid-row-wrapper" <?= $action ?>>
		  
		  <div id="tag_<?= $taxGroup ['id'] ?>" class="grid-cell grid-cell-left"></div>
		  
		  <div class="grid-cell grid-cell-left">
				<?=$taxGroup ['short_desc'] ?>
		  </div>
		  
		  <div class="grid-cell grid-cell-right">
				<?= $rate.'%' ?>
		  </div>
		  
		  <div class="grid-cell grid-cell-right">
				<?= $altRate.'%' ?>
		  </div>

		  <div class="grid-cell grid-cell-right">
				<?= $fixedRate ?>
		  </div>
		  
		  <div class="grid-cell grid-cell-right">
				<?= $fixedAltRate ?>
		  </div>

	 </div>

	 <?php
	 }
	 ?>

</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script ("TaxGroups/index") ?>

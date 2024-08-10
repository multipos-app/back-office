<style>
  
 .pricing-grid {

     width: 50%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
 	  grid-column-gap: 15px;
 	  grid-row-gap: 25px;
	 }

</style>

<?php

$this->log ($pricingDesc, 'debug');
$this->log ($pricing, 'debug');

echo $this->Form->hidden ('class', ['value' => $pricing ['class']]);

if (isset ($pricingID)) {
	 
	 echo $this->Form->hidden ('pricing_id', $pricingID);
}

?>

<form id="pricing_edit" name ="pricing_edit">

	 <input type="hidden" name="class" va;ie ="<?= $pricing ['class']?>"/>
	 <input type="hidden" name="pricing_id" va;ie ="<?= $pricing ['id']?>"/>
	 
	 <div class="form-section">
		  <?= $pricing ['category']  ?>
	 </div>

	 <div class="form-grid pricing-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __('Name') ?></div>

		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('pricing[name]', ['class' => 'form-control', 'label' => false, 'required' => true, 'value' => $pricing ['name']]) ?>
		  </div>

	 	  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
		  <div class="form-cell form-control-cell">
		  
				<?= $this->Form->control ('pricing[description]', ['class' => 'form-control', 'label' => false, 'required' => true, 'value' => $pricing ['description']]) ?>

		  </div>

	 </div>
	 
	 <?php
				
				switch ($pricing ['class']) {
						  
					 case 'size':

						  echo '<div class="row top10">' .
								 '<div class="col-sm-6 text-center">' . __ ('Description') . '</div>' .
								 '<div class="col-sm-3 text-center">' . __ ('Price') . '</div>' .
								 '<div class="col-sm-3 text-center">' . __ ('Cost') . '</div>' .
								 '</div>';
						  
						  if (count ($pricing ['sizes']) > 0) {

								$count = 0;
								foreach ($pricing ['sizes'] as $size) {

									 echo '<div class="row top10">' .
											'<div class="col-sm-6 text-left">' .
											'<input type=text name="pricing[sizes][' . $count . '][size]" id="pricing[sizes][' . $count . '][size]" class="form-control" required="required" value="' . $size ['description']  . '">' .
											'</div>' .
											'<div class="col-sm-3 text-left">' .
											'<input type=text name="pricing[sizes][' . $count . '][price]" id="pricing[sizes][' . $count . '][price]" class="form-control currency-format" required="required" value="' . $size ['price']  . '">' .
											'</div>' .
											'<div class="col-sm-3 text-left">' .
											'<input type=text name="pricing[sizes][' . $count . '][cost]" id="pricing[sizes][' . $count . '][price]" class="form-control currency-format" required="required" value="' . $size ['cost']  . '">' .
											'</div>' .
											'</div>' .
	  										'<span id="sizes_' . ($count + 1) . '"></span>';

									 $count ++;
								}
						  }
						  else {
								echo '<span id="sizes_0"></span>';
						  }
				?>
				
				<div class="row top30">
					 
					 <div class="col-sm-4 text-center"></div>
					 <div class="col-sm-4 text-center">
						  <div id="add_size" class="btn btn-primary btn-block top15"><?= __ ('Add Size') ?></div>
					 </div>
					 
				</div>

				<?php 
				break;
				
				case 'group':
				?>
				<div class="row top10">
					 <div class="col-sm-6 text-center"><?=  __ ('Receipt Description') ?></div>
					 <div class="col-sm-3 text-center"><?=  __ ('Price')?></div>
					 <div class="col-sm-3 text-center"><?=  __ ('Cost')?></div>
				</div>
				<div class="row top10">
					 <div class="col-sm-6 text-left">
						  <input type=text name="pricing[group][description]" id="pricing[group][price]" class="form-control" required="required" value="<?= $pricing ['description'] ?>">
					 </div>
					 <div class="col-sm-3 text-left">
						  <input type=text name="pricing[group][price]" id="pricing[group][price]" class="form-control currency-format" required="required" placeholder="<?= __ ('0.00') ?>" value="<?= $pricing ['price'] ?>">
					 </div>
					 <div class="col-sm-3 text-left">
						  <input type=text name="pricing[group][cost]" id="pricing[group][price]" class="form-control currency-format" required="required" placeholder="<?= __ ('0.00') ?>" value="<?= $pricing ['cost'] ?>">
					 </div>
				</div>

				<?php
				
				break;
				
				case 'metric':
				
				?>

				<div class="row top10">
					 
					 <div class="col-sm-4 text-right top10"><?= __('Measure') ?></div>
					 
					 <div class="col-sm-4 text-left top10">
						  
						  <?php echo $this->Form->select ('pricing[metric][type]',
																	 $pricing ['metrics'],
																	 ['class' => 'custom-dropdown', 'label' => false]); ?>
					 </div>
				</div>

				<div class="row top10">
					 
					 <div class="col-sm-4 text-right top10"><?= __('Measure Quantity') ?></div>
					 
					 <div class="col-sm-2 text-left top10">
						  
						  <?= $this->Form->control ('pricing[metric][quantity]', ['class' => 'form-control', 'label' => false, 'required' => true, 'maxlength' => 5]) ?>
					 </div>
					 
				</div>
				
				<?php 
				break;
				}
				?>
		  </div>
	 </div>
</div>

<div class="container">
	 
	 <div class="row top30">
		  
		  <div class="col-sm-4"></div>
		  
		  <div class="col-sm-2 text-center">
				<?php echo $this->Form->submit (__ ('Save'), ['class' => 'btn btn-success btn-block top15']); ?>
		  </div>
		  
		  <div class="col-sm-2 text-center">
				<button class="btn btn-warning btn-block top15" onclick="javascript:window.close ();"><?= __ ('Cancel') ?></button>
		  </div>
		  
		  <div class="col-sm-4"></div>
		  
	 </div>
	 
</div>

<script>

 var count = <?php echo count ($pricing ['sizes']) ?>;
 var currencyPlaceholder = "<?php echo __ ('0.00'); ?>";
 
 $('#add_size').click (function () {

	  console.log ('add size... ' + count);

	  $('#sizes_' + count).append (
			'<div class="row top10">' +
			'<div class="col-sm-4 text-left"><input type=text name="pricing[sizes][' + count + '][size]" id="pricing[sizes][' + count + '][size]" class="form-control" required="required"></div>' +
			'<div class="col-sm-4 text-left"><input type=text name="pricing[sizes][' + count + '][price]" id="pricing[sizes][' + count + '][price]" class="form-control currency-format" required="required" placeholder="' + currencyPlaceholder + '"></div>' +
			'<div class="col-sm-4 text-left"><input type=text name="pricing[sizes][' + count + '][cost]" id="pricing[sizes][' + count + '][cost]" class="form-control currency-format" required="required" placeholder="' + currencyPlaceholder + '"></div>' +
			'</div>' +
	  		'<span id="sizes_' + (count + 1) + '"></span>');

	  count ++;
 });
 
</script>

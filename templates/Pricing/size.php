<?= $this->Html->css ("Pricing/size") ?>

<form id="size_pricing" name ="size_pricing">

	 <input type="hidden" name="class" value="<?= $pricing ['class']?>"/>
	 <input type="hidden" name="pricing_id" value="<?= $pricing ['id']?>"/>
	 
	 
	 <div class="form-section">
		  <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $pricingDesc ?>
	 </div>
	 
	 <div class="size-pricing-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __('Name') ?></div>

		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('pricing[name]',
												  ['class' => 'form-control',
													'label' => false,
													'required' => true,
													'value' => $pricing ['name']]) ?>
		  </div>

	 	  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
		  <div class="form-cell form-control-cell">
				
				<?= $this->Form->control ('pricing[description]',
												  ['class' => 'form-control',
													'label' => false, 'required' => true,
													'value' => $pricing ['description']]) ?>
		  </div>

	 </div>
	 
	 <div class="size-pricing-sizes-grid grid-cell-separator header-grid">
		  
		  <div class="grid-cell grid-cell-left form-desc-cell"><?= __ ('Size Description') ?></div>
		  <div class="grid-cell grid-cell-right form-desc-cell"><?= __ ('Price') ?></div>
		  <div class="grid-cell grid-cell-right form-desc-cell"><?= __ ('Cost') ?></div>
		  <div class="form-cell">&nbsp;</div>
		  
	 </div>
	 
	 <div id="sizes"></div>
	 
</form>

<div class="form-submit-grid">
	 
	 <div class="grid-cell">
		  <button class="btn btn-success btn-block control-button" onclick="javascript:save ()"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div class="grid-cell">
		  <button class="btn btn-warning btn-block control-button" onclick="javascript:del ()"><?= __ ('Cancel') ?></button>
	 </div>
	 
</div>

<script>
 currencyPlaceholder = '<?= __ ('0.00'); ?>';
 pricing = <?= json_encode ($pricing) ?>
</script>

<?= $this->Html->script ("Pricing/size") ?>

<script>
	 $(".currency-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});
</script>

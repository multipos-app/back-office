<style>
 
 .pricing-grid {

     width: 50%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
 	  grid-column-gap: 15px;
 	  grid-row-gap: 25px;
 	  margin-top: 25px;
 }

</style>

<?php

$this->log ($pricingDesc, 'debug');
$this->log ($pricing, 'debug');

?>

<form id="metric_pricing" name ="metric_pricing">

	 <input type="hidden" name="class" value="<?= $pricing ['class']?>"/>
	 <input type="hidden" name="pricing_id" value="<?= $pricing ['id']?>"/>
	 
	 <div class="form-section">
		  <?= $pricingDesc  ?>
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
		  
	 	  <div class="form-cell form-desc-cell"><?= __('Measure') ?></div>
		  <div class="select">
				
				<?php echo $this->Form->select ('pricing[metric]',
														  $metrics,
														  ['id' => 'metric_pricing',
															'class' => 'select',
															'label' => false])
				?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Price') ?></div>
		  <div class="form-cell grid-cell-right form-control-cell">
				<input type=text name="pricing[price]" id="pricing[[price]" class="form-control currency-format" required="required" value="<?= $pricing ['price'] ?>" placeholder="<?= __ ('currency_placeholder') ?>">
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Cost') ?></div>
		  <div class="form-cell grid-cell-right form-control-cell">
				<input type=text name="pricing[cost]" id="pricing[cost]" class="form-control currency-format" required="required" value="<?= $pricing ['cost'] ?>" placeholder="<?= __ ('currency_placeholder') ?>">
		  </div>

	 </div>

</form>

<div class="form-submit-grid">
	 
	 <div class="grid-cell">
		  <button class="btn btn-success btn-block control-button" onclick="javascript:save ()"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div class="grid-cell">
		  <button class="btn btn-warning btn-block control-button" onclick="javascript:cancel ()"><?= __ ('Cancel') ?></button>
	 </div>
	 
</div>

<script>

 function save () {

	  console.log ($('#metric_pricing').serialize ());
 	  $.ajax ({type: "POST",
				  url: "/pricing/metric-pricing-update",
				  data: $('#metric_pricing').serialize (),
				  success: function (data) {

						console.log (data);
						controller ('pricing', false);
						
				  }});
 }
 
 function cancel () {
	  
	  window.close ();
 }
 
 window.onunload = function (e) {
	  
     window.opener.controller (window.opener.pageAction, window.opener.pageAbsolute);
 };

 $(".currency-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});
 
</script>

<div class="row top10">
	 <div class="col-sm-2 text-right"><?= __('Discount Amount') ?></div>
	 <div class="col-sm-1">
		  
		  <?php echo $this->Form->control ('discount_amount', ['value' => $addon ['params'] ['percent'], 'class' => 'form-control', 'label' => false, 'required' => true]); ?>

	 </div>
</div>

<div class="row top10">
	 <div class="col-sm-2 text-right"><?= __('Item Count') ?></div>
	 <div class="col-sm-1">
		  
		  <?php echo $this->Form->control ('item_count', ['value' => $addon ['params'] ['count'], 'class' => 'form-control', 'label' => false, 'required' => true]); ?>
		  
	 </div>
	 <div class="col-sm-5">
		  
		  <?= __ ('Apply discount after n items') ?>
		  
	 </div>
</div>

<div class="row top10">
	 <div class="col-sm-2 text-right"><?= __('Once per sale') ?></div>
	 <div class="col-sm-8">

		  <div class="checkbox checkbox-primary">
				<input type="checkbox" class="styled top5" name="apply_once" id="apply_once" type="checkbox">
		  </div>
		  
	 </div>
	 <div class="col-sm-2"></div>
</div>

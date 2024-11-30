<style>

</style>

<?= $this->Form->create ('message') ?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('POS Message') ?></legend>
	 <div class="container edit">
		  
		  <div class="row top10">
				<div class="col-sm-3"></div>
				<div class="col-sm-2">
					 <?php echo $this->Form->select ('pos_id', $posUnits, ['class' => 'custom-dropdown', 'label' => false]); ?>	
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
					 <textarea rows="5" id="message_body" name="message_body" style="min-width: 100%"></textarea>
				</div>
		  </div>
		  
		  <div class="row top30">
				<div class="col-sm-5"></div>
				<div class="col-sm-2">
					 <?php echo $this->Form->submit (__ ('Send'), ['class' => 'btn btn-success btn-block']); ?>
				</div>

		  </div>
	 </div>
</fieldset>

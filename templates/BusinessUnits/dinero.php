<?php 
echo $this->Form->create ('accounting');
?>

<input type="hidden" name="account_update" value="dinero">

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('Dinero') ?></legend>
	 <div class="container edit">

		  <div class="row top15">
				
				<div class="col-sm-2 text-right top7"><?= __ ('Account Number') ?></div>
				<div class="col-sm-4">
					 <input type="text" name="account_id" value="<?= $accounting ['account_id']?>" class="form-control"/>
				</div>
				
		  </div>
				
		  <div class="row top15">
				
				<div class="col-sm-2 text-right top7"><?= __ ('API Key') ?></div>
				<div class="col-sm-4">
					 <input type="text" name="api_key" value="<?= $accounting ['username'] ?>" class="form-control" title="Log into your Dinero account, select Integrationer, the API Key is below Nøgle"/>
				</div>
				
		  </div>
				
		  <div class="row top15">
				
				<div class="col-sm-2 text-right"></div>
				<div class="col-sm-2">
					 <?= __ ('Account Number') ?>
				</div>
				<div class="col-sm-4">
					 <?= __ ('Description') ?>
				</div>
		  </div>
		  
		  <?php foreach ($accounting ['accounts'] as $key =>  $account) { ?>
				
				<div class="row top15">
					 
					 <div class="col-sm-2 text-right top7"><?= __ ($key) ?></div>
					 <div class="col-sm-2">
						  <input type="text" name="accounts[<?= $key ?>][account_no]" value="<?= $accounting ['accounts'] [$key] ['account_no'] ?>" class="form-control"/>
					 </div>
					 <div class="col-sm-4">
						  <input type="text" name="accounts[<?= $key ?>][desc]" value="<?= $accounting ['accounts'] [$key] ['desc'] ?>" class="form-control"/>
					 </div>
					 
				</div>
				<?php } ?>
		  
		  <div class="row">
				<div class="col-sm-2 text-right"></div>
				<div class="col-sm-4 index-actions text-left top30">
					 <?php echo $this->Form->submit (__ ('Save'), ['class' => 'btn btn-success btn-block']); ?>
				</div>
				<div class="col-sm-4 index-actions text-left top30">
					 <a href="#" class="btn btn-warning btn-block"><?= __ ('Cancel') ?></a>
				</div>
		  </div>

	 </div>
</fieldset>

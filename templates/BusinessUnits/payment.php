<?php

$this->log ($businessUnit, 'debug');

echo $this->Form->create ('BusinessUnit');
echo $this->Form->hidden ('business_type', array ('value' => $businessUnit ['business_type']));
echo $this->Form->hidden ('id', array ('value' => $businessUnit ['id']));
?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('Payment Methods') ?></legend>
	 <div class="container edit">

		  <div class="row seperator-row">
				<div class="col-sm-3">Card Number</div>
				<div class="col-sm-3">Type</div>
				<div class="col-sm-5">Default</div>
				<div class="col-sm-1 text-right">Actions</div>
		  </div>

		  <div class="row">
				<div class="col-sm-4 index-actions text-left top30">
					 <a href="<?= $this->request->getAttribute ('webroot') ?>business-units/add-payment" class="btn btn-primary btn-block"><?= __ ('Add Payment Method') ?></a>
				</div>
		  </div>
	 </div>
</fieldset>

<?php

$this->log ($businessUnit, 'debug');

echo $this->Form->create ('BusinessUnit');
echo $this->Form->hidden ('business_type', array ('value' => $businessUnit ['business_type']));
echo $this->Form->hidden ('id', array ('value' => $businessUnit ['id']));

?>
<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('Edit Business') ?></legend>
	 <div class="container edit">

		  <div class="row topo15">
				
				<div class="col-sm-2 text-right"><?= __ ('Business Name') ?></div>
				<div class="col-sm-4">
					 <input type="text" name="business_name" value="<?php echo $businessUnit ['business_name'];?>" class="form-control"/>
				</div>
				
				<div class="col-sm-2 text-right"><?= __ ('Phone') ?></div>
				<div class="col-sm-4">
					 <input type="text" name="phone_1" value="<?php echo $businessUnit ['phone_1'];?>" class="form-control"/>
				</div>
				
		  </div>

		  
		  <div class="row top7">
				
				<div class="col-sm-2 text-right"><?= __ ('Address') ?></div>
					 <div class="col-sm-4">
						  <input type="text" name="addr_1" value="<?php echo $businessUnit ['addr_1'];?>" class="form-control"/>
					 </div>
					 
					 <div class="col-sm-2 text-right"><?= __ ('Primary Contact') ?></div>
					 <div class="col-sm-4">
						  <input type="text" name="contact" value="<?php echo $businessUnit ['contact'];?>" class="form-control"/>
					 </div>
					 
		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right"><?= __ ('Postal Code') ?></div>
					 <div class="col-sm-4">
						  <input type="text" name="postal_code" value="<?php echo $businessUnit ['postal_code'];?>" class="form-control"/>
					 </div>
					 
					 <div class="col-sm-2 text-right"<?= __ ('>E-Mail') ?></div>
					 <div class="col-sm-4">
						  <input type="text" name="email" value="<?php echo $businessUnit ['email'];?>" class="form-control"/>
					 </div>

		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right"><?= __ ('City') ?></div>
				<div class="col-sm-4">
					 <input type="text" name="city" value="<?php echo $businessUnit ['city'];?>" class="form-control"/>
				</div>
				
		  </div>

		  <div class="row top30">

				<div class="col-sm-2 text-right"></div>
				<div class="col-sm-4 text-right">
					 <?php echo $this->Form->submit (__ ('Save'), ['class' => 'btn btn-success btn-block']); ?>
				</div>
				
				<div class="col-sm-4 text-left">
					 <button class="btn btn-warning btn-block"><?= __ ('Cancel') ?></button>
				</div>
		  </div>
		  
	 </div>
</fieldset>

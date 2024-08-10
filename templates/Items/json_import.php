<?= $this->Form->create () ?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border">JSON Items</legend>
	 <div class="container input-fields">
		  
		  <div class="row">
				<div class="col-sm-2 text-right"></div>
				<div class="col-sm-8">
					 <?= $this->Form->textarea ('json', ['rows' => 20, 'cols' => 80, 'class' => 'form-control', 'label' => false]) ?>
				</div>
				<div class="col-sm-2"></div>
		  </div>
		  
		  <div class="row top15">
				<div class="col-sm-2"></div>
				<div class="col-sm-8 text-center">
					 
					 <?= $this->Form->submit ('Save', ['class' => 'btn btn-success']) ?>
					 
				</div>
				<div class="col-sm-2"></div>
		  </div>
		  
	 </div>
</fieldset>

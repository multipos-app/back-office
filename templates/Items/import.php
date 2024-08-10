<?= $this->Form->create () ?>

<fieldset class="maintenance-border">
	 
	 <legend class="maintenance-border">Import Items</legend>
	 
	 <div class="container">

		  <?= $this->Form->create (null, ['action' => '/upload.php', 'enctype' => 'multipart/form-data', 'type' => 'file']) ?>
		  <div class="row top10">
				<div class="col-sm-6 text-left">
					 <?= $this->Form->input (null, ['name' => 'upload_file', 'type' => 'file', 'enctype' => 'multipart/form-data', 'class' => 'form-control']) ?>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-4 text-left">
					 <?= $this->Form->button (__ ('Submit'), ['class' => 'btn btn-success btn-block top15']) ?>
				</div>
				
		  </div>
		  <?= $this->Form->end () ?>
	 </div>
	 
</fieldset>

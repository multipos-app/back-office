
<div class="row m-3">
	 <h5>
		  <?= __ ('Import') ?>
	 </h5>
</div>

<form id="import_form" enctype="multipart/form-data" accept-charset="utf-8" class="row g-3">
	 
	 <div class="row mt-5">
 		  <div class="col-sm-2">
				<?= $this->Form->select ('import_action',
												 $imports, ['id' => 'import_action',
																'class' => 'form-select',
																'placeholder' => __ ('Import type')]) ?>
		  </div>
	 </div>

	 <div class="row mt-5">
		  <div class="col-sm-2">	  
				<input type="file" id="import_file" name="import_file">
		  </div>
		  
 	 <div class="row mt-5">
		  <div class="col-sm-2 text-left">
				<?=
				$this->Form->select ('department_id',
											$departments,
											['id' => 'department_id',
											 'value' => '',
											 'class' => 'form-select',
											 'label' => false])
				?>
		  </div>
	 </div>
		  
 	 <div class="row mt-5">
		  <div class="col-sm-2 text-left">
				<?=
				$this->Form->select ('tax_group_id',
											$taxGroups,
											['id' => 'tax_group_id',
											 'value' => '',
											 'class' => 'form-select',
											 'label' => false])
				?>
		  </div>
	 </div>
		  
 	 <div class="row mt-5">
		  <div class="col-sm-2 text-left">
				<?=
				$this->Form->select ('supplier_id',
											$suppliers,
											['id' => 'supplier_id',
											 'value' => '',
											 'class' => 'form-select',
											 'label' => false])
				?>
		  </div>
	 </div>
		  
	 <div class="row mt-5">
		  <div class="form-check form-switch ms-3">
				
				<input type="checkbox" class="form-check-input" id="enabled" name="enabled">
				<label class="grid-label" for="enabled"><?= __ ('Enable/disable items') ?></label>
				
		  </div>
	 </div>
	 
<div class="row mt-5">
		  
 		  <div class="col-sm-2 d-grid text-left">
				<button class="btn btn-primary"><?= __ ('Import') ?></button>
		  </div>
		  
	 </div>

</form>

<script>
 
 $('#import_form').submit (function (e) {
	  
	  $.ajax ({url: '/import',
				  type: 'POST',
				  data: new FormData (this),
				  processData: false,
				  contentType: false,
				  success: function (data) {
						
						data = JSON.parse (data);
						if (data.status != 0) {
							 
							 alert (data.status_text);
						}						 
						window.location = '/import';
				  }
	  }); 
	  e.preventDefault ();
 });
</script>

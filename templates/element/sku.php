<?php

if (strlen ($sku) > 0) { ?>
	 
	 <label for="sku" class="col-sm-3 form-label"><?= __ ('SKU') ?></label>
	 <div class="col-sm-9">
		  <?= $this->input ('sku',
								  ['name' => 'sku',
									'value' => $sku,
									'class' => 'form-control',
									'required' => 'required'])
		  ?>
	 </div>
<?php
}
else {
?>
	 
	 <label for="sku" class="col-sm-3 form-label"><?= __ ('SKU') ?></label>
	 <div class="col-sm-4">
		  <?= $this->input ('sku',
								  ['id' => 'sku',
									'name' => 'sku',
									'value' => $sku,
									'class' => 'form-control',
									'required' => 'required'])
		  ?>
	 </div>
	 <div class="col-sm-5">
		  <?=
		  $this->Form->select ("generate_sku",
									  [null => __ ('Create unique 4, 5, 7 or 8 digit SKU'),
										4 => 4,
										5 => 5,
										7 => 7,
										8 => 8],
									  ['id' => 'generate_sku',
										'value' => $item ['sku'],
										'class' => 'form-select',
										'label' => false])
		  ?>
	 </div>
<?php
}
?>

<script>
 
 $('#generate_sku').change (function () {
	  
	  $.ajax ({
			url: "/items/sku/" + $('#generate_sku').val (),
			type: "GET",
			success: function (data) {
				 
				 data = JSON.parse (data);
				 if (data.status == 0) {
					  
					  console.log (`sku ${data.sku}`);
					  
					  $('#sku').val (data.sku);
				 }
			}
	  });
 });

</script>

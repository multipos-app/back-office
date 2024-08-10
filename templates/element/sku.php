<?php

$this->debug ('sku element... ');
$this->debug ($item);

if ($item ['id'] > 0) {

	 echo $this->input ('fa-barcode',
							  ['id' => 'sku',
								'name' =>'item[sku]',
								'value' => $item ['sku'],
								'class' => 'form-control']);
}
else {
?>
	 
	 <div class="auto-sku-grid">

		  <div>
				<?= $this->input ('fa-barcode',
										['id' => 'sku',
										 'name' =>'item[sku]',
										 'value' => $item ['sku'],
										 'class' => 'form-control']) ?>
		  </div>
		  <div onclick="autoSku ()"><i class="far fa-rotate fa-large" style="margin-top: 15px;" alt="Next 5 digit SKU"></i></div>
		  
	 </div>
	 
<?php
}
?>


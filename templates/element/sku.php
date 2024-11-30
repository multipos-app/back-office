<style>

 .gen-sku-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: 1;
     grid-template-columns: 1fr 5fr;
	  grid-column-gap: 0px;
 }
 
</style>

<?php

if ($item ['id'] > 0) {

	 echo $this->input ('fa-barcode',
							  ['id' => 'sku',
								'name' =>'item[sku]',
								'value' => $item ['sku'],
								'class' => 'form-control']);
}
else {
?>
	 
	 <div class="gen-sku-grid">

		  <div class="grid-cell grid-cell-center" onclick="autoSku ()"><i class="far fa-rotate fa-large" alt="Next 5 digit SKU"></i></div>
		  <div class="grid-cell grid-cell-left">
				<?= $this->input ('fa-barcode',
										['id' => 'sku',
										 'name' =>'item[sku]',
										 'value' => $item ['sku'],
										 'class' => 'form-control']) ?>
		  </div>
	 </div>
	 
<?php
}
?>

<script>
 
 function autoSku () {

	  $.ajax ({
			url: "/items/auto-sku/",
			type: "GET",
			success: function (data) {
				 
				 data = JSON.parse (data);
				 
				 console.log (data);

				 if (data.status == 0) {
					  
					  $('#sku').val (data.sku);
				 }
			}
     });
 }
</script>


<?php

/**
 *
 * prompt for add item options
 *
 **/

include ('button_header.php');

$this->debug ('menus item...');

?>

<div class="form-grid">

	 <div id="add_item_container" class="form-grid button-edit-grid">
		  
		  <div class="grid-cell grid-span-all">
				
				<?php
				
				echo $this->Form->select ('add_item',
												  $pricingOptions,
												  ['id' => 'add_item',
													'class' => 'custom-dropdown',
													'label' => false]);
				?>
		  </div>
	 </div>
</div>

<div class="form-submit-grid">
	 
	 <div>
		  <button type="button" class="btn btn-success" onclick="itemUpdate ()"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div>
		  <button type="button" class="btn btn-warning" onclick="buttonClose ()"><?= __ ('Cancel') ?></button>
	 </div>
	 
</div>

<script>

 var existingItem = false;
 
 $('#add_item').change (function () {

 	  console.log ('add item... ' + existingItem);
	  
	  if (!existingItem) {
			
 			if ($('#add_item').val ()) {
				 
				 console.log ('add item...');
 				 console.log (b);
 				 
 				 $.ajax ({
 					  url: "/items/edit/0/" + $('#add_item').val () + '/0',
 					  type: "GET",
 					  success: function (data) {
 							
 							data = JSON.parse (data);
 							
 							console.log (data);
 							$('#add_item_container').html (data.html);	 
 					  }
 				 });
			}
	  }
 });

 function itemUpdate () {

 	  console.log ('item update... ' + existingItem);

	  if (!existingItem) {

			var item = $('#item_edit');
			var data = getFormData (item);
			
			console.log ('create item...');
			console.log (b);
			console.log (data);

			data ['button'] = b;

			/* var form = document.querySelector ('form');
				if (!form.checkValidity()) {

				form.reportValidity ();
				return;
				}
			 */
			
			let url = '/items/item/0';

			$.ajax ({type: "POST",
						url: url,
						data: data,
						success: function (data) {
							 
							 data = JSON.parse (data);
							 
							 console.log ('menu item add...');
							 console.log (data);

							 var button = data.button;
							 posConfig.config.pos_menus [button.container] ['horizontal_menus'] [button.menu] ['buttons'] [button.pos] = button;
							 render (button.container);
						},
						fail: function () {

							 console.log ('fail...');
						},
						always: function () {

							 console.log ('always...');
						}
				 
			});
	  }
 }

 function getFormData ($form){
	  
     var unindexed_array = $form.serializeArray ();
     var indexed_array = {};

     $.map (unindexed_array, function (n, i) {
			
			indexed_array[n['name']] = n['value'];
     });

     return indexed_array;
 }

</script>

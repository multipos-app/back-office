
<div class="form-cell form-desc-cell"><?= __ ('SKU') ?>/<?= __ ('Description') ?></div>
<?php

echo $this->input ('fa-barcode',
						 ['id' =>'sku',
						  'name' => 'button[sku]',
						  'value' => '',
						  'class' =>'form-control',
						  'placeholder' => __ ('Enter Item Description or SKU')]);
?>

<script>
 
 $('#sku').typeahead ({
	  
     source: function (query, result) {
			
			$.ajax ({
             url: "/search/items/sku_and_desc/" + query,
             type: "GET",
             success: function (data) {

					  data = JSON.parse (data);
					  
					  result ($.map (data, function (item) {
							
							return item
                 }));
             }
			});
     },
	  updater: function (item) {		  
			
 			$('#button_desc').val (item.item.item_desc);
 			$('#button_text').html ($('#button_desc').val ().toUpperCase ());
 			$('#' + container + '_' + menu + '_' + pos).html ($('#button_desc').val ().toUpperCase ());	  

  			posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].class = 'DefaultItem';
  			posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].text = item.item.item_desc.toUpperCase ();
 			posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {sku: item.item.sku};
			existingItem = true;
	  }
 });

</script>

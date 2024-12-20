
<div class="form-cell form-desc-cell"><?= __ ('SKU') ?>/<?= __ ('Description') ?></div>
<?php

echo $this->input ('fa-barcode',
						 ['id' =>'sku',
						  'name' => 'button[sku]',
						  'value' => '',
						  'class' =>'form-control',
						  'placeholder' => __ ('Enter Item Description or SKU')]);
?>

<span id="item_params"></span>

<script>
 
 $('#sku').typeahead ({
	  
     source: function (query, result) {
			
			$.ajax ({
             url: "/search/items/sku_and_desc/" + query,
             type: "GET",
             success: function (data) {

					  data = JSON.parse (data);
					  
					  result ($.map (data, function (item) {

							console.log (item);
							return item
                 }));
             }
			});
     },
	  updater: function (item) {		  

			console.log (item);
			
 			$('#text').val (item.item.item_desc);
 			$('#' + container + '_' + menu + '_' + pos).html (item.item.item_desc);	  
 			$('#item_params').html ('<input type="hidden" name=params[sku] value="' + item.item.sku + '">');	  

  			/* 
				posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].class = 'DefaultItem';
  				posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].text = item.item.item_desc.toUpperCase ();
 				posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {sku: item.item.sku};
			 */

			existingItem = true;
	  }
 });

</script>


<?php include ('button_header.php'); ?>

<div class="form-grid button-edit-grid">

	 <div class="form-cell form-desc-cell"><?= __ ('SKU') ?>/<?= __ ('Description') ?></div>

	 <?php

	 $this->debug ($button);
	 
	 $desc = '';
	 if (isset ($button ['params'] ['sku']) && (strlen ($button ['params'] ['sku']) > 0)) {

		  $desc = $button ['params'] ['sku'] . '/' . $button ['text'];
	 }
	 
	 echo $this->input ('fa-barcode',
							  ['id' =>'sku',
								'name' => 'button[sku]',
								'value' => $desc,
								'class' =>'form-control',
								'placeholder' => __ ('SKU')]);
	 ?>
	 
</div>

<?php include ('button_footer.php'); ?>

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

  		  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].class = 'Item';
  		  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].text = item.item.item_desc.toUpperCase ();
 		  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {sku: item.item.sku};
	 }
 });
 
</script>

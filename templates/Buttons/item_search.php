
<?php

$this->debug ('buttons item search...');

?>

<div class="row g-3" style="height:400px"> <!-- make room for the select list -->
	 
	 <div id="search" class="col-sm-12">
				
		  <input type="text" class="form-control" name="item_search" id="item_search" value="" placeholder="<?= __ ('Search, SKU, description')?>">
		  
	 </div>
</div>

<script>
 
 $('#item_search').typeahead ({
	  
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

			console.log (`existing item... ${configID} ${menuName} ${menuIndex} ${pos}`);
			console.log (item);
			console.log (curr.buttons [pos]);

			curr.buttons [pos] = {class: "Item", color: "#555555", text: item.name};
			menus.render (curr.buttons);

			data = {config_id: <?= $configID ?>,
					  menu_name: '<?= $menuName ?>',
					  menu_index: <?= $menuIndex ?>,
					  pos: <?= $pos ?>,
					  button: {class: "Item", color: "#555555", text: item.name, params: {sku: item.item.sku}}};
			
			console.log ('add_item... /buttons/item-add/');
			console.log (data);
			
			$.ajax ({url: '/buttons/item-add/',
						type: 'POST',
						data: data,
						success: function (data) {
							 
							
							 data = JSON.parse (data);
							 $('#modal_content').html (data.html);
							 menus.button (pos);
						}
			});
	  }
 });

</script>

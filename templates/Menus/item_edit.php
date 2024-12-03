<style>

 .item-display-grid {

     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
 }

</style>

<?php

/**
 *
 * display/edit an existing item attached to a button
 *
 **/

include ('button_header.php');

?>

<div id="edit_item_container" class="form-grid item-display-grid">
	 
	 <div class="form-cell form-desc-cell"><?= __('SKU') ?></div>
	 <div class="form-cell form-display-cell" id='item_desc'><?= $button ['params'] ['sku'] ?></div>

	 <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
	 <div class="form-cell form-display-cell" id='item_desc'><?= $button ['text'] ?></div>
</div>

<div id="edit_item_controls" class="form-submit-grid grid-span-all">
	 
	 <div>
		  <button type="button" class="btn btn-success" onclick="itemDisplay ()"><?= __ ('Edit Item') ?></button>
	 </div>
	 
	 <div>
		  <button type="button" class="btn btn-warning" onclick="buttonClear ()"><?= __ ('Clear Button') ?></button>
	 </div>
	 
</div>

<script>

 function itemDisplay () {
	  
	  $.ajax ({
 			url: "/items/menu-item/" + <?= $button ['params'] ['sku'] ?>,
 			type: "GET",
 			success: function (data) {
 				 
 				 data = JSON.parse (data);
				 if (data.status == 0) {
					  
 					  $('#edit_item_container').html (data.html);

					  let controls = '<div>' +
										  '<button type="button" class="btn btn-success" onclick="itemSave ()"><?= __ ('Save') ?></button>' +
										  '</div>' +
										  '<div>' +
										  '<button type="button" class="btn btn-warning" onclick="buttonClose ()"><?= __ ('Cancel') ?></button>' +
										  '</div>';
					  
					  $('#edit_item_controls').html (controls);
				 }
 			}
	  });
 }
 
 function itemSave () {

	  var form = document.querySelector ('form');
	  if (!form.checkValidity()) {

			form.reportValidity ();
			return;
	  }
	  
	  let url = '/items/item/' + item.id;

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#item_edit').serialize (),
				  success: function (data) {

						buttonClose ();
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 }

</script>


<?php

$id = $item ['id'];
$template = 'standard_pricing';
if (isset ($item ['item_template'])) {

	 $template = $item ['item_template'];
}

$url = "/items/edit/$id/$template";

?>

<input type="hidden" name="class" value="Item">

<div id="edit_item_content"></div>

<script>
 
 $.ajax ({
 	  url: '<?= $url ?>',
 	  type: "GET",
 	  success: function (data) {
 			
 			data = JSON.parse (data);
			if (data.status == 0) {
				 
 				 $('#edit_item_content').html (data.html);
			}
 	  }
 });
 
</script>

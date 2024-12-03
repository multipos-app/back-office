<?php include ('button_header.php'); ?>

<div class="form-cell">
	 <div id="menu_select" class="select grid-span-all"></div>
</div>


<?php include ('button_footer.php'); ?>

<script>

 html = 
 	  '<select id="menus" class="custom_dropdown" onchange="menuSelect ()">' +
	  '<option disabled>Select menu</option>';

 for (i=0; i < posConfig.config.pos_menus [b.container].horizontal_menus.length; i ++) {

	  m = posConfig.config.pos_menus [b.container].horizontal_menus [i];
	  html += '<option value=' + i + '>' + m ['name'] + '</option>';
 }
 
 html += '</select>';
	 
 $('#menu_select').html (html);

 function menuSelect () {

  	  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].class = 'Navigate';
  	  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].text = $('#button_desc').val ().toUpperCase ();
 	  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {menu_index: $('#menus').val ()};
 }
 
</script>

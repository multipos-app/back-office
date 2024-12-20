<?php include ('button_header.php'); ?>

<input type="hidden" name="class" value="CashTender">
<input type="hidden" name="params[tender_id]" value="1">

<div class="form-control">
	 
	 <select id="cash_options" name="params[value]" class="custom-dropdown">
		  <option disabled selected>Select tender amount</option> 
		  <option value=-1>Amount of sale</option>
		  <option value=0>Round up</option>
		  <option value=500>$5</option>
		  <option value=1000>$10</option>
		  <option value=2000>$20</option>
		  <option value=5000>$50</option>
	 </select>
</div>

<?php include ('button_footer.php'); ?>

<script>

  $('#cash_options').change (function () {

	  let value = parseInt ($('#cash_options').val ());
	  let text = $("#cash_options option:selected").text ();
	  
	  $('#text').val (text);
	  $(buttonID).html (text);
	  
	  /* switch (value) {

		  case -1:

		  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {tender_id: 1};
		  break;
		  
		  default:
		  
 		  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {tender_id: 1, value: value};
		  break;
		  }*/
	  
 	  console.log ('cash tender cash...');
	  console.log (posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos]);
});

</script>

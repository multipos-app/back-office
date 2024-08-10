<?php include ('button_header.php'); ?>

<div class="form-control">
	 
	 <select id="cash_options" class="custom-dropdown" onchange="cash ()">
		  <option disabled selected>Select tender amount</option> 
		  <option value=0>Round up</option>
		  <option value=-1>Amount of sale</option>
		  <option value=500>$5</option>
		  <option value=1000>$10</option>
		  <option value=2000>$20</option>
		  <option value=5000>$50</option>
	 </select>
</div>

<?php include ('button_footer.php'); ?>

<script>

 function cash () {
	  
 	  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos].params = {value: $('#cash_options').val ()};
 }

</script>

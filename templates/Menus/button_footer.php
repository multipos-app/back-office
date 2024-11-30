<?php

$this->debug ('menu button footer...');

?>

<div class="form-submit-grid">
	 
	 <div>
		  <button type="button" class="btn btn-success" onclick="buttonClose ()"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div>
		  <button type="button" class="btn btn-warning" onclick="buttonClear ()"><?= __ ('Clear Button') ?></button>
	 </div>
	 
</div>

<script>

 /**
  *
  * clear a button
  *
  */
 
 function buttonClear () {
	  
	  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu] ['buttons'] [pos] = {"text": "", "class": "Null", "color": "#999"};
	  render (container);
	  dirty (true);
 }
 
</script>

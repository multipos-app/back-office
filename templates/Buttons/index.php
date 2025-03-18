
<style>
 
 .button {

	  height: 100px;
	  width: 120px;
	  font-size: 1.0em;
	  resize: none;
	  background-color: #679ACD;
 }
 
 .color-grid {

	  display: grid !important;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: repeat(8, 1fr);;
	  grid-row-gap: 3px;
	  grid-column-gap: 3px;
	  padding: 3px;
 }

 textarea {

	  font-size: 120%;
 }
 
</style>

<?php
$color = 'color: white; background-color:' . $button ['color'] . ';';
?>

<script>
 colors = <?= json_encode ($colors) ?>
</script>


<input type="hidden" name="config_id" value="<?= $configID?>">
<input type="hidden" name="menu_name" value="<?= $menuName?>">
<input type="hidden" name="menu_index" value="<?= $menuIndex?>">
<input type="hidden" name="pos" value="<?= $pos ?>">
<input type="hidden" id="color" name="color" value="<?= $color ?>">

<div class="row mt-3">
	 
	 <div class="col-sm-3">
		  <textarea id="text" name="text" class="button menu-button"><?= $button ['text'] ?></textarea>
	 </div>

	 <div class="col-sm-1">

		  <input type="color"
					class="form-control form-control-color"
					id="color_input"
					name="color_input"
					value="<?= $button ['color']?>"
					title="Choose your color"
					onchange="colorChange ()">

	 </div>
	 
	 <div class="col-sm-8">
		  
		  <div class="color-grid">
				
				<?php
				for ($i = 0; $i < 16; $i ++) {
				?>
					 
					 <div id="color_<?= $i ?>" class="form-control form-control-color" style="background: <?= $colors [$i] ?>;" onclick="selectColor (<?= $i ?>)"></div>
					 
				<?php
				} ?>
		  </div>
	 </div>
</div>

<?php

// button detail fields

include ("$detail.php");

?>

<script>
 
 function colorChange () {
	  
	  $('#text').css ({'background-color': $('#color_input').val ()});
	  $(`#${pos}`).css ({'background-color': $('#color_input').val ()});
	  curr.buttons [pos].color = $('#color_input').val ();
	  $('#color').val ($('#color_input').val ());
	  menus.modified ();
 }
 
 function selectColor (i) {
	  
	  $('#text').css ({'background-color': colors [i]});
	  $(`#${pos}`).css ({'background-color': colors [i]});
	  curr.buttons [pos].color = colors [i];
	  $('#color').val (colors [i]);
	  menus.modified ();
 }
 
 $('#text').change (function () {

	  let text = $(this).val ().toUpperCase ();
	  curr.buttons [pos].text = text;
	  $(`#${pos}`).html (text);
	  menus.modified ();
 });

</script>


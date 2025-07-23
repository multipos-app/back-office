
<?php
$color = 'color: white; background-color:' . $button ['color'] . ';';
?>

<script>
 
 pos = <?= $pos ?>;
 colors = <?= json_encode ($colors) ?>;
 buttonClass = curr.buttons [pos].class;
 isLocal = buttonClass == '<?= $button ['class'] ?>';
 
</script>

<div class="row mt-3">
	 
	 <div class="col-sm-4">
		  <textarea id="text" name="text" class="button menu-button"><?= $button ['text'] ?></textarea>
	 </div>

	 <!-- <div class="col-sm-1">

			<input type="color"
			class="form-control form-control-color"
			id="color_input"
			name="color_input"
			value="<?= $button ['color']?>"
			title="Choose your color"
			onchange="colorChange ()">

			</div> -->
	 
	 <div class="col-sm-8">
		  
		  <div class="color-grid">
				
				<?php
				for ($i = 0; $i < count ($colors); $i ++) {
				?>
					 <div id="color_<?= $i ?>" class="form-control form-control-color" style="background: <?= $colors [$i] ?>;" onclick="selectColor (<?= $i ?>)"></div>
				<?php
				}
				?>
		  </div>
	 </div>
</div>

<?php

// button detail fields

include ("$detail.php");

?>

<script>

 // initialize common fields
 
 $('#text').css ({'background-color': curr.buttons [pos].color});
 $('#text').val (curr.buttons [pos].text);
 
 function colorChange () {
	  
	  curr.buttons [pos].color = $('#color_input').val ();

	  $('#text').css ({'background-color': $('#color_input').val ()});
	  $(`#${pos}`).css ({'background-color': $('#color_input').val ()});
	  curr.buttons [pos].color = $('#color_input').val ();
	  $('#color').val ($('#color_input').val ());
	  menus.modified ();
 }
 
 function selectColor (i) {
	  
	  curr.buttons [pos].color = colors [i];
	  
	  $('#text').css ({'background-color': colors [i]});
	  $(`#${pos}`).css ({'background-color': colors [i]});
	  curr.buttons [pos].color = colors [i];
	  $('#color').val (colors [i]);
	  menus.modified ();
 }
 
 $('#text').bind ('input propertychange', function() {

	  let text = $(this).val ().toUpperCase ();
	  
	  curr.buttons [pos].text = text;
	  $(`#b_${pos}`).html (text);
	  $(this).val (text);
	  menus.modified ();

	  console.log (curr.buttons [pos]);
 });

</script>


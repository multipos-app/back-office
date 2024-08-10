<style>

 .cd-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 3fr 1fr 1fr 1fr 1fr 1fr;
     grid-column-gap: 0px;
	  margin-bottom: 10px;
 }

 .rf-justify-left {
	  text-align: left;
 }

 .rf-justify-right {
	  text-align: right;
 }

 .rf-justify-center {
	  text-align: center;
 }

 .rf-font-normal {
	  font-weight: normal;
 }

 .rf-font-bold {
	  font-weight: bold;
 }

 .rf-font-italic {
	  font-style: italic;
 }

 .rf-size-big {
	  font-size: 180%;
 }

 .rf-size-normal {
	  font-size: 120%;
 }

 .rf-size-small {
	  font-size: 100%;
 }

 .grid-cell-gap {
	  padding-left: 10px;
	  padding-right: 10px;
 }
 
</style>

<script>
 
 let cd = <?php echo json_encode ($cd); ?>;
 
 console.log (cd);
 
</script>

<?= $this->Form->create (null, ['id' => 'cd', 'name' => 'cd']) ?>

<?php

$just = ['left' => 'Left',
			'center' => 'Center',
			'right' => 'Right'];

$font = ['normal' => 'Normal',
			'bold' => 'Bold',
			'italic' => 'Italic',
			'banner' => 'Banner'];

$size = ['normal' => 'Normal',
			'big' => 'Big',
			'small' => 'Small'];

$feed = ['0' => '0',
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5'];


foreach ($cd as $section => $rows) {

?>
	 <fieldset class="maintenance-border">

		  <legend class="maintenance-border"><?php echo __ ('POS Cd'); echo ' ' . $section; ?></legend>
		  
		  <div class="container">
				
				<div class="row top15">

					 <div class="col-sm-3">

						  <select name="timeout"  class="custom-dropdown">
								<option selected disabled><?= __ ('Idle timeout') ?></option>
								<option value="0"><?= __ ('Never') ?></option>
								<option value="1"><?= __ ('One minute') ?></option>
								<option value="2"><?= __ ('Two minutes') ?></option>
								<option value="5"><?= __ ('Five minutes') ?></option>
						  </select>
						  
					 </div>
				</div>
				
				<div class="cd-grid top30">
					 
					 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Text')?></div>
					 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Justify')?></div>
					 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Style')?></div>
					 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Size')?></div>
					 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Feed Lines')?></div>
					 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('')?></div>
					 
				</div>

				
				<span id="<?php echo $section ?>_lines">
					 <span id="add_<?php echo $section ?>_0"></span>
				</span>
				
				<div class="row top15">

					 <div class="col-sm-5"></div>
					 <div class="col-sm-2">
						  <a href=# class="btn btn-primary btn-block text-center" onclick="javascript:addRow ('<?php echo $section ?>', {text:'', justify: 'left', font:'normal', size: 'normal', feed: '0'})"><?= __ ('Add Row') ?></a>
					 </div>

				</div>
		  </div>
	 </fieldset>

<?php
}
?>

<div class="container">

	 <div class="row top20">
		  <div class="col-sm-3"></div>
		  <div class="col-sm-3 text-center">
				<?php echo $this->Form->submit ('Save', ['class' => 'btn btn-block btn-success']); ?>
		  </div>
		  <div class="col-sm-3 text-center">
				<a href="#" class="btn btn-block btn-warning"><?= ('Cancel') ?></a>
		  </div>
		  <div class="col-sm-3"></div>
	 </div>

</div>

<script>


 function draw (section, row, i) {

	  let justifySelect = row.justify == 'center' ? ' selected' : '';

	  console.log (row);

	  let id =  section + "_" + i;
	  let html =
			
			'<div class="cd-grid">' +

			'<div class="grid-cell grid-cell-center grid-cell-gap">' +
			'<input type="text" id="' + id + '" name="' + section + "[" + i + '][text]" id=' + section + '_text_' + i + '" class="form-control" required="required" value="' + row.text + '"/>' +
			'</div>' +
			
			'<div class="grid-cell grid-cell-center grid-cell-gap">' +
			'<select name="' + section + '[' + i + '][justify]" id="' + section + '_justify_' + i + '" class="custom-dropdown" required="required" onchange="javascript:setClass (\'' + section + '\', ' + i + ', \'justify\')">' +
			'<option value="left">Left</option>' +
			'<option value="center">Center</option>' +
			'<option value="right">Right</option>' +
			'</select>' +
			'</div>' +
			
			'<div class="grid-cell grid-cell-center grid-cell-gap">' +
			'<select name="' + section + '[' + i + '][font]" id="' + section + '_font_' + i + '" class="custom-dropdown" required="required" onchange="javascript:setClass (\'' + section + '\', ' + i + ', \'font\')">' +
			'<option value="normal">Normal</option>' + 
			'<option value="bold">Bold</option>' + 
			'<option value="italic">Italic</option>' + 
			'</select>' +
			'</div>' +
			
			'<div class="grid-cell grid-cell-center grid-cell-gap">' +
			'<select name="' + section + '[' + i + '][size]" id="' + section + '_size_' + i + '" class="custom-dropdown" required="required" onchange="javascript:setClass (\'' + section + '\', ' + i + ', \'size\')">' +
			'<option value="normal">Normal</option>' + 
			'<option value="big">Big</option>' + 
			'<option value="small">Small</option>' + 
			'</select>' +
			'</div>' +
			
			'<div class="grid-cell grid-cell-center grid-cell-gap">' +
			'<select name="' + section + '[' + i + '][feed]" id="' + section + '_feed_' + i + '" class="custom-dropdown" required="required" onclick="javascript:setClass (\'' + section + '\',' + i + ', \'feed\')">' +
			'<option value="0">0</option>' +
			'<option value="1">1</option>' +
			'<option value="2">2</option>' +
			'<option value="3">3</option>' +
			'<option value="4">4</option>' +
			'<option value="5">5</option>' +
			'</select>' +
			'</div>' +
			
			'<div class="grid-cell grid-cell-center grid-cell-gap">' +
			'<i class="fa fa-trash fa-large action-buttons" onclick="dropRow (\'' + section + '\',' + + i + ')"></i>' +
			'</div>' +
			
			'</div>' +
			
			'<span id="add_' + section + '_' + (i + 1) + '"></span>';

	  $('#add_' + section + '_' + i).html (html);

	  $.each (['justify', 'font', 'size'], function (index, sel) {
			
			if (row [sel].length > 0) {

				 let el = '#' + section + '_' + sel + '_' + i;
				 let value = row [sel];
				 				 
				 $(el).val (value).prop ('selected', true);
				 
				 $('#' + section + '_' + i).addClass ('rf-' + sel + '-' + value);
			}
	  });

 }
 
 function refresh () {
	  
	  $.each (cd, function (section, rows) {
			
			$.each (rows, function (i, row) {
				 
				 draw (section, row, i);
			});
	  });
 }
 

 function setClass (section, row, attr) {

	  let id = '#' + section + '_' + row;
	  let control = '#' + section + '_' + attr + '_' + row;
	  let text = section + '_' + row;
	  let selected = $(control + ' :selected').val ();
	  let cl = 'rf-' + attr + '-' + selected;
	  
     var classes = $('#' + text).attr ('class').split (/\s+/);
	  
     $.each (classes, function (index, c) {
			
         if (c.startsWith ("rf-" + attr)) {
				 
             $('#' + text).removeClass (c);
         }
     });
	  
     $('#' + text).addClass (cl);
	  
 }

 function addRow (section, row) {

	  cd [section].push (row);
	  refresh ();
 }

 function dropRow (section, row) {
	  
	  console.log ('drop row... ' + section + ' ' + row);

	  cd [section].splice (row, 1);
	  
	  console.log (cd [section]);
	  
	  $('#' + section + '_lines').html ('<span id="add_' + section + '_0"></span>');
	  refresh ();
 }

 refresh ();

</script>

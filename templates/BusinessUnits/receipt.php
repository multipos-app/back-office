<style>

 .receipt-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 3fr 1fr 1fr 1fr 1fr .5fr;
  	  grid-column-gap: 3px;
	  grid-row-gap: 25px;
 }

 .rf-cell {
	  border-bottom: 1px solid #777;
 }
 
 .rf-cell:hover {
	  border-bottom: 3px solid green !important;
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
	  font-weight: 500;
 }

 .rf-font-bold {
	  font-weight: 900;
 }
 
 .rf-font-bold-italic {
	  font-weight: 900;
	  font-style: italic;
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

 .grid-cell-separator {

	  font-size: 120%;
 }

 .custom-dropdown {

	  height: 40px !important;
 }
 
 .form-control {
	  
	  background: #fff !important;
	  height: 40px !important;
 }

 #action_form {
	  
	  right: -65%;
	  width: 65%;
 }
 
 .grid-span-6 {
	  grid-column: 1/6;
 }
 
</style>

<?php 
$this->debug ($receipt);
?>

<script>

 receipt = <?= json_encode ($receipt) ?>;

</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i>
</div>

<?php

$this->debug ('receipt...');
$this->debug ($receipt);

foreach ($receipt as $section => $rows) {

?>
	 <form id="receipt" name ="receipt">

		  <input type="hidden" name="business_unit_id" value="<?= $buID?>">
		  <div class="receipt-grid">
				
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Text')?></div>
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Justify')?></div>
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Size')?></div>
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Style')?></div>
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Feed Lines')?></div>
				<div class="grid-cell grid-cell-center"></div>
				
		  </div>

		  <div id="<?= $section ?>_lines"></div>

	 </form>
<?php
}
?>
<div class="form-submit-grid">
	 
	 <div>
		  <button type="submit" id="receipt_update" class="btn btn-success" onclick="save ()"><?= __ ('Save') ?></button>
	 </div>
	 
</div>

<script>
 
 function save () {
	  
 	  $.ajax ({type: "POST",
				  url: "/business-units/update-receipt/" + <?= $buID ?>,
				  data: receipt,
				  success: function (data) {
						
						closeForm ();
				  }});
 }
  
 function render () {

	  $.each (receipt, function (section, rows) {
			
			var lines = '';
			var count = 0;
			$.each (rows, function (i, row) {
				 
				 lines += line (section, row, i);
				 count ++;
			});

			count ++;
			lines += add (section, count);
			$('#' + section + '_lines').html (lines);
			
	  });
 }
 
 function line (section, row, i) {
	  
	  let id =  section + "_" + i;
	  let html = add (section, i) +
					 '<div class="receipt-grid">' +
					 text (section, row, i) +
					 justify (section, row, i) +
					 size (section, row, i) +
					 font (section, row, i) +
					 feed (section, row, i) +
					 minus (section, i) +
					 '</div>';
	  
	  return html;
 }

 function text (section, row, i) {

	  css = ` rf-justify-${row.justify} rf-font-${row.font} rf-size-${row.size}`;

	  return '<div class="form-cell form-control-cell">' +
				`<input type="text" name="${section}[${i}][text])" id="${section}_text_${i}" ` +
				`class="form-control${css}" required="required" value="${row.text}" placeholder="add text" ` +
				`onkeyup=" setText('${section}', ${i})" ` +
				'onclick="this.select ()"/>' +
				'</div>';
	  
 }
 
 function justify (section, row, i) {

	  id = `#${section}_text_${i}`;
	  
	  select = '<div class="form-cell form-control-cell">' +
				  `<select name="${section}[${i}][justify]" id="${section}_justify_${i}" class="custom-dropdown" required="required" onchange="setClass (\'${section}\', ${i}, \'justify\')">`;

	  $.each (['left', 'center', 'right'], function (index, val) {

			desc = val.charAt (0).toUpperCase () + val.slice (1);
			
			selected = row.justify == val ? ' selected' : '';
			select += `<option value="${val}"${selected}>${desc}</option>`;

	  });

	  select += '</select>' +
					'</div>';
	  return select;
 }
 
 function size (section, row, i) {

	  select = '<div class="grid-cell grid-cell-center">' +
				  `<select name="${section}[${i}][size]" id="${section}_size_${i}" class="custom-dropdown" required="required" onchange="javascript:setClass (\'${section}\', ${i}, \'size\')">`;

	  $.each (['normal', 'big', 'small'], function (index, val) {

			desc = val.charAt (0).toUpperCase () + val.slice (1);
			selected = row.justify == val ? ' selected' : '';
			select += `<option value="${val}"${selected}>${desc}</option>`;

	  });
	  
	  select += '</select>' +
					'</div>';

	  return select;
 }


 function font (section, row, i) {
	  
	  select = '<div class="grid-cell grid-cell-center">' +
				  `<select name="${section}[${i}][font]" id="${section}_font_${i}" class="custom-dropdown" required="required" onchange="javascript:setClass (\'${section}\', ${i}, \'font\')">`;
	  
	  $.each (['normal', 'bold', 'italic', 'bold-italic'], function (index, val) {
			
			desc = val.charAt (0).toUpperCase () + val.slice (1);
			desc = desc.replace ('-', ' ');

			selected = row.font == val ? ' selected' : '';
			select += `<option value="${val}"${selected}>${desc}</option>`;
			
	  });
	  
	  select += '</select>' +
					'</div>';

	  return select;
 }
 
 function feed (section, row, i) {

	  return '<div class="grid-cell grid-cell-center">' +
				`<select name="${section}[${i}][feed]" id="${section}_feed_${i}" class="custom-dropdown" required="required" onchange="javascript:setClass (\'${section}\', ${i}, \'feed\')">` +
				'<option value="1">1</option>' +
				'<option value="2">2</option>' +
				'<option value="3">3</option>' +
				'<option value="4">4</option>' +
				'<option value="5">5</option>' +
				'</select>' +
				'</div>';
 }
 
 function add (section, i) {

	  return '<div class="receipt-grid">' +
				'<div class="grid-span-6"></div>' +
				'<div class="grid-cell grid-cell-center" onclick="addRow (\'' + section + '\', ' + i + ')"><i class="fa fa-plus fa-large action_icons"></i></div>' +
				'</div>';

 }
 
 function minus (section, i) {

	  return '<div class="grid-cell grid-cell-center" onclick="dropRow (\'' + section + '\', ' + i + ')"><i class="fa fa-minus fa-large action_icons"></i></div>';

 }

 function setText (section, row) {
	  
	  receipt [section] [row] ['text'] = $('#' + section + '_text_' + row).val ();
 }
 
 function setClass (section, row, attr) {

	  id = `#${section}_${attr}_${row}`;

	  receipt [section] [row] [attr] = $(id).val ();
	  render ();
 }

 function addRow (section, row) {

	  receipt [section].splice (row, 0, {text: 'add text here', justify: 'center', font: 'normal', size: 'normal', feed: '0'});
	  render ();
 }

 function dropRow (section, row) {

	  receipt [section].splice (row, 1);
	  render ();
 }

 render ();

</script>

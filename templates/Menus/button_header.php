<?php 

$colors = ['#7B0100',
			  '#CA3701',
			  '#767700',
			  '#009100',
			  '#045AB9',
			  '#5800B7',
			  '#CE006C',
			  '#616161',
			  '#520100',
			  '#8F2703',
			  '#646500',
			  '#007002',
			  '#004A93',
			  '#42008A',
			  '#B2005B',
			  '#4F4F4F',
			  '#2A0001',
			  '#4A1400',
			  '#4E4F01',
			  '#005301',
			  '#003466',
			  '#300061',
			  '#850143',
			  '#313131',
			  '#190101',
			  '#2C0C01',
			  '#272C02',
			  '#003000',
			  '#001D3B',
			  '#140135',
			  '#50012A',
			  '#000000'];
?>

<style>

 .button-edit-grid {

	  display: grid;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
	  grid-row-gap: 10px;
 }
 
 .button-view {
	  
	  height: 120px !important;
	  width: 130px;
	  margin-top: 10px;
	  margin-bottom: 10px;
	  background: #999;
	  font-size: 100%;
	  font-weight: 500;
	  border-radius: 3px;
	  color: white;
	  padding: 5px;
 	  box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.1);

 }

 .button-desc {
	  
	  font-size: 120%;
	  margin-top: 10px;
	  padding: 10px;
 }
 
 .color-grid {

	  display: grid;
	  width: 100%;
	  height: 100%;
     grid-template-rows: auto;
     grid-template-columns: repeat(8, 1fr);
	  grid-row-gap: 2px;
	  grid-column-gap: 2px;
 }
 
 .btn {
	  
	  height: 100% !important;
	  width: 100% !important;
	  border-radius: 0px !important;
 }
 
 .button-edit-color {

	  width: 100%;
	  height: 100%;
 }
 
</style>

<script>
 
 b = <?php echo json_encode ($button); ?>;

 console.log (b);
 
 $('#' + b ['menu'] + '_' + b ['pos']).removeClass ('empty-button');
 posConfig.config.pos_menus [b.container].horizontal_menus [b.menu].buttons [b.pos] = b;
 
</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="buttonClose ()"></i>
</div>

<div class="form-grid button-edit-grid">
	 
	 <div class="form-cell button" id="button_text" style="background-color: <?= $button ['color'] ?>">
		  <?= $button ['text'] ?>
	 </div>
		  
	 <div class="grid-cell button-desc">
		  <?php if (isset ($button ['desc'])) echo $button ['desc']; ?>
	 </div>

	 <div class="form-cell form-desc-cell"><?= __('Button text') ?></div>
	 <?php
	 echo $this->input ('fa-text-size',
							  ['id' =>'button_desc',
								'name' => 'button[button_desc]',
								'value' => $button ['text'],
								'class' =>'form-control button_control',
								'placeholder' => __ ('Button text')]);
	 ?>
	 
	 <div class="grid-cell grid-span-all">
		  
		  <div class="color-grid">

				<?php foreach ($colors as $color) { ?>

					 <div class="grid-cell">
						  
						  <button type="button" class="btn btn-block" style="background: <?= $color ?>;" onclick="color ('<?= $color ?>')"></button>
						  
					 </div>
					 
				<?php } ?>
				
		  </div>
	 </div>
</div>

<script>
 
 function buttonClose () {
	  
	  $('#button_container').toggleClass ('on');
 }

 $('#button_desc').on ('keyup', function (e) {

	  let text = $('#button_desc').val ().toUpperCase ();
	  $('#button_text').html (text);
	  $('#' + container + '_' + menu + '_' + pos).html (text);	  
 	  posConfig.config.pos_menus [container].horizontal_menus [menu].buttons [pos].text = text;
 });
 
 function color (color) {
	  
     $('#button_text').css ({'background-color': '"' + color + '"'});
	  $('#' + container + '_' + menu + '_' + pos).css ({'background-color': '"' + color + '"'});	  
	  posConfig.config.pos_menus [container].horizontal_menus [menu].buttons [pos].color = color;
 }
 
</script>

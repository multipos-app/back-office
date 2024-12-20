<?php

/**
 * Copyright (C) 2023 multiPos, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
			  '#21512D',
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

$colors = ['#006482',
			  '#0172BB',
			  '#018380',
			  '#026851',
			  '#0A0A0A',
			  '#0E4E93',
			  '#111E6C',
			  '#1A3A46',
			  '#264E3A',
			  '#2C262D',
			  '#2E183D',
			  '#3062A5',
			  '#512D55',
			  '#679ACD',
			  '#696969',
			  '#8C0306',
			  '#007002',
			  '#21512D',
			  '#42008A',
			  '#B2005B',
			  '#7F4F4F',
			  '#6161CD',
			  '#520100',
			  '#8F2703'];

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
	  grid-row-gap: 12px;
	  grid-column-gap: 12px;
 }
 
 .btn {
	  
	  height: 100% !important;
	  width: 100% !important;
	  border-radius: 0px !important;
 }
 
 .button-edit-color {

	  width: 50px;
	  height: 50px;
 }
 
</style>

<script>
 
 button = <?php echo json_encode ($button); ?>;

</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="buttonClose ()"></i>
</div>

<form id="button_edit_form" name="button_edit_form"> <!-- start button edit form -->

	 <input type="hidden" id="color" name="color" value="<?= $button ['color'] ?>">
	 <input type="hidden" name="fixed" value="1">
	 
	 <div class="form-grid button-edit-grid">

		  <div id="button_text_echo" class="form-cell form-desc-cell"><?= __('Button text') ?></div>
		  <?php

		  $text = '';
		  if (isset ($button ['text'])) {

				$text = $button ['text'];
		  }
		  
		  echo $this->input ('fa-text-size',
									['id' =>'text',
									 'name' => 'text',
									 'value' => $text,
									 'class' =>'form-control button_control',
									 'placeholder' => __ ('Button text')]);
		  ?>
		  
		  <div class="grid-cell grid-span-all">
				
				<div class="color-grid">

					 <?php
					 
					 $index = 0;
					 foreach ($colors as $color) { ?>

						  <div class="grid-cell">
								
								<button id="color_<?= $index ?>" type="button" class="btn btn-block button-edit-color" style="background: <?= $color ?>;" onclick="buttonColor (<?= $index?>, '<?= $color ?>')"></button>
								
						  </div>
						  
						  <?php
						  
						  $index ++;
						  } ?>
						  
				</div>
		  </div>
	 </div>
	 
	 <!-- end of form is in button_footer.php -->
	 
	 <script>
	  
	  $('#text').val (button.text.toUpperCase ());
	  $(buttonID).html (button.text.toUpperCase ());
	  
	  function save () {
			
			let button = $('#button_edit_form').serializeJSON ();
						
			if (button.color.length == 0) {

				 button.color = '#eeeeee';
			}
			
			posConfig.config.pos_menus [container] ['horizontal_menus'] [menu] ['buttons'] [pos] = button;
			buttonClose ();
			render (container);
	  }
	  
	  function buttonClose () {
			
			$('#button_container').toggleClass ('on');
	  }

	  $('#button_desc').on ('keyup', function (e) {

			let text = $('#button_desc').val ().toUpperCase ();
			$('#button_text').html (text);
			$(buttonID).html (text);
	  });
	  
	  function buttonColor (index, color) {

			$('#color').val (color);
			
			if (colorIndex >= 0) {
				 
				 $('#color_' + colorIndex).removeClass ('button-color-select');
			}

			colorIndex = index;
			$('#color_' + index).addClass ('button-color-select');
			$(buttonID).css ("background-color", color);
	  }
	 </script>

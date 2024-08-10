<?php
$positions = [null => __ ('Position relative to current menu'),
              'above' => 'Above',
              'below' => 'Below',
              'left' => 'Left of',
              'right' => 'Right of'];

$positions = [null => __ ('Choose horizontal or vertical menus'),
              'horizontal_menus' => 'Horizontal',
              'vertical_menus' => 'Vertical'];

$rows = [null => 'Rows',
         1 => '1',
         2 => '2',
         3 => '3',
         4 => '4',
         5 => '5',
         6 => '6',
         7 => '7',
         8 => '8',
         9 => '9',
         10 => '10',
         11 => '11',
         12 => '12'];

$cols = [null => 'Columns',
         1 => '1',
         2 => '2',
         3 => '3',
         4 => '4',
         5 => '5',
         6 => '6',
         7 => '7',
         8 => '8',
         9 => '9',
         10 => '10',
         11 => '11',
         12 => '12'];

$actions = [null => 'Actions',
            'add_menu' => __ ('Add menu'),
            'resize_menu' => __ ('Resize menu'),
            'remove_menu' => __ ('Remove menu'),
            'clear_button' => __ ('Clear button')];

$this->debug ($this->request);

?>

<style>

 .btn-menu {
     height: 100px !important;
     font-size: 1.0em !important;
 }
</style>

<?= $this->Html->css ("menu.css") ?>

<style>
 
 .main-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 1fr;
     grid-row-gap: 3px;
     grid-column-gap: 3px;
     margin-top: 50px;
 }
 
 .controls-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: .5fr .5fr 2fr 1fr 1fr 1fr;
     grid-row-gap: 3px;
     grid-column-gap: 10px;
     margin-top: 15px;
     padding-left: 5px;
     padding-right: 5px;
 }

 .menu-header-grid {
	  
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 1fr;
 }
 
 .detail-grid {
	  
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
     grid-column-gap: 5px;
     grid-row-gap: 25px;
 }
 
 .menu-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: repeat(<?= $width ?>, 1fr);
     grid-row-gap: 3px;
     grid-column-gap: 3px;
     justify-content: start;
     align-content: start;
     margin-top: 25px;
 }

 .color-grid {
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: repeat(8, 1fr);
     grid-row-gap: 0px;
     grid-column-gap: 3px;
     height: 100%;
 }
 
 .edit-menu-grid {
	  
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: repeat(3, 1fr);
     grid-row-gap: 0px;
     grid-column-gap: 3px;
     margin-top: 25px;
 }
 
 .edit-complete-grid {
	  
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 2fr repeat(2, 1fr);
     grid-row-gap: 0px;
     grid-column-gap: 3px;
     padding: 0px;
     background: #fff !important;
     box-shadow: none !important;
 }

 .alert-bg {
     color: #fff !important;
     background-color: #198754 !important;
 }

 /* overides */
 
 .btn {
     color: #fff !important;
     font-weight: 500 !important;
     width: 100% !important;
     line-height: 1.2 !important;
 }
 
 .form-check-input {

     margin: 10px 0px 0px 15px !important;
     font-size: 1.2em !important;
 }

 .form-check-label {

     margin: 15px 0px 0px 10px !important;
 }

 .align-bottom {
     margin-top: auto;
 }

 .button-edit-grid {

     display: flex !important;
     flex-direction: column;
     height: 100%;
 }
 
</style>-

<div class="main-grid">

    <!-- left side, controls -->
	 
    <div class="button-edit-grid">
		  
        <div class="form-section">
            <?= __ ('Edit button') ?>
        </div>
		  
        <div class="form-grid detail-grid">
		  		
            <div class="form-cell form-desc-cell"><?= __('Button text') ?></div>
            <div class="form-cell form-control-cell">
                <input type="text" id="button_text" name="button_text" class="form-control" placeohlder="" value="" onblur="updateControlText ()" placeholder="Enter text">
            </div>
				
            <div id="color_pickers" class="form-cell"></div>				
            <div class="form-cell form-control-cell">
                <div id="colors" class="form-grid color-grid"></div>
            </div>
				
            <div id="functions_prompt" class="form-cell form-desc-cell"> <?= __('Button functions') ?></div>
            <div id="controls" class="form-cell select"></div>  <!-- controller picker -->

            <div id="button_edit" class="grid-cell grid-span-all">
                <?= __ ('Select a button position to begin.') ?>
            </div>
				
        </div>
		  
    </div>

    <!-- right side, menu-->
	 
    <div class="grid-cell grid-cell-center">
		  
        <div class="grid-cell grid-cell-center">
				
            <div class="form-grid menu-header-grid">
					 
                <div class="form-cell form-control-cell">
                    <?= $this->Form->control ('menu_name', ['class' => 'form-control',
                                                            'id' => 'menu_name',
                                                            'name' => 'menu_name',
                                                            'label' => false,
                                                            'placeholder' => __ ('Menu name')]) ?>
                </div>

                <div class="form-cell form-control-cell">
						  
                    <div id="tabs"></div>
                </div>
					 
            </div>
				

            <!-- menu content -->
				
            <div id="grid_size"></div>
            <div id="menus"></div>

            <!-- menus control grid, add resize... -->
				

        </div>
    </div>	

</div>  <!-- main grid -->


<div class="controls-grid">

    <div class="form-cell">
        <button id="save_button" class="btn btn-secondary btn-block" onclick="save ()"><?= __ ('Save') ?></button>
    </div>
	 
    <div class="form-cell">
        <button class="btn btn-danger btn-block" onclick="cancel ()"><?= __ ('Close') ?></button>
    </div>
	 
    <div class="grid-cell"></div>
	 
    <div class="form-cell">
        <?= $this->Form->select ('add_menu_rows', $rows, ['class' => 'custom-dropdown', 'id' => 'add_menu_rows', 'label' => false]); ?>
	 </div>
	 
	 <div class="form-cell">
		  <?=  $this->Form->select ('add_menu_cols', $cols, ['class' => 'custom-dropdown', 'id' => 'add_menu_cols', 'label' => false]);?>
	 </div>
	 
	 <div class="form-cell">
		  <?=  $this->Form->select ('menu_actions', $actions, ['onchange' => '"actions ()"', 'class' => 'custom-dropdown', 'id' => 'menu_actions', 'label' => false]);?>
	 </div>
	 
</div>


<?= $this->Html->script ('typeahead.js') ?>
<?= $this->Html->script ('editors.js') ?>

<script>
 
 config = <?php echo json_encode ($config); ?>;
 configID = <?= $configID ?>;
 menus = '<?= $menus ?>';
 menuCount = <?= $menuCount ?>;
 picker = '<?= $picker ?>';
 colorPickers = <?= json_encode ($colorPickers) ?>;
 csrfToken = <?= json_encode ($this->request->getParam ('_csrfToken')) ?>;
 menuUpdate = false;
 attr = null;
 decimalPlaces = 2;
 decimalSep = '<?= __ ('decimal_sep')?>';
 selected = '';
 cellIndex = -1;
 currMenu = 0;
 styleUpdate = '';
 cells = null;
 controllers = <?= json_encode ($controls) ?>;
 menuIndex = {'horizontal_menus': 0,
				  'vertical_menus': 0};
 
 axis = 'horizontal_menus';
 legacyColors = <?php echo json_encode ($legacyColors); ?>;
 
 render ();
 
 $('#menu_name').focusout (function (e) {

     let name = $(this).val ().toUpperCase ();
     $(this).val (name);	  

     /* update the config object*/
	  
     let menu = config [config [menus] ['horizontal_menus'] [currMenu] ['name']];
     menu.name = name;
	  
     $('#tabs').html (tabs ());  /* redraw tabs*/
 });
 
 function render () {
	  
     if (config [menus] [axis].length == 0) {
			
			$('#menus').html ('');
			return;
     }
	  
     $('#tabs').html (tabs ());
     $('#menus').html (menu (currMenu));
     $('#color_pickers').html (pickers ());
     $('#colors').html (colors ());
     $('#controls').html (controls ());
	  
     $('#menu_description').focusout (function () {
			
			let menuID = config [menus] ['horizontal_menus'] [menuIndex [axis]] ['name'];
			let menu = config [menuID];
			menu.name = $('#menu_description').val ();
     });
	  
     $('head title').text (config [config [menus] ['horizontal_menus'] [currMenu] ['name']] ['name']);
 }
 
 function tabs () {
	  
     let html = '<select id="tab_select" onchange="tab ()" class="custom-dropdown">';
     $.each (config [menus] ['horizontal_menus'], function (i, m) {

			html += '<option value="' + i + '">' + config [m.name] ['name'] + '</option>';
     });

     return (html + '</select>');
 }

 function tab () {
	  
     $('#menus').html (menu ($('#tab_select').val ()));
     $('head title').text (config [config [menus] ['horizontal_menus'] [currMenu] ['name']] ['name']);
 }

 function menu (index) {
	  
     currMenu = index;
     let menu = config [config [menus] ['horizontal_menus'] [index] ['name']]
     $('#menu_name').val (config [config [menus] ['horizontal_menus'] [index] ['name']].name);
	  
     $('#grid_size').html ('<style>.menu-grid {grid-template-columns: repeat(' + menu.width + ', 1fr);}</style>')
	  
     cells = menu.buttons;
     let html = '<div class="menu-grid">';
	  
     $(menu.buttons).each (function (i, b) {
			
			if (b.class != 'Null') {

             if (typeof b.color === "undefined") {

                 b.color = '#000';
             }
				 
             if (b.color.startsWith ('color_')) {

                 b.color = legacyColors [b.color];
             }
				 
             html += '<div id="cell_' + i + 
							'" class="btn grid-cell-left btn-menu" ' +
							'style="background:' + b.color + '"' +
							'onclick="edit (' + i + ')">' + b.text + 
							'</div>';
			}
			else {
				 
             html += '<div id="cell_' + i + '" class="btn grid-cell-center btn-menu empty" onclick="edit (' + i + ')"><i class="far fa-plus fa-large action_icon btn-center color_white"></i></div>';
			}
     });
	  
     styleUpdate ='';
     return (html += '</div>');
 }

 function pickers () {

     let html = '<select id="color_picker" onchange="changePallet ()" class="custom-dropdown">';
     $.each (colorPickers, function (k, p) {

			html += '<option value="' + k + '">' + p.name + '</option>';
     });

     return (html + '</option>');
 }
 
 function colors () {

     let html = '';

     $.each (colorPickers [picker].colors, function (i, c) {

			html += '<button class="btn grid-cell-left" ' +
					  'style="background:' + c + '" onclick="changeColor (' + i + ')">' +
					  '</button>';
     });

     return html;
 }

 function controls () {

     let html =
         '<select id="select_control" name="select_control" onchange="control ();">' +
         '<option selected value="">Select a button action</option>';
	  
     for (var className in controllers) {
			
			if (className.startsWith ('separator')) {
             html += '<option disabled class="select-separator">' + controllers [className] ['desc'] + '</option>';
			}
			else if (controllers [className] ['desc'].length > 0) {
             html += '<option value="' + className + '">' + controllers [className] ['desc'] + '</option>';
			}
     }
	  
     return html += '</select>';
 }
 
 function changePallet () {
	  
     picker = $('#color_picker').val ();
     $('#colors').html (colors ());
 }
 
 function changeColor (color) {

     cells [cellIndex] ['color'] = colorPickers [picker].colors [color]; 
     $('#cell_' + cellIndex).css ({'background-color': '"' + colorPickers [picker].colors [color] + '"'});
 }
 
 function actions () {

     switch ($('#menu_actions').val ()) {

			case 'add_menu':
				 
				 if (($('#add_menu_rows').val () == 0) || ($('#add_menu_cols').val () == 0)) {

					  alert ('<?= __ ('Please select a size (rows/columns)') ?>');
					  return;
				 }
				 
				 currMenu = config [menus] ['horizontal_menus'].length;
				 let name = 'menu_' +  currMenu;
				 
				 config [menus] ['horizontal_menus'].push ({name: name, type: 'controls'});

				 let buttons = [];
				 let numButtons = $('#add_menu_rows').val () * $('#add_menu_cols').val ();
				 
				 for (let i=0; i<numButtons; i ++) {

					  buttons.push ({class: 'Null'});
				 }
				 
				 var m = {name: name, width: $('#add_menu_cols').val (), height: $('#add_menu_rows').val (), buttons: buttons};
				 
				 config [name] = m;

				 $('#tabs').html (tabs ());
				 $('#menus').html (menu (currMenu));

				 break;
				 
			case 'resize_menu':

				 if (($('#add_menu_rows').val () == 0) || ($('#add_menu_cols').val () == 0)) {
					  
					  alert ('<?= __ ('Please select a size (rows/columns)') ?>');
					  return;
				 }
				 
				 var m = config [config [menus] ['horizontal_menus'] [currMenu] ['name']];

				 var oldSize = m.buttons.length;
				 var newSize =  $('#add_menu_rows').val () * $('#add_menu_cols').val ()

             if (newSize > oldSize) {

                 for (i=0; i < (newSize - oldSize); i++)  {

							m.buttons.push ({class: 'Null'});
                 }
             }
             else if (newSize < oldSize) {

                 for (i=0; i < (oldSize - newSize); i++)  {

							m.buttons.pop ();
                 }
             }
				 
				 config [config [menus] ['horizontal_menus'] [currMenu] ['name']] ['width'] = $('#add_menu_cols').val ();
				 config [config [menus] ['horizontal_menus'] [currMenu] ['name']] ['height'] = $('#add_menu_rows').val ();
				 
				 $('#menus').html (menu (currMenu));
				 break;
				 
			case 'remove_menu':
				 
				 let mname = config [menus] ['horizontal_menus'] [currMenu] ['name'];
				 
				 config [menus] ['horizontal_menus'].splice (currMenu, 1);

				 delete config [mname];
				 
				 $('#tabs').html (tabs ());
				 $('#menus').html (menu (0));
				 break;

			case 'clear_button':

				 clearButton ();
				 break;
     }

     $('#menu_actions').val (null);
     $('#save_button').removeClass ('btn-secondary');
     $('#save_button').addClass ('btn-success');
 }
 
 /**
	 
	 edit button
	 
  */
 
 function edit (cell) {

     if (cellIndex >= 0) {
			
			$('#cell_' + cell).removeClass ('btn-highlight');
     }
	  
     cellIndex = cell;
	  
     $('#cell_' + cell).addClass ('btn-highlight');
	  
     let params = {};
     let control = "";
	  
     if (cells [cellIndex].class != 'Null') {
			
			params = cells [cellIndex].params;
			control = controllers [cells [cellIndex] ['class']];
     }
     else {

			$('#functions_prompt').html ('<?= __ ('Select a function')?>');
			$('#functions_prompt').addClass ('alert-bg');
			return;
     }
	  
     $('#cell_' + cellIndex).addClass ('btn-highlight');
	  
     if (cells [cellIndex] ['color'] == 'Null') {

			changeColor ('color_0');
     }
	  
     let editor =
         '<div class="form-grid detail-grid">' +
         editors [control ['method']] (cells [cellIndex], null) +
         '</div>';
	  
     $('#button_edit').html (editor);
     $('#button_text').val (cells [cellIndex].text.toUpperCase ());

     $('#save_button').removeClass ('btn-secondary');
     $('#save_button').addClass ('btn-success');
 }
 
 /**
  *
  * select control
  *
  */
 
 function control () {

     if (cellIndex < 0) {

			alert ('<?= __ ('Please select a button') ?>');
			return;
     }
	  
     let control = controllers [$('#select_control').val ()];
	  
     cells [cellIndex] = {'class': $('#select_control').val (),
								  'text':  control.desc,
								  'params': control.params};

     let editor =
         '<div class="form-grid detail-grid">' +
         editors [control.method] (control)
     '</div>';
	  
     $('#button_text').val (control.desc.toUpperCase ());
     $('#cell_' + cellIndex).html (control.desc.toUpperCase ());
     $('#button_edit').html (editor);
	  
     $('#select_control').val ('')
     $('#save_button').removeClass ('btn-secondary');
     $('#save_button').addClass ('btn-success');
 }
 
 function reset () {
	  
     config [menus] [axis] = [];
 }
 
 /**
  *
  * character input
  *
  */

 function updateControlText () {
	  
     if (cellIndex >= 0) {

			menuUpdate = true;
			$('#save_button').removeClass ('btn-secondary');
			$('#save_button').addClass ('btn-success');

			$('#cell_' + cellIndex).html ($('#button_text').val ().toUpperCase ());
			cells [cellIndex] ['text'] = $('#button_text').val ().toUpperCase ();
			
     }
 }
 
 function clearButton () {
	  
     if (cellIndex >= 0) {

			$('#cell_' + cellIndex).removeClass ('btn-highlight');
			$('#cell_' + cellIndex).html ('');
			$('#cell_' + cellIndex).addClass ('empty');
			$('#cell_' + cellIndex).css ({'background-color': "#fff"});

			cells [cellIndex] = {class: "Null"};
			$('#button_edit').html ('');
			/* cellIndex = -1;*/
     }
 }
 
 function save () {
	  
     var data = {};
	  
     data ['config_id'] = configID;
     data ['menu_description'] = $('#menu_description').val ();
     data ['config'] = JSON.stringify (config);
	  
     let url = '<?= $this->request->getAttribute ('webroot') ?>pos-configs/menus/' + configID + '/' + menus;
	  
     $.ajax ({ url: url,
					type: 'POST',
					data: data,
					headers: {'X-CSRF-Token': csrfToken}}
     ).done (function (data) {
			
			controller ('/pos-configs', true);
     });
 }
 
 function cancel () {
	  
     let url = '/pos-configs';

     if (menuUpdate) {
			
			if (confirm ("You have unsaved changes!")) {
				 
             controller ('/pos-configs', true);
             return;
			}
     }
     controller ('/pos-configs', true);
 }

 $("#button_text").keyup (function () {
	  
     $('#save_button').removeClass ('btn_secondary');
     $('#save_button').addClass ('btn_success');
	  
     this.value = this.value.toUpperCase ();
     if (cellIndex >= 0) {
			$('#cell_' + cellIndex).html ($('#button_text').val ().toUpperCase ());
			cells [cellIndex] ['text'] = $('#button_text').val ().toUpperCase ();
     }
 });
 
 $('#menu_actions').change (function () {

     actions ();
 });

 function params (inputID, inputType) {

     var params = cells [cellIndex].params;
	  
     if (cellIndex >= 0) {
			
			let amount = 0;
			
			switch (inputType) {

				 case 'checkbox':
					  
					  params [inputID] = $('#' + inputID).is (':checked');
					  break;
					  
				 case 'text':
					  
					  params [inputID] = $('#' + inputID).val ();
					  break;
					  
				 case 'select':
					  
					  params [inputID] = $('#' + inputID).val ();
					  break;
					  
				 case 'percent':
					  
					  amount = parseFloat ($('#' + inputID).val ());
					  params [inputID] = amount;
					  break;
					  
				 case 'currency':

					  amount = parseFloat ($('#' + inputID).val () * 100.0);
					  params [inputID] = amount;
					  break;
					  
				 case 'integer':

					  percent = parseInt ($('#' + inputID).val ());					  
					  params [inputID] = percent;
					  break;
			}

			cells [cellIndex].params = params;
			
     }
 }

 function getParam (params, name, placeholder) {
	  
     let value = 'placeholder="' + placeholder + '"';
     if (typeof params [name] !== "undefined") {
			
			value = 'value="' + params [name] + '"';
     }

     return value;
 }
 
 function clearButton () {
	  
     if (cellIndex >= 0) {
			
			$('#cell_' + cellIndex).removeClass ('btn-highlight');
			
			$('#cell_' + cellIndex).addClass ('empty');
			$('#cell_' + cellIndex).html ('<i class="far fa-plus fa-large action_icon btn-center color_white"></i>');
			$('#cell_' + cellIndex).css ({'background-color': '"#555"'});
			
			cells [cellIndex] = {class: "Null", params: {}};
			
			$('#button_edit').html ('');
			/* cellIndex = -1;*/
     }
 }

 /**
  *
  * character input
  *
  */

 var ctl = false;
 var ctlC = 67;
 var ctlX = 88;
 var ctlV = 86;
 var cellCopy = null;
 
 xlate = {currency_placeholder: '<?= __ ('currency_placeholder') ?>',
			 percent_placeholder: '<?= __ ('0%') ?>',
			 percent_markdown: '<?= __ ('Item markdown percent') ?>',
			 percent_markdown_desc: '<?= __ ('Markdown item by percent, 1.125 = 1.125%') ?>',
			 amount_markdown: '<?= __ ('Item markdown amount') ?>',
			 amount_markdown_desc: '<?= __ ('Markdown $ amount, 0.00 will prompt for markdown') ?>',
			 percent_discount: '<?= __ ('Sale disocunt (percen)t') ?>',
			 percent_discount_desc: '<?= __ ('Discount all items in sale, 1.125 = 1.125%') ?>',
			 receipt_desc: '<?= __ ('Description on receipt') ?>',
			 fixed_amount: '<?= __ ('Fixed Amount 0.00') ?>',
			 cash_amount: '<?= __ ('Amount, (0.00 one up)') ?>',
			 service_fee: '<?= __ ('Service Fee %') ?>',
			 open_drawer: '<?= __ ('Open drawer') ?>',
			 open_drawer_desc: '<?= __ ('Always open cash drawer at end of sale') ?>',
			 print_receipt: '<?= __ ('Print receipt') ?>',
			 print_receipt_desc: '<?= __ ('Always print a receipt at end of sale') ?>',
			 debit: '<?= __ ('Debit') ?>',
			 credit: '<?= __ ('Credit') ?>',
			 ebt_foodstamp: '<?= __ ('EBT foodstamp') ?>',
			 ebt_cash: '<?= __ ('EBT cash') ?>',
			 debit_credit: '<?= __ ('Debit/Credit/EBT') ?>',
			 credit_return: '<?= __ ('Credit return') ?>',
			 debit_return: '<?= __ ('Debit return') ?>',
			 bank_functions: '<?= __ ('Bank functions') ?>',
			 open_amount: '<?= __ ('Open amount') ?>',
			 cash_drop: '<?= __ ('Cash drop') ?>',
			 paid_in: '<?= __ ('Paid in') ?>',
			 paid_out: '<?= __ ('Paid out') ?>',
			 reason_code: '<?= __ ('Add reason code') ?>',
			 search_items: '<?= __ ('Search items') ?>',
			 add_item_desc: '<?= __ ('Add item') ?>',
			 nav_menu: '<?= __ ('Menu tab') ?>',
			 phone_format: '<?= __ ('phone_format') ?>',
			 postal_code_format: '<?= __ ('postal_code_format') ?>',
			 percent_format: '<?= __ ('percent_format') ?>',
			 currency_format: '<?= __ ('currency_format') ?>',
			 integer_format: '<?= __ ('integer_format') ?>'	 
 };

 switch ('<?= $merchant ['locale'] ?>') {

	  case 'en_US':

			xlate.currency_placeholder = '0.00';
			xlate.percent_placeholder = '0.000';
			break;

	  default:
			
			xlate.currency_placeholder = '0,00';
			xlate.percent_placeholder = '0,000';
			break;
 }
 
 /**
  *
  * cut and past buttons
  *
  */

 var ctl = false;
 var ctlC = 67;
 var ctlX = 88;
 var ctlV = 86;
 var cellCopy = null;
 
 $(document).keydown (function (event) {
	  
     if (event.ctrlKey || event.metaKey) {

			ctl = true;
     }

     if (ctl) {

			switch (event.which) {

				 case ctlC:
					  
					  cellCopy = cells [cellIndex];
					  break;
					  
				 case ctlX:
					  
					  cellCopy = cells [cellIndex];
					  /* clearButton ();*/
					  break;
					  
				 case ctlV:
					  
					  if (cellCopy != null) {
							
							cells [cellIndex] = cellCopy;
							
							$('#cell_' + cellIndex).removeClass ('btn-highlight');
							$('#cell_' + cellIndex).addClass (cellCopy ['color']);
							$('#cell_' + cellIndex).html (cellCopy ['text']);
							
							cellCopy = null;
					  }
					  break;
			}
     }
	  
     return true;
 });
 
 $(document).keyup (function (event) {
	  
     if (event.ctrlKey || event.metaKey) {

			ctl = false;
     }
	  
     return true;
 });

</script>

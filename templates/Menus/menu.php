
<?php

$this->debug ($controls);

$menu = $posConfig ['config'] ['pos_menus'] [$menuName];
$configID = $posConfig ['id'];
$numButtons = count ($menu ['horizontal_menus'] [$menuIndex] ['buttons']);
$cols = $menu ['horizontal_menus'] [$menuIndex] ['width'];
$rows = $numButtons / $cols;

// scale the button size depending on the number of columns

$buttonSize = '75px';
$buttonTextSize = '1em';

switch ($cols) {

	 case 6:
	 case 7:
		  $buttonSize = '75px';
		  $buttonTextSize = '1em';
		  break;
		  
	 case 8:
	 case 9:
	 case 10:
		  $buttonSize = '75px';
		  $buttonTextSize = '.5em';
		  break;
}

$rootLayout = '';
$pos = 0;
$subMenu = 0;
$rows = count ($menu ['horizontal_menus'] [$menuIndex] ['buttons']) / $menu ['horizontal_menus'] [$menuIndex] ['width'];

$menusTooltip = __ ("Click to select menu area to edit");
$buttonsTooltip = __ ("Click to select button, \nctrl-x cut, ctrl-c copy ctrl-v paste, \ndouble click to edit");
$buttonCount = count ($menu ['horizontal_menus'] [$menuIndex] ['buttons']);

// scale the button height based on the number of buttons

$buttonHeight = intval (50 * (50/count ($menu ['horizontal_menus'] [$menuIndex] ['buttons'])));

?>

<!-- menu style sheet, note dynamic button size and menu width -->

<style>
 
 .menu-button {

	  cursor: pointer;
	  font-size: <?= $buttonTextSize ?>;
	  height: <?= $buttonHeight ?>px;
	  color: white !important;
	  margin: 5px;
	  padding-top: 5px;
	  padding-left: 5px;
	  border-radius: 5px;
	  border: solid 2px #aaa;
 }

 .button-grid {
	  
	  display: grid;
	  width: 100%;
	  grid-template-columns: repeat(<?= $cols ?>, 1fr) !important;
	  grid-row-gap: 10px;
	  grid-column-gap: 10px;
 }
 
</style>

<link href="/assets/css/menus.css" rel="stylesheet">

<input type="hidden" id="config_id" value="<?= $configID ?>">
<input type="hidden" id="menu_name" value="<?= $menuName ?>">
<input type="hidden" id="menu_index" value="<?=$menuIndex ?>">

<div class="row mt-3">

	 <div class="col-sm-6"></div>
	 
	 <div class="col-sm-3">
		  <?=
		  $this->Form->select ('sub_menu_index',
									  $menus,
									  ['id' => 'sub_menu_index',
										'value' => $menuIndex,
										'class' => 'form-select',
										'label' => false,
										'required' => 'required',
										'onchange' => "menus.subMenu ()"])
		  ?>
	 </div>
	 
	 <div class="col-sm-3">
		  <?=
		  $this->Form->select ('menu_actions',
									  $menuActions,
									  ['id' => 'menu_actions',
										'data-bs-toggle' => 'modal',
										'data-bs-target' => '#menus_modal',
										'value' => '',
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<div class="menu-grid" id="menu_grid">

	 <!-- Render the navigation element for this config -->

	 <div class="menu-layout <?= $posConfig ['config'] ['root_layout'] ?>"><?php echo $this->element ($posConfig ['config'] ['root_layout']) ?></div>

	 <!-- button grid goes here -->
	 
	 <div id="button_grid" "data-bs-toggle="tooltip" title="<?= $buttonsTooltip ?>"></div>

	 <div>

	 </div>

	 <!-- save menu changes -->

	 <div class="grid-span-all">
		  <div class="row mt-5">
				<div class="col-sm-9"></div>
 				<div class="col-sm-3 d-grid">
					 <button class="btn btn-secondary" id="save_menu" onclick="menus.update ()"><?= __ ('Save changes') ?></button>
				</div>
		  </div>
	 </div>

	 <!-- modal content -->

	 <div class="modal fade" id="menus_modal" aria-hidden="fales" tabindex="-1">
		  <div class="modal-dialog modal-dialog-scrollable">
				<div class="modal-content">
					 <div id="modal_header" class="modal-header">
						  <span id="modal_title"></span>
						  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					 </div>
					 <div id="modal_content" class="modal-body">
					 </div>
				</div>
		  </div>
	 </div>

	 <script>
	  
	  curr = <?= json_encode ($menu ['horizontal_menus'] [$menuIndex]) ?>;
	  pos = 0;
	  var configID =  <?= $configID ?>;
	  var menuName = '<?= $menuName ?>';
	  var menuIndex = '<?= $menuIndex ?>';
	  var pos = 0;
	  var rows = <?= $rows ?>;
	  var cols = <?= $cols ?>;
	  let confirmChanges = '<?= __ ('You have unsaved changes, do you want to continue?') ?>';

	 </script>

	 <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	 <script src="/assets/js/menus.js"></script>

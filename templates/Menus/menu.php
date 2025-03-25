
<?php

$menu = $posConfig ['config'] ['pos_menus'] [$menuName];
$configID = $posConfig ['id'];
$actions = [null => __ ('Actions'),
				'add_before' => __ ('Insert menu'),
				'add_after' => __ ('Append menu'),
				'rename' => __ ('Rename'),
				'resize' => __ ('Resize'),
				'delete' => __ ('Delete')];

$menuWidth = $menu ['horizontal_menus'] [$menuIndex] ['width'];
$numButtons = count ($menu ['horizontal_menus'] [$menuIndex] ['buttons']);
$buttonHeight = intval (120 - $numButtons);

$buttonTextSize = '1';
if ($numButtons > 30) {

	 $buttonTextSize = '.7';
}

$rootLayout = '';

$pos = 0;
$subMenu = 0;
$rows = count ($menu ['horizontal_menus'] [$menuIndex] ['buttons']) / $menu ['horizontal_menus'] [$menuIndex] ['width'];

$menusTooltip = __ ("Click to select menu area to edit");
$buttonsTooltip = __ ("Click to select button, \nctrl-x cut, ctrl-c copy ctrl-v paste, \ndouble click to edit");

?>

<!-- menu style sheet -->

<link href="/assets/css/menus.css" rel="stylesheet">

<input type="hidden" id="config_id" value="<?= $configID ?>">
<input type="hidden" id="menu_name" value="<?= $menuName ?>">
<input type="hidden" id="menu_index" value="<?=$menuIndex ?>">

<div class="menu-grid">

	 <!-- Render the navigation element for this config -->

	 <div class="menu-layout <?= $posConfig ['config'] ['root_layout'] ?>"><?php echo $this->element ($posConfig ['config'] ['root_layout']) ?></div>

	 <!-- button grid goes here -->
	 
	 <div id="button_grid" class="button-grid" "data-bs-toggle="tooltip" title="<?= $buttonsTooltip ?>"></div>

<div>

	 <!-- menu action form -->
	 
	 <form class="row g-1" id="menu_edit" method="post">
		  
		  <div class="row g-3">
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
		  
		  <div class="row g-3">
				<label for="menu_name" class="col-sm-12"><?= __ ('Menu name') ?></label>
				<div class="col-sm-12">
					 <?= $this->input ('menu_name',
											 ['id' => 'menu_name',
											  'value' => $menu ['horizontal_menus'] [$menuIndex] ['name'],
											  'class' => 'form-control'])
					 ?>
				</div>
		  </div>

		  <div class="row g-3">
 				<label for="rows" class="col-sm-3 form-label"><?= __ ('Rows') ?></label>	 
				<div class="col-sm-9">
					 <?=
					 $this->Form->select ('rows',
												 [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8],
												 ['id' => 'rows',
												  'value' => $rows,
												  'class' => 'form-select',
												  'label' => false,
												  'required' => 'required'])
					 ?>
				</div>
		  </div>
		  
		  <div class="row g-3">
 				<label for="rows" class="col-sm-3 form-label"><?= __ ('Columns') ?></label>	 
				<div class="col-sm-9">
					 <?=
					 $this->Form->select ('cols',
												 [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8],
												 ['id' => 'cols',
												  'value' => $menu ['horizontal_menus'] [$menuIndex] ['width'],
												  'class' => 'form-select',
												  'label' => false,
												  'required' => 'required'])
					 ?>
				</div>
		  </div>
		  
		  <div class="row g-3">
 				<div class="col-sm-12">
					 <?=
					 $this->Form->select ('menu_actions',
												 $actions,
												 ['id' => 'menu_actions',
												  'value' => 0,
												  'class' => 'form-select',
												  'label' => false,
												  'required' => 'required',
												  'onchange' => 'menus.actions ()'])
					 ?>
				</div>
		  </div>

	 </form>
</div>

<!-- save menu changes -->

<div class="grid-span-all">
	 <div class="row g-3 mt-5">
		  <div class="col-sm-4 d-grid text-center"></div>
 		  <div class="col-sm-4 d-grid text-center">
				<button class="btn btn-secondary" id="save_menu" onclick="menus.update ()"><?= __ ('Save changes') ?></button>
		  </div>
	 </div>
</div>

<!-- modal content -->

<div class="modal fade" id="menus_modal" aria-hidden="fales" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
		  <div class="modal-content">
				<div id="modal_header" class="modal-header">
					 <span id="modal_title"><?= __ ('Edit button') ?></span>
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
 var isDirty = false;
 let confirmChanges = '<?= __ ('You have unsaved changes, do you want to continue?') ?>';

</script>

<script src="/assets/js/menus.js"></script>

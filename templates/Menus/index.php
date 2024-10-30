<?= $this->Html->css ("Menus/index") ?>

<script>

 posConfig = <?php echo json_encode ($posConfig); ?>;
 console.log (posConfig.config.pos_menus);
 
</script>

<?php
$cntainers = [];
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
			10 => '10'];

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
			10 => '10'];

$actions = [null => __ ('Actions'),
				'add_before' => __ ('Insert menu'),
				'add_after' => __ ('Append menu'),
				'rename' => __ ('Rename'),
				'resize' => __ ('Resize'),
				'delete' => __ ('Delete')];

?>

<div class="form-grid controls-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
	 
</div>

<div class="menu-grid">
	 
	 <?php 
	 foreach ($posConfig ['config'] ['pos_menus'] as $container => $menus) {
		  
		  $this->debug ("container... $container");
		  $this->debug ($menus);
		  
		  $height = null;
		  $width = null;
		  $name = '';
		  $style = '';
		  
		  if (count ($menus ['horizontal_menus'])) {
				
				$height = intVal (count ($menus ['horizontal_menus'] [0] ['buttons']) / $menus ['horizontal_menus'] [0] ['width']);
				$width = intVal ($menus ['horizontal_menus'] [0] ['width']);
				$name = $menus ['horizontal_menus'] [0] ['name'];
				$style = $menus ['horizontal_menus'] [0] ['style'];
		  }
		  
		  $this->debug ("width... $height $width");
	 ?>		

	 <div class="sub-menu-grid">

		  <div id="<?= $container ?>" class="grid-cell grid-cell-center">
		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				
				<div id="<?= $container ?>_picker"></div>
				
				<div class="menu-controls-grid float-top">
					 
					 <div class="add-menu-grid">
						  
						  <div class="form-cell form-desc-cell"><?= __('Menu name') ?></div>
						  <div class="grid-cell grid-cell-center">

								<?= $this->input ('fa-text-size',
														['id' => $container . '_name',
														 'name' => $container . '_name',
														 'value' => $name,
														 'class' =>'form-control button_control',
														 'placeholder' => __ ('Menu name')]);
								
								?>
						  </div>

						  <div class="controls-size-grid grid-span-all">
								
								<div class="form-cell form-desc-cell"><?= __('Rows') ?></div>
								<div class="grid-cell grid-cell-center">
									 <?= $this->Form->select ($container . '_rows',
																	  $rows,
																	  ['id' => $container . '_rows',
																		'class' => 'custom-dropdown',
																		'value' => $height,
																		'label' => false]); ?>
								</div>
								
								<div class="form-cell form-desc-cell"><?= __('Columns') ?></div>
								<div class="grid-cell grid-cell-center">
									 <?= $this->Form->select ($container . '_cols',
																	  $cols,
																	  ['id' => $container . '_cols',
																		'class' => 'custom-dropdown',
																		'value' => $width,
																		'label' => false]);
									 ?>
									 
								</div>
						  </div>
						  
						  <div class="form-cell form-desc-cell"><?= __('Style') ?></div>
						  <div class="grid-cell grid-cell-center">
								<?= $this->Form->select ('style',
																 [null => __ ('Style'),
																  'solid' =>'Solid',
																  'outline' => 'Outline'],
																 ['id' => 'style',
																  'onchange' => 'changeStyle (\'' . $container . '\')',
																  'class' => 'custom-dropdown',
																  'value' => $style,
																  'label' => false]);
								?>
								
						  </div>
						  
						  <div class="grid-cell grid-cell-center grid-span-all">
								<?= $this->Form->select ($container . '_action',
																 $actions,
																 ['id' => $container . '_action',
																  'onchange' => 'actions (\'' . $container . '\')',
																  'class' => 'custom-dropdown',
																  'label' => false,
																  'value' => false]);
								?>							 
						  </div>
					 </div>
				</div>
		  </div>
	 </div>
		  <?php 
		  }
		  ?>
		  
		  <div class="form-submit-grid">
				
				<div class="button-input">
					 <button type="button" id="menu_update" class="btn btn-secondary"><?= __ ('Save') ?></button>
				</div>
				
		  </div>
</div>

<div class="container">

	 <div id="button_container"></div>

</div>

<?= $this->Html->script (['Menus/index']); ?>

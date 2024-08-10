
<style>

 .pricing-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 2fr 1fr;
	  grid-column-gap: 0px;
 }
 
</style>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __('Pricing') ?></legend>

	 <div class="container">
		  
		  <div class="pricing-grid">

				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Name'); ?></div>
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description'); ?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Actions'); ?></div>
				
				<?php
				
				$row = 0;
				foreach  ($pricing as $p) {
					 
					 $rowClass = (($row % 2) == 0) ? ' even-cell' : '';
					 $row ++;
				?>
					 
					 <div class="grid-cell grid-cell-left<?= $rowClass ?>">
						  
						  <?php echo $this->Html->link ($p ['name'],
																  ['controller' => 'PosConfigs',
																	'action' => 'pricing-edit/' . $p->id],
																  ['class' => 'report-link']);?>
					 </div>
					 <div class="grid-cell grid-cell-left<?= $rowClass ?>">
						  <?= $p ['description'] ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right<?= $rowClass ?>">
						  <?= $this->Form->postLink ( '<i class="fa fa-trash fa-med"></i>',
															  ['action' => 'pricing-delete', $p->id],
															  ['escape' => false,
																'confirm' => __ ('Delete pricing option').' '.$p ['description'].'?']) ?>  
					 </div>
				<?php
				}
				
				?>
		  
				<div class="grid-cell grid-cell-left top15">

					 <?php
					 
					 echo $this->Form->select ('add_pricing',
														[null => __ ('Add Pricing Option'),
														 'size' => __ ('Price by product size, small, medium, large'),
														 'group' => __ ('Group pricing, all items in group priced the same')],
														['id' => 'add_pricing',
														 'class' => 'custom-dropdown',
														 'label' => false]);
					 ?>

				</div>
				
		  </div>
	 </div>
	 

</fieldset>

<script>
	 
 $('#add_pricing').change (function () {
	  
	  switch ($('#add_pricing').val ()) {

			case 'size':
			case 'metric':
			case 'group':
				 
				 window.location = "/pos-configs/pricing-edit/0/" + $('#add_pricing').val ();
				 break;
	  }
		
		$('#add_pricing').val ('').prop ('selected', true);	
	 });

</script>

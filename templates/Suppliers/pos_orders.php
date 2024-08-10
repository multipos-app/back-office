<?= $this->Html->css ("pos-styles.css") ?>
<style>
 
 .main-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr;
	  grid-column-gap: 10px;
 	  font-size: 24px !important;
 }

 .order-grid {
	  
	  display: grid;
	  width: 100%;
	  grid-template-rows: auto;
     grid-template-columns: repeat(5, 1fr) .7fr .7fr;
	  grid-column-gap: 5px;
 }

 .orders-btn {
	  font-weight: 400;
 	  font-size: 20px !important;
	  height: 30px !important;
 }
 
</style>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __('Orders') ?></legend>
	 
	 <div class="main-grid top30">
		  
		  <div class="order-grid">
				
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Order Number'); ?></div>
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Status'); ?></div>
				<div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Order Type'); ?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Items'); ?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Value'); ?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Actions'); ?></div>
				
				<?php
				
				$row = 0;
				$actions = '';
				
				foreach  ($orders as $order) {

					 $this->log ($order, 'debug');

					 $rowClass = (($row % 2) == 0) ? ' even-cell' : '';
					 $row ++;

					 $status = '';
					 switch ($order ['status']) {
								
						  case 0:

								$status = __ ('Open');
								// $actions =
									 //'<a href="/suppliers/order/' . $order ['id'] . '" class="btn keypad-btn">' . __ ('Edit'). '</a>' . 
									 //'<a href="/suppliers/send-order/' . $order ['id'] . '" class="btn keypad-btn">' . __ ('Send') . '</a>';
									 
								$actions =
									 '<div class="grid-cell grid-cell-center keypad-btn orders-btn bg-primary" onclick="javascript:order (' . $order ['id'] . ')">' . __ ('Edit') . '</div>' .
									 '<div class="grid-cell grid-cell-center keypad-btn orders-btn bg-success" onclick="javascript:post (' . $order ['id'] . ')">' . __ ('Post') . '</div>';
								
								break;
								
						  case 1:
								
								$status = __ ('Pending');
								$actions =
									 '<div class="grid-cell grid-cell-center keypad-btn orders-btn bg-primary" onclick="javascript:order (' . $order ['id'] . ')">' . __ ('Edit') . '</div>' .
									 '<div class="grid-cell grid-cell-center keypad-btn orders-btn bg-danger" onclick="javascript:close (' . $order ['id'] . ')">' . __ ('Close') . '</div>';
								break;
								
						  case 2:
								
								$status = __ ('Closed');
								$actions =
									 '<div class="grid-cell grid-cell-center keypad-btn orders-btn bg-success" onclick="javascript:viewOrder (' . $order ['id'] . ')">' . __ ('View') . '</div>' .
									 '<div class="grid-cell grid-cell-center"></div>';
							break;
					 }
				?>
					 
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= __ ($order ['id']) ?></div>
				<div class="grid-cell grid-cell-center<?= $rowClass ?>"><?= $status ?></div>
				<div class="grid-cell grid-cell-center<?= $rowClass ?>"><?= $order ['order_type_desc'] ?></div>
				<div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= $order ['order_quantity'] ?></div>
				<div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= money_format ('%!i', $order ['order_total']) ?></div>
				
				<?php
				echo $actions;
				}
				?>

		  </div>
	 </div>
</fieldset>

<script>

 function order (id) {
	  
	  window.location = '/suppliers/order/' + id;
 }
 
 function viewOrder (id) {
	  
	  window.location = '/suppliers/view-order/' + id;
 }
 
 function post (id) {
	  
	  window.location = '/suppliers/post-order/' + id;
 }
 
 function close (id) {
	  
	  window.location = '/suppliers/close-order/' + id;
 }
 
 $(document).ready (function () {

	  session ()
 });

</script>

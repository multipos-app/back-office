<style>

 .order-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: repeat(9, 1fr);
	  grid-column-gap: 0px;
 }

</style>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __('Orders') ?></legend>
	 
	 <div class="order-grid">

		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Order Number'); ?></div>
		  <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Status'); ?></div>
		  <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Order Type'); ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Open'); ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Pending'); ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Closed'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Items'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Value'); ?></div>
		  <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Actions') ?></div>
		  
		  <?php
		  
		  $row = 0;
		  foreach  ($orders as $order) {

				$this->log ($order, 'debug');

				$rowClass = (($row % 2) == 0) ? ' even-cell' : '';
				$row ++;

				$status = '';
				$actions = '<nav class="nav-menu d-none d-lg-block">' .
							  '<ul>' .
							  '<li class="drop-down dropdown-actions"><a class="dropdown-actions" href=#></a>' .
							  '<ul>';
				
				switch ($order ['status']) {

					 case 0:

						  $status = __ ('Open');
						  
						  $actions .= 
								'<li class="text-sm-left"> <a href=# onclick="javascript:window.open (\'/suppliers/order/' . 
								$order ['id'] . '\')">' . __ ('Edit') . '</a></li>';
						  
 						  if ($order ['order_quantity'] > 0) {
								
								$actions .=
									 '<li class="text-sm-left"><a href=/suppliers/post-order/' .
									 $order ['id'] . '>' . __('Send'). '</a></li>';
 						  }
 						  
 						  if ($order ['order_type'] == 1) {
								
 								$actions .=
 									 '<li class="text-sm-left"><a href=/suppliers/cancel-order/' .
									 $order ['supplier_id'] . '/' .
									 $order ['id'] . '>' . __('Cancel'). '</a></li>';
 						  }
						  break;
						  
					 case 1:
						  
						  $status = __ ('Pending');

						  $actions .=
								
								'<li class="text-sm-left"> <a href=# onclick="javascript:window.open (\'/suppliers/order/' . 
								$order ['id'] . '\')">' . __ ('Edit') . '</a></li>'.

								'<li class="text-sm-left"><a href=/suppliers/close-order/' .
								$order ['id'] . '>' . __('Close'). '</a></li>';
						  
						  break;
						  
					 case 2:
						  
						  $status = __ ('Closed');

						  $actions .=
								
								'<li class="text-sm-left"> <a href=# onclick="javascript:window.open (\'/suppliers/view-order/' . $order ['id'] . '\')">' . __ ('View') . '</a></li>';
				}

				$actions .=
					 '</ul>' .
					 '</li>' .
					 '</ul>' .
					 '</nav>';
		  
		  ?>
									 
		  <div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= __ ($order ['id']) ?></div>
		  <div class="grid-cell grid-cell-center<?= $rowClass ?>"><?= $status ?></div>
		  <div class="grid-cell grid-cell-center<?= $rowClass ?>"><?= $order ['order_type_desc'] ?></div>
		  <div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $order ['open'] ?></div>
		  <div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $order ['pending'] ?></div>
		  <div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $order ['closed'] ?></div>
		  <div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= $order ['order_quantity'] ?></div>
		  <div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= money_format ('%!i', $order ['order_total']) ?></div>
		  <div class="grid-cell grid-cell-center<?= $rowClass ?>"><?= $actions ?></div>
					 
		  <?php		  
		  }
		  
		  ?>
		  
	 </div>
	 
	 <div class="row top30">
		  <div class="col-sm-2">
				
				<a href="/suppliers/custom-order/<?= $order ['supplier_id'] ?>" class="btn btn-primary btn-block"><?= __ ('Custom Order'); ?></a>
				
		  </div>
	 </div>

	 <div class="container">
		  <div class="row">
				<div class="col-sm-12 text-center">
					 
					 <nav class="pagination">
						  <ul class="pagination">
								
								<?php
								echo $this->Paginator->prev (__('Previous'));
								echo $this->Paginator->numbers (['first' => 'First page']);
								echo $this->Paginator->next (__('Next'));
								?>
								
						  </ul>
					 </nav>
				</div>
		  </div>
	 </div>
	 
</fieldset>

<script>

 function send (orderID) {

	  console.log ('sending... ' + orderID);
	  
 }
</script>

<style>

 .alert-tickets {

	  margin-bottom: 15px;
 }

 .alert-tickets-grid {
	  
	  display: grid;
	  grid-template-columns: repeat(6, 1fr);
	  font-size: 90%;

 }
 
 .no-sales-tickets-grid {
	  
	  display: grid;
	  width: 30%;
	  grid-template-columns: repeat(2, 1fr);
	  font-size: 90%;

 }

</style>

<?php switch ($ticketType) {

	 case 'refunds':
?>
	 
	 <div class="alert-tickets">
		  
		  <div class="alert-tickets-grid">
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Square Trx')?></div>
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Time')?></div>
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Tender Type')?></div>
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Reason')?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Item Count')?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Total')?></div>
		  </div>

		  <?php
		  foreach ($tickets as $ticket) {
		  ?>		  
				<div class="alert-tickets-grid grid-row-wrapper" onclick="detail (<?= $ticket ['id'] ?>)">
					 <div class="grid-cell grid-cell-left"><?= $ticket ['ticket_no'] ?></div>
					 <div class="grid-cell grid-cell-left"><?= $ticket ['complete_time'] ?></div>
					 <div class="grid-cell grid-cell-left"><?= $ticket ['tender_desc'] ?></div>
					 <div class="grid-cell grid-cell-left"><?= $ticket ['comment'] ?></div>
					 <div class="grid-cell grid-cell-right"><?= $ticket ['item_count'] ?></div>
					 <div class="grid-cell grid-cell-right"><?= $ticket ['total'] ?></div>
				</div>
		  <?php
		  }
		  ?>
	 </div>

	 <?php

	 break;

	 case 'discounts':
	 ?>

	 <div class="alert-tickets">
		  
		  <div class="alert-tickets-grid">
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Square Trx')?></div>
				<div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Time')?></div>
				<div class="grid-cell grid-center grid-cell-separator"><?= __ ('Tender Type')?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Item Count')?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Discount')?></div>
				<div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Total')?></div>
		  </div>

		  <?php
		  foreach ($tickets as $ticket) {
		  ?>		  
				<div class="alert-tickets-grid grid-row-wrapper" onclick="detail (<?= $ticket ['id'] ?>)">
					 <div class="grid-cell grid-cell-left"><?= $ticket ['ticket_no'] ?></div>
					 <div class="grid-cell grid-cell-left"><?= $ticket ['complete_time'] ?></div>
					 <div class="grid-cell grid-center"><?= $ticket ['tender_desc'] ?></div>
					 <div class="grid-cell grid-cell-right"><?= $ticket ['item_count'] ?></div>
					 <div class="grid-cell grid-cell-right"><?= $ticket ['discounts'] ?></div>
					 <div class="grid-cell grid-cell-right"><?= $ticket ['total'] ?></div>
				</div>
		  <?php
		  }
		  ?>

		  <?php
		  
		  break;

		  case 'no_sales':
		  ?>
		  
		  <div class="alert-tickets">
				
				<div class="no-sales-tickets-grid">
					 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Square Trx')?></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Time')?></div>
				</div>

				<?php
				foreach ($tickets as $ticket) {
				?>		  
					 <div class="no-sales-tickets-grid grid-row-wrapper" onclick="detail (<?= $ticket ['id'] ?>)">
						  <div class="grid-cell grid-cell-left"><?= $ticket ['ticket_no'] ?></div>
						  <div class="grid-cell grid-cell-right"><?= $ticket ['complete_time'] ?></div>
					 </div>
				<?php
				}
				?>

				<?php
			
				break;
				?>
<?php
}
?>
<?= $this->Html->script (['tickets-detail']); ?>


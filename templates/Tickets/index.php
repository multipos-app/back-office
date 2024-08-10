<?= $this->Html->css (['Tickets/index']); ?>

<style>

 .search-grid {

	  margin-top: 25px;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 120px 1fr;
	  grid-column-gap: 15px;
 }

</style>

<div class="form-grid search-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
	 <div></div>
</div>

<div class="grid-cell grid-cell-right grid-span-all action-icon">
	 <a onclick="loadSearch ()"><span><?= __ ('Search') ?><i class="fa fa-magnifying-glass fa-large action-icon"></i></span></a>
	 <a onclick="salesExport ()"><i class="fa fa-download fa-large action-icon"></i></a>
</div>

<div id="page_export" class="tickets-grid">
	 
	 <div class="grid-cell grid-cell-left grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator data-cell"><?= __ ('#') ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator data-cell"><?= __ ('Location') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator grid-cell-center data-cell"><?= __ ('Complete time') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator grid-cell-center data-cell"><?= __ ('Type') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator data-cell"><?= __ ('Items') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator data-cell"><?= __ ('Discounts') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator data-cell"><?= __ ('Tips') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator data-cell"><?= __ ('Void items') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator data-cell"><?= __ ('Tender type') ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator data-cell"><?= __ ('Total') ?></div>
	 
	 <?php
	 
	 $row = 0;
	 foreach ($tickets as $ticket) {
		  
		  $action = 'onclick="detail (' . $ticket ['id'] . ')"';

		  $tenderDesc = __ ('UNKNOWN');
		  if ($ticket ['tender_desc'] !== null) {

				$tenderDesc = __ (strtoupper ($ticket ['tender_desc']));
		  }
		  
		  $flag = '';
		  if ($ticket ['tag'] !== null) {

				$flag = '<i class="fa fa-flag fa-small fa-warn"></i>';
		  }

		  $location = $merchant ['bu_names'] [$ticket ['business_unit_id']];
	 ?>
	 <div class="grid-row-wrapper" <?= $action ?>">
		  
		  <div class="grid-cell grid-cell-left" id="ticket_<?= $ticket ['id'] ?>"></div>
		  <div class="grid-cell grid-cell-left" id="flag_<?= $ticket ['id'] ?>"><?= $flag ?></div>
		  <div class="grid-cell grid-cell-left data-cell"><?= $ticket ['ticket_no'] ?></div>
		  <div class="grid-cell grid-cell-left data-cell"><?= $location ?></div>
		  <div class="grid-cell grid-cell-center data-cell"><?= $ticket ['complete_time'] ?></div>
		  <div class="grid-cell grid-cell-center data-cell"><?= $ticketTypes [$ticket ['ticket_type']] ?></div>
		  <div class="grid-cell grid-cell-right data-cell"><?= $ticket ['item_count'] ?></div>
		  <div class="grid-cell grid-cell-right data-cell"><?= $this->moneyFormat ($ticket ['discounts']) ?></div>
		  <div class="grid-cell grid-cell-right data-cell"><?= $this->moneyFormat ($ticket ['tip']) ?></div>
		  <div class="grid-cell grid-cell-right data-cell"><?= $ticket ['void_items'] ?></div>
		  <div class="grid-cell grid-cell-right data-cell"><?= $tenderDesc ?></div>
		  <div class="grid-cell grid-cell-right data-cell"><?= $this->moneyFormat ($ticket ['total']) ?></div>
		  
	 </div>
	 
	 <?php } ?>
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>

<div class="container"><div id="detail"></div></div>

<?= $this->Html->script (['Tickets/index']); ?>

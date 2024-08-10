<?= $this->Html->css ("Tickets/ticket") ?>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i>
</div>

<div id="ticket" class="ticket-grid">
	 	 
	 <?php if ($hasVideo) { ?>
		  <div class="grid-cell grid-cell-center top30">
				<div id="clip" class="video-box"/>
		  </div>
	 <?php } ?>
	 
	 <div class="grid-cell grid-cell-center">
		  
		  <div class="details-grid header-grid">
				
				<?php
				foreach ($ticket ['details'] as $detail) {
				?>
					 
					 <div class="grid-cell grid-cell-left">
						  <?= $detail ['desc'] ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $detail ['value'] ?>
					 </div>
				<?php
				}
				?>

				<div class="grid-cell grid-cell-center grid-cell-separator grid-span-all">
					 <?= __ ('Items') ?>
				</div>
				
				<?php
				foreach ($ticket ['ticket_items'] as $ti) {

					 $state = '';
					 switch ($ti ['state']) {

						  case 1:
								$state = ' void-item';
								break;
					 }
				?>
					 
					 <div class="grid-cell grid-cell-left<?= $state?>">
						  <?= sprintf ('%0d %s', $ti ['quantity'], $ti ['item_desc']) ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right<?= $state?>">
						  <?= $this->moneyFormat ($ti ['quantity'] * $ti ['amount']) ?>
					 </div>
					 
					 <?php
					 foreach ($ti ['ticket_item_addons'] as $tia) {
					 ?>
						  <div class="grid-cell grid-cell-left<?= $state?>">
								<?= sprintf ('%0d %s', $tia ['addon_quantity'], $tia ['addon_description']) ?>
						  </div>
						  
						  <div class="grid-cell grid-cell-right<?= $state?>">
								<?= $this->moneyFormat ($tia ['addon_quantity'] * $tia ['addon_amount']) ?>
						  </div>
					 <?php
					 }
					 }
					 ?>
					 <?php
					 if ($ticket ['tip'] > 0) {
					 ?>
						  <div class="grid-cell grid-cell-left">
								<?= __ ('Tip') ?>
						  </div>
						  
						  <div class="grid-cell grid-cell-right">
								<?= $this->moneyFormat ($ticket ['tip']) ?>
						  </div>

					 <?php
					 }
					 ?>
					 <?php
					 if (count ($ticket ['ticket_taxes']) > 0) {
					 ?>
						  
						  <div class="grid-cell grid-cell-center grid-cell-separator grid-span-all">
								<?= __ ('Taxes') ?>
						  </div>
						  
						  <?php
						  foreach ($ticket ['ticket_taxes'] as $tt) {
						  ?>
								
								<div class="grid-cell grid-cell-left">
									 <?= $tt ['short_desc'] ?>
								</div>
								
								<div class="grid-cell grid-cell-right">
									 <?= $this->moneyFormat ($tt ['tax_amount']) ?>
								</div>
								
						  <?php
						  }
						  }
						  ?>
		  </div>
		  
		  <div class="details-grid tender-grid">
				
				<div class="grid-cell grid-cell-left grid-cell-separator">
					 <?= __ ('Tender type') ?>
				</div>

				<div class="grid-cell grid-cell-right grid-cell-separator">
					 <?= __ ('Amount') ?>
				</div>
				
				<div class="grid-cell grid-cell-right grid-cell-separator">
					 <?= __ ('Returned') ?>
				</div>
				
				<div class="grid-cell grid-cell-right grid-cell-separator">
					 <?= __ ('Balance') ?>
				</div>
				
				<div class="grid-cell grid-cell-left even-cell grid-span-3">
					 <?= __ ('TOTAL') ?>
				</div>
				
				<div class="grid-cell grid-cell-right even-cell">
					 <?= $this->moneyFormat ($ticket ['total']) ?>
				</div>
				
				<?php
				
				$balance = $ticket ['total'];
				
				foreach ($ticket ['ticket_tenders'] as $tt) {

					 $balance -= $tt ['tendered_amount'] - $tt ['returned_amount'];
				?>
					 
					 <div class="grid-cell grid-cell-left">
						  <?= __ (strtoupper ($tt ['tender_type'])) ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $this->moneyFormat ($tt ['tendered_amount']) ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $this->moneyFormat ($tt ['returned_amount']) ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $this->moneyFormat ($balance) ?>
					 </div>
					 
					 <?php
					 switch ($tt ['tender_type']) {
								
						  case 'CREDIT':
						  case 'DEBIT':
								
								$card = json_decode ($tt ['data_capture'], true);
								$this->debug ($card);

								foreach (['card_brand' => 'CARD BRAND', 'cardholder_name' => 'CARDHOLDER', 'last_4' => 'LAST 4 DIGITS'] as $key => $value) {

									 $desc = '';
									 if (isset ($card [$key])) {
										  
										  $desc = $card [$key];
										  
										  switch ($key) {
													 
												case 'cardholder_name':

													 $desc = str_replace ("/", " ", $desc);
													 break;
										
												case 'last_4':
													 
													 $desc = '*' . $desc;
													 break;
										  }
										  
					 ?>
						  
						  <div class="grid-cell grid-cell-left">
								<?= __ ($value) ?>
						  </div>
						  <div/>
						  <div/>
						  <div class="grid-cell grid-cell-right">
								<?= $desc ?>
						  </div>
					 <?php
					 }
					 }
					 }
					 }
					 ?>

					 <div class="grid-cell grid-cell-left grid-span-3">
						  <?= __ ('BALANCE') ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-right">
						  <?= $this->moneyFormat ($balance) ?>
					 </div>
		  </div>
	 </div>
	 
	 <div class="grid-cell grid-cell-center ticket-text">
		  <pre>
				<?= $ticket ['ticket_text'] ?>
		  </pre>
	 </div>
	 
</div>

<?php 

if ($_SERVER ['SERVER_NAME'] == 'dev.myvideoregister.com') {

	 echo '<pre>' . $ticket ['data_capture'] . '</pre>';
	 foreach ($ticket ['ticket_tenders'] as $tender) {

		  if (strlen ($tender ['data_capture']) > 0) {

				echo '<pre>' . $tender ['data_capture'] . '</pre>';
		  }
	 }
}
?>

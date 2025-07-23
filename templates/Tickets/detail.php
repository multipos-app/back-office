
<style>

 .receipt {

	  font-family: monospace, monospace;
	  weight: 600;
	  background-color: #eee;
	  border-radius: 5px;
	  padding: 20px;
	  font-size: 120%;
 }
 
</style>

<div class="row g-1 mt-3">
	 <div class="col-sm-4">
		  <?= $this->Form->select ('flag', $flag, ['id' => 'flag', 'onchange' => 'flag ()', 'class' => 'form-select', 'label' => false, 'value' => false]) ?>
	 </div>
</div>

<?php if ($hasVideo) { ?>
	 <div class="">
		  <div id="clip" class="video-box"/>
	 </div>
<?php } ?>

<table class="table table-hover mt-3">
	 <tbody>
		  <?php
		  foreach ($ticket ['details'] as $detail) {
		  ?>
				
				<tr>
					 <td>
						  <?= $detail ['desc'] ?>
					 </td>
					 
					 <td align="right">
						  <?= $detail ['value'] ?>
					 </td>
				</tr>
		  <?php
		  }
		  ?>
	 </tbody>
</table>

<table class="table table-hover mt-3">
	 <tbody>
		  
		  <td colspan="3" align="center" class="fw-bold">
				<?= __ ('Items') ?>
		  </td>
		  
		  <?php
		  foreach ($ticket ['ticket_items'] as $ti) {

				$strikeThrough = '';
				if ($ti ['state'] == 1) {

					 $strikeThrough = ' void-item';
				}
		  ?>

		  <tr>
				<td class="<?= $strikeThrough ?>">
					 <?= sprintf ('%0d', $ti ['quantity']) ?>
				</td>
				<td class="<?= $strikeThrough ?>">
					 <?= sprintf ('%s', $ti ['item_desc']) ?>
				</td>
				
				<td align="right" class="<?= $strikeThrough ?>">
					 <?= $this->moneyFormat ($ti ['quantity'] * $ti ['amount']) ?>
				</td>
		  </tr>
        <?php
		  }
		  
		  if ($ticket ['tip'] > 0) {
		  ?>
				<td>
				</td>
				<td>
					 <?= __ ('Tip') ?>
				</td>
				
				<td>
					 <?= $this->moneyFormat ($ticket ['tip']) ?>
				</td>
	 </tbody>
    <?php
	 }
	 ?>
	 
	 <?php
	 if (count ($ticket ['ticket_taxes']) > 0) {
	 ?>
		  <table class="table table-hover mt-3">
				<tbody>
					 <td colspan="2" align="center" class="fw-bold">
						  <?= __ ('Taxes') ?>
					 </td>
					 
					 <?php
					 foreach ($ticket ['ticket_taxes'] as $tt) {
					 ?>
						  <tr>
								<td>
									 <?= $tt ['short_desc'] ?>
								</td>
								
								<td align="right">
									 <?= $this->moneyFormat ($tt ['tax_amount']) ?>
								</td>
						  </tr>
						  
					 <?php
					 }
					 }
					 ?>
				</tbody>
		  </table>

		  <table class="table table-hover mt-3">
				<thead align="right">
					 <tr>
						  <th></th>
						  
						  <th>
								<?= __ ('Amount') ?>
						  </th>
						  
						  <th>
								<?= __ ('Returned') ?>
						  </th>
						  
						  <th>
								<?= __ ('Balance') ?>
						  </th>
						  
					 </tr>
				</thead>
				<tbody>

					 <tr>
						  <td colspan="3">
								<?= __ ('TOTAL') ?>
						  </td>
						  
						  <td align="right" style="text-align:right;">
								<?= $this->moneyFormat ($ticket ['total']) ?>
						  </td>
					 </tr>
					 
					 <?php
					 
					 $balance = $ticket ['total'];
					 
					 foreach ($ticket ['ticket_tenders'] as $tt) {

						  $this->debug ($tt);
						  
						  $balance -= floatval ($tt ['tendered_amount']) - floatval ($tt ['returned_amount']);
						  $balance = round ($balance, 2);
						  
						  $tenderType = __ ('Unknown');
						  if ($tt ['tender_type'] !== null) {
								
								$tenderType = __ (strtoupper ($tt ['tender_type']));
						  }
					 ?>

					 <tr>
						  <td>
								<?= $tenderType ?>
						  </td>
						  
						  <td align="right">
								<?= $this->moneyFormat ($tt ['tendered_amount']) ?>
						  </td>
						  
						  <td align="right">
								<?= $this->moneyFormat ($tt ['returned_amount']) ?>
						  </td>
						  
						  <td align="right">
								<?= $this->moneyFormat (abs ($balance)) ?>
						  </td>
						  
						  <?php
						  switch ($tt ['tender_type']) {
									 
								case 'CARD':
								case 'CREDIT':
								case 'DEBIT':
									 
									 $card = json_decode ($tt ['data_capture'], true);
									 
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
						  
						  <td>
								<?= __ ($value) ?>
						  </td>
					 </tr>
					 <tr>
						  <td>
								<?= $desc ?>
						  </td>
					 </tr>
	 <?php
	 }
	 }
	 }
	 }
	 ?>

	 <tr>
		  <td colspan="3">
				<?= __ ('BALANCE') ?>
		  </td>
		  
		  <td align="right">
				<?= $this->moneyFormat (abs ($balance)) ?>
		  </td>
	 </tr>
				</tbody>
		  </table>
		  
		  <div class="row g-1 mt-5">
				<div class="col-sm-12 text-center fw-bold">
					 <h3><?= __ ('Printed receipt') ?></h3>
				</div>
		  </div>
		  <?php
		  $receipt = str_replace (' ', '&nbsp;', str_replace ("\n", "</br>", $ticket ['ticket_text']));
		  ?>
		  <div class="row g-1 m-3">
				<div class="card">
					 <div class="card-body">
						  <div class="col-sm-12 mt-3 receipt">
								<center>
									 <?= $receipt?>
								</center>
						  </div>
					 </div>
				</div>
		  </div>


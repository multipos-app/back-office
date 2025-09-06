<?php
echo $this->element ('video_container');
?>

<div class="row g-1 mt-3">
	 <div class="col-sm-4">
		  <?= $this->Form->select ('flag', $flag, ['id' => 'flag', 'onchange' => 'flag ()', 'class' => 'form-select', 'label' => false, 'value' => false]) ?>
	 </div>
</div>

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
		  
		  if (isset ($ti ['ticket_item_addons']) && (count ($ti ['ticket_item_addons']) > 0)) {
				
				foreach ($ti ['ticket_item_addons'] as $tia) {					 
		  ?>
				<td>
				</td>
				<td class="<?= $strikeThrough ?>">
					 <?= sprintf ('%s', $tia ['addon_description']) ?>
				</td>
				
				<td align="right" class="<?= $strikeThrough ?>">
					 <?= $this->moneyFormat ($tia ['addon_amount']) ?>
				</td>
				
		  <?php
		  }
		  }
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

		  <?php
		  }
		  ?>
	 </tbody>
</table>

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
								
								foreach (['card_brand' => __ ('CARD BRAND'),
											 'cardholder_name' => __ ('CARDHOLDER'),
											 'last_4' => __ ('LAST 4 DIGITS')] as $key => $value) {
									 
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
				</tr>	 
				<tr>
					 <td>
						  <?= __ ($value) ?>
					 </td>
					 <td></td>
					 <td></td>
					 <td align="end">
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
		  <?=
		  $this->moneyFormat (abs ($balance))
		  ?>
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

	 $receipt = '';

	 switch ($merchant ['category']) {

		  case 'multipos':
				
				if ($ticket ['ticket_text'] != null) {
					 
					 $receipt = str_replace (' ', '&nbsp;', str_replace ("\n", "</br>", $ticket ['ticket_text']));
				}
				break;

		  case 'square':
				
				if (isset ($ticket ['data_capture'])) {

					 $dataCapture = json_decode ($ticket ['data_capture'], true);
					 
					 if (isset ($dataCapture ['payment'] ['receipt_url'])) {
						  
						  $receipt = '<a href="' . $dataCapture ['payment'] ['receipt_url'] . '" target=_blank>' .
										 __ ('Open square receipt') .
										 '</a>';
					 }
				}
				break;
	 }
	 
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

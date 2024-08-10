

<table width="80%" style="margin-left:50px; margin-right:50px; border-collapse: collapse;">
	 
	 <tr bgcolor="#1A4066">
		  <td colspan="3" style="color:#fff; font-size:3em; text-align:center;">
				<?= __ ('Purchase Order') ?>&nbsp;#<?= $order ['id'] ?>
		  </td>
	 </tr>
	 <tr bgcolor="#1A4066">
		  <td colspan="3" style="color:#fff; font-size:1.5em; text-align:center;">
				<?= $order ['order_date'] ?>
		  </td>
	 </tr>

	 <tr>
		  <td colspan="3">
				
				<fieldset class="maintenance-border">
					 <legend class="maintenance-border"><?= __('Customer') ?></legend>
					 
					 <strong><?= $order ['business_units'] [0] ['business_name']?></strong><br>
					 <?= $order ['business_units'] [0] ['addr_1']?><br>
					 <?= $order ['business_units'] [0] ['city']?>,&nbsp;<?= $order ['business_units'] [0] ['state']?>,&nbsp;<?= $order ['business_units'] [0] ['postal_code']?><br>
					 <?= $order ['business_units'] [0] ['phone_1']?><br>
					 <?= $order ['business_units'] [0] ['email']?><br>
					 <?= $order ['business_units'] [0] ['contact']?><br>
				</fieldset>
		  </td>
	 </tr>

	 <tr>
		  <td width="40%">
				<fieldset class="maintenance-border">
					 <legend class="maintenance-border"><?= __('Vendor') ?></legend>
					 <strong><?= $order ['supplier'] ['supplier_name']?></strong><br>
					 <?= $order ['supplier'] ['addr1']?><br>
					 <?= $order ['supplier'] ['addr3']?>,&nbsp;<?= $order ['supplier'] ['addr5']?>,&nbsp;<?= $order ['supplier'] ['addr6']?><br>
					 <?= $order ['supplier'] ['phone1']?><br>
					 <?= $order ['supplier'] ['email']?><br>
					 <?= $order ['supplier'] ['contact1']?><br>
				</fieldset>
		  </td>
		  
		  <td width="20%"></td>
		  <td width="40%">
				<fieldset class="maintenance-border">
					 <legend class="maintenance-border"><?= __('Ship To') ?></legend>
					 <strong><?= $order ['business_units'] [1] ['business_name']?></strong><br>
					 <?= $order ['business_units'] [1] ['addr_1']?><br>
					 <?= $order ['business_units'] [1] ['city']?>,&nbsp;<?= $order ['business_units'] [1] ['state']?>,&nbsp;<?= $order ['business_units'] [1] ['postal_code']?><br>
					 <?= $order ['business_units'] [1] ['phone_1']?><br>
					 <?= $order ['business_units'] [1] ['email']?><br>
					 <?= $order ['business_units'] [1] ['contact']?><br>
				</fieldset>
		  </td>
	 </tr>
</table>

<table width="80%" style="margin:50px; border:solid 1px; border-collapse: collapse;">
	 
	 <tr bgcolor="#1A4066">
		  <th width="20%" style="color:#fff; padding: 5px; text-align:left"><?= __ ('SKU'); ?></th>
		  <th width="40%" style="color:#fff; padding: 5px; text-align:left"><?= __ ('Description'); ?></th>
		  <th width="15%" style="color:#fff; padding: 5px; text-align:right"><?= __ ('Cost'); ?></th>
		  <th width="15%" style="color:#fff; padding: 5px; text-align:right"><?= __ ('Order Quantity'); ?></th>
		  <th width="15%" style="color:#fff; padding: 5px; text-align:right"><?= __ ('Total'); ?></th>
	 </tr>
	 
	 <?php
	 
	 $orderTotal = 0;
	 $orderQuantity = 0;
	 $row = 0;
	 
	 foreach ($order ['items'] as $item) {

		  $total = $item ['item_prices'] [0] ['cost'] * $item ['inv_items'] [0] ['order_quantity'];
		  $orderQuantity += $item ['inv_items'] [0] ['order_quantity'];
		  $orderTotal += $total;

		  if (($row % 2) == 0) {
				echo '<tr>';
		  }
		  else {
				echo '<tr bgcolor="#eee">';
		  }
		  $row ++;
	 ?>					 
	 <td style="padding: 5px;"><?= $item ['sku'] ?></td>
	 <td style="padding: 5px;"><?= $item ['item_desc'] ?></td>
	 <td style="padding: 5px; text-align:right"><?= money_format ('%!i', $item ['item_prices'] [0] ['cost']) ?></td>
	 <td style="padding: 5px; text-align:right"><?= $item ['inv_items'] [0] ['order_quantity'] ?></td>
	 <td style="padding: 5px; text-align:right"><?= money_format ('%!i', $total) ?></td>

	 </tr>
		  <?php
		  }
		  ?>
		  <tr bgcolor="#1A4066">
				<td style="color:#fff; padding: 5px; font-size: 1.5em;"><?= __ ('Totals')?></td>
				<td></td>
				<td></td>
				<td style="color:#fff; text-align:right; padding: 5px; font-size: 1.5em;"><?= $orderQuantity ?></td>
				<td style="color:#fff; text-align:right; padding: 5px; font-size: 1.5em;"><?= money_format ('%!i', $orderTotal) ?></td>
		  </tr>
</table>


<?php

$titles = ['total' => ['text' => 'gross', 'type' => 'currency', 'link' => 'period'],
           'tax' => ['text' => 'tax',  'type' => 'currency'],
           'cash' => ['text' => 'cash',  'type' => 'currency', 'link' => 'tender_desc/cash'],
           'paid_out' => ['text' => 'paid out', 'type' => 'currency'],
           'credit' => ['text' => 'credit', 'type' => 'currency', 'link' => 'tender_desc/credit'],
           'card' => ['text' => 'card', 'type' => 'currency', 'link' => 'tender_desc/card'],
           'debit' => ['text' => 'debit', 'type' => 'currency', 'link' => 'tender_desc/debit'],
           'wallet' => ['text' => 'wallet', 'type' => 'currency', 'link' => 'tender_desc/wallet'],
           'buy_now_pay_later' => ['text' => 'buy now pay later', 'type' => 'currency', 'link' => 'tender_desc/buy_now_pay_later'],
           'square_account' => ['text' => 'square account', 'type' => 'currency', 'link' => 'tender_desc/square_account'],
           'external' => ['text' => 'external', 'type' => 'currency', 'link' => 'tender_desc/external'],
           'ebt_foodstamp' => ['text' => 'ebt foodstamp', 'type' => 'currency', 'link' => 'tender_desc/ebt_foodstamp'],
           'mobile' => ['text' => 'mobile', 'type' => 'currency', 'link' => 'tender_desc/mobile'],
           'check' => ['text' => 'check', 'type' => 'currency', 'link' => 'tender_desc/check'],
           'other' => ['text' => 'other', 'type' => 'currency', 'link' => 'tender_desc/other'],
           'gift_cards' => ['text' => 'gift cards', 'type' => 'currency', 'link' => 'tender_desc/gift_card'],
           'gift_certificates' => ['text' => 'gift certificates', 'type' => 'currency', 'link' => 'tender_desc/gift_certificate'],
           'account' => ['text' => 'account', 'type' => 'currency', 'link' => 'account', 'link' => 'tender_desc/account'],
           'taxable' => ['text' => 'tax sales', 'type' => 'currency'],
           'split' => ['text' => 'split', 'type' => 'currency', 'link' => 'tax', 'link' => 'tender_desc/split'],
           'round' => ['text' => 'round', 'type' => 'currency', 'link' => 'tax', 'link' => 'round/0'],
           'non_taxable' => ['text' => 'non tax sales', 'type' => 'currency'],
           'discounts' => ['text' => 'discounts', 'type' => 'currency', 'link' => 'ticket_contains/discounts'],
           'tips' => ['text' => 'tips', 'type' => 'currency', 'link' => 'ticket_contains/tip'],
           'void_sales' => ['text' => 'void sales', 'type' => 'currency', 'link' => 'ticket_type/void_sales'],
           'avg_sale' => ['text' => 'avg net sales', 'type' => 'currency'],
           'customer_count' => ['text' => 'customer count', 'type' => 'int'],
           'return_sales' => ['text' => 'return sales', 'type' => 'currency', 'link' => 'ticket_type/return_items'],
           'comp_sales' => ['text' => 'comp sales', 'type' => 'currency', 'link' => 'ticket_type/comp_sales'],
           'no_sales' => ['text' => 'no sales', 'type' => 'int', 'link' => 'ticket_type/no_sales'],
           'void_items' => ['text' => 'void items', 'type' => 'currency', 'link' => 'ticket_type/void_items'],
           'refunds' => ['text' => 'refunds', 'type' => 'currency', 'link' => 'ticket_type/refund_sales'],
           'return_items' => ['text' => 'return items', 'type' => 'currency', 'link' => 'ticket_type/return_items']];

$format = function ($type, $totalCol) {
	 
	 switch ($type) {
				
		  case 'int': return $totalCol;
		  case 'currency': return $this->moneyFormat ($totalCol);
	 }
	 return $this->moneyFormat ($totalCol);
};

?>

<div class="row g-1 m-3">
	 
	 <?php 
	 echo $this->element ('period_controls', ['url' => '/dashboard']);
	 ?>
	 
</div>

<table class="table table-hover">
    <thead align="right">
		  <tr>
            <th></th>
				<?php
	 	 
				foreach ($periods as $period) {
				?>
					 <th align="right"><?= $period ['period'] ?></th>
				<?php
				}
				?>
				
				<th align="right"><?= __ ('Total') ?></th>
		  </tr>
	 </thead>
	 <tbody>
				
		  <?php
		  
		  foreach ($totals ['sales'] as $key => $total) {
				
				// skip zero totals
				
				if ($total [count ($total) - 1] == 0) {
					 
					 continue;
				}
				
				$title = strtoupper ($key);
				$type = '';
				$link = false;
				
				if (isset ($titles [$key])) {
					 
					 $type = $titles [$key] ['type'];
					 $title = strtoupper (__ ($titles [$key] ['text']));
					 
					 if (isset ($titles [$key] ['link'])) {
						  
						  $link = $titles [$key] ['link'];
					 }
				}	
		  ?>

		  <tr>
				<td><?= __ ($title) ?></td>

				<?php
				
				$col = 0;
				foreach ($total as $totalCol) {
					 
					 $colDate = '';						  
					 if (isset ($periods [$col] ['start'])) {
						  
						  $colDate = strtotime ($periods [$col] ['start']);
					 }
					 
					 $val = $format ($type, $totalCol);
					 if (($col < count ($periods)) && ($totalCol != 0)) {
						  
						  $a = $val;
						  if ($link) {
								
								$a = '<a href="/tickets/index/period/' . $colDate. '">' . $val . '</a>';
						  }
						  
						  echo '<td align="right">' . $a . '</td>';
					 }
					 else {
						  
						  echo '<td align="right">' . $val .'</td>';
					 }
					 $col ++;
				}
				echo '</tr>';
		  		}
				
				?>

				<thead align="center">
					 <th colspan="9" align="center">
						  <h5><?= __ ('Departments') ?></h5>
					 </th>
				</thead>
		  </tr>
		  
		  <?php
				
		  foreach ($totals ['departments'] as $department) {
					 
					 echo '<tr><td>' . strtoupper ($department ['department_desc']) . '</td>';
					 
					 $col = 0;
					 
					 foreach ($department ['totals'] as $total) {
						  
						  $colDate = '';						  
						  if (isset ($periods [$col] ['start'])) {
								
								$colDate = strtotime ($periods [$col] ['start']);
						  }
						  
						  if (($col < 7) && $total != 0) {
								
								$a = '<a href="/tickets/index/period/' . $colDate. '/department_id/' . $department ['id'] . '">' . $format ('currency', $total) . '</a>';
								echo '<td align="right">' . $a . '</td>';
						  }
						  else {
						  
								echo '<td align="right">' . $format ('currency', $total) . '</td>';
						  }
					 
						  $col ++;
					 }
				echo '</tr>';
		  }
		  ?>
		  <tr>
				<td><?= __ ('TOTALS') ?></td>
				<?php
				
				foreach ($totals ['department_totals'] as $total) {
					 
					 $val = $format ('currency', $total);
					 echo '<td align="right">' . $format ('currency', $total) . '</td>';
				}
				?>
		  </tr>
	 </tbody>


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
				<th align="right"><?=  __ ('Total') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>

		  <?php

		  $totals = [];
		  $totalsReset = [];
		  $grandTotals = [];
		  
		  foreach ($periods as $period) { 
				$totals [] = ['amount' => 0.0, 'quantity' => 0];
				$totalsReset [] = ['amount' => 0.0, 'quantity' => 0];
				$grandTotals [] = ['amount' => 0.0, 'quantity' => 0];
		  }
		  
		  $totals [] = ['amount' => 0.0, 'quantity' => 0];
		  $totalsReset [] = ['amount' => 0.0, 'quantity' => 0];
		  $grandTotals [] = ['amount' => 0.0, 'quantity' => 0];

		  foreach ($departments as $department) {

				echo '<tr><td colspan="9" align="center"><h5>' . $department ['department_desc'] . '</h5></td></tr>';
				
				foreach ($department ['items'] as $item) {

					 $html = '<tr><td>' . strtoupper ($item ['item_desc']) . '</td>';
					 $col = 0;

					 foreach ($item ['totals'] as $itemData) {

						  if ($itemData ['amount'] < 0) {
								$itemData ['quantity'] *= -1;
						  }

						  if (($col < count ($periods)) && (intval ($itemData ['quantity']) != 0)) {
																
								$url = 'tickets/index/item_search/' . $item ['item_desc'] .
										 '/start_date/' . $periods [$col] ['start'] .
										 '/end_date/' . $periods [$col] ['end'];
																
								$html .= '<td align="right">' .
											'<a class="report-link"' . $url . '>' .
											$itemData ['quantity'] . '/' .
											$this->moneyFormat ($itemData ['amount']) .
											'</a>' .
											'</td>';
						  }
						  else {
								
								$html .= '<td align="right">0/' .
											$this->moneyFormat (0) .
											'</td>';
						  }
						  
						  $totals [$col] ['amount'] += $itemData ['amount'];
						  $totals [$col] ['quantity'] += $itemData ['quantity'];
						  $grandTotals [$col] ['amount'] += $itemData ['amount'];
						  $grandTotals [$col] ['quantity'] += $itemData ['quantity'];
						  $col ++;
					 }
					 
					 $html .= '</tr>';
					 echo $html;
				}
				}
				?>
				
	 </tbody>
</table>

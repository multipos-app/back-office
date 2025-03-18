
<?php 

$report = [];
$dayTotals = array_fill (0, 8, 0);

for ($hour = 0; $hour < $hourly ['hid']; $hour ++) {
	 
	 $report [$hour] = array ($hourly ['times'] [$hour]);
	 $total = 0;
	 
	 for ($day = 0; $day < 7; $day ++) {
		  
		  array_push ($report [$hour], $hourly [$day] [$hour]);
	 }
	 array_push ($report [$hour], $total);
}

for ($day = 0; $day < 7; $day ++) {
	 
	 for ($hour = 0; $hour < $hourly ['hid']; $hour ++) {
		  
		  $dayTotals [$day] += $hourly [$day] [$hour] ['amount'];
	 }
	 
	 $dayTotals [7] += $dayTotals [$day];
}
?>

<table class="table table-hover">
	 
	 <thead align="right">
		  <tr><th></th>
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

		  $start = $dow;
		  $end = $dow + 1;
		  $start = 0;
		  $end = 9;
		  $oneHour = 60 * 60;

		  $html = '<tr>';
		  for ($hour = 0; $hour < $hourly ['hid']; $hour ++) {
	 			
				$dayTotal = 0;
				
				for ($day = $start; $day < $end; $day ++) {
					 
					 switch ($day) {
								
						  case 0:
								$html .= '<td>' . substr ($report [$hour] [$day], 11, 5) . '</td>';
								break;

						  case 8:
								
								$html .= '<td align="right">' . $this->moneyFormat ($dayTotal) . '</td>';
								break;

						  default:

								$action = $this->moneyFormat ($report [$hour] [$day] ['amount']);
								
								if (floatval ($report [$hour] [$day] ['amount']) != 0.0) {

									 // $utcHour = $hour - $this->tzOffset ($this->tz);
										  
									 $startTime = sprintf ("%s %02d:00:00", substr ($periods [$day] ['start'], 0, 10), $hour);
									 $endTime = sprintf ("%s %02d:00:00", substr ($periods [$day] ['start'], 0, 10), $hour + 1);
									 
									 $link = "/tickets/index/start_hour/$startTime/end_hour/$endTime/business_unit_id/1";
										  
									 $this->debug ("val... $link");
									 									 
									 $html .= '<td align="right"><a href="' . $link . '">' . $this->moneyFormat ($report [$hour] [$day] ['amount']) . '</a></td>';
								}
								else {

									 $html .= '<td align="right" style="color:#777;">' . $this->moneyFormat ($report [$hour] [$day] ['amount']) . '</td>';
								}
								break;
					 }
				}
				$html .= '</tr>';
		  }

		  $html .= '<tr><td>'.__ ('Total', true).'</td>';
		  for ($day = 0; $day < 8; $day ++) {
				
				$html .= '<td align="right">' . $this->moneyFormat ($dayTotals [$day]) . '</td>';
		  }

		  echo $html;
		  ?>
	 </tbody>
</table>

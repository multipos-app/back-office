
<style>

 .search-grid {

	  margin-top: 25px;
     width: 30%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr auto;
	  grid-column-gap: 10px;
 }
 
</style>

<?php


//$this->log ($employees, 'debug');

?>

<div class="row g-1 m-3">
	 
	 <?php 
	 echo $this->element ('period_controls', ['url' => '/labor']);
	 ?>

</div>

<table class="table table-hover">
    <thead align="right">
		  <tr>
				<th style="text-align: start;">
					 <?= __ ('Employee') ?>
				</th>
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
	 <tbody align="right">

		  <?php
		  
		  $col = 0;
		  $total = 0;
		  
		  foreach ($employees as $employee) {
				
				echo '<tr><td style="text-align: start;">' . $employee ['fname'] . '</td>';
				foreach ($employee ['periods'] as $period) {
					 
					 $col = 0;
					 echo '<td>';
					 $total += $period ['cost'];

					 if (($period ['cost'] > 0) && ($col < $len)) {
						  
						  $colDate = '';						  
						  if (isset ($periods [$col] ['start'])) {
								
								$colDate = strtotime ($periods [$col] ['start']);
						  }
						  	  
						  echo '<a href="#" onclick="tickets (' . $colDate . ',' . $employee ['id'] . ')">' .
		  						 '<span class="report-link">' . $this->moneyFormat ($period ['cost']) . '</span>' .
								 '</a>';
					 }
					 else {
						  
						  echo $this->moneyFormat ('%!i', $period ['cost']);
					 }
					 
					 echo '</td>';
					 $col ++;
				}
				echo '<tr>';
		  }
		  ?>
	 </tbody>
</table>

<script>

 function tickets (period, clerk) {

	  let url = `/tickets/index/period/${period}/clerk_id/${clerk}`
	  console.log (url);
	  window.open (url);
 }

</script>

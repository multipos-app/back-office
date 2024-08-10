
<style>
 
 .search-grid {

	  margin-top: 25px;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 120px 1fr 1fr 2fr 1fr 2fr;
	  grid-column-gap: 10px;
 }
 
 .item-history-header-grid {
	  
	  display: grid;
     width: 100%;
	  grid-template-columns: 7fr repeat(8, 3fr);
     grid-column-gap: 0;
 	  margin-top: 25px;
}

 .item-history-grid {
	  
	  display: grid;
     width: 100%;
	  grid-template-columns: 7fr repeat(8, 1fr 2fr);
     grid-column-gap: 0;
 }
 
 .grid-span-all {
	  grid-column: 1 / 18;
 }

</style>

<div class="form-grid search-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="start_date" name="start_date" class="form-control datetimepicker-input start-date" autocomplete="off" onchange="searchDate ()" placeholder="<?= __ ('Search Start Date') ?>"/>

	 </div>
	 
	 <div class="form-cell">
		  
		  <?php echo $this->Form->select ('department_id',
													 $depts,
													 ['onchange' => "search ('department_id')",
													  'id' => 'department_id',
													  'class' => 'custom-dropdown',
													  'label' => false,
													  'value' => false]); ?>
	 </div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="item_desc" class="form-control" onchange="search ('item_desc')" placeholder="<?= __ ('Item description') ?>">
	 </div>
	 	 
	 <div class="grid-cell">
		  <button class="btn btn-primary" onclick="javascript:search ()"><?= __ ('Search') ?></button>
	 </div>

</div>

<?php
	 
$header = '<div class="item-history-header-grid" id="page_export"><div class="grid-cell grid-cell-left grid-cell-separator">' . __ ('Period') . '</div>';
$exportHeader = __ ('Period') . ',';

foreach ($periods as $period) {
	 
	 $header .= '<div class="grid-cell-right grid-cell-separator" data-cell>' . $period ['period'] . '</div>';
	 $exportHeader .= date ($merchant ['header_format'], $period ['date']) . ',,';
}

$exportHeader .= __ ('Total') . ',';
$header .= '<div class="grid-cell-right grid-cell-separator">' . __ ('Total') . '</div>';

?>

	 
	 <div class="grid-cell grid-cell-right grid-span-all">
		  
		  <a onclick="javascript:skip(<?= $prev ?>, 'item-history');" class="action-icon"><i class="far fa-arrow-left fa-large action-icon"></i></a>		
		  <a onclick="javascript:skip(<?= $next ?>, 'item-history');" class="action-icon"><i class="far fa-arrow-right fa-large action-icon"></i></a>		  
		  <a href="#" onclick="javascript:exporter('item-history', <?= ($len * 2 + 3) ?>, '<?= $exportHeader ?>')" class="action-icon"><i class="far fa-download fa-large action-icon"></i></a>
		  
	 </div>
	 
	 <?= $header ?>

</div>

<div class="item-history-grid" id="page_export">

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
	 
	 $links = false;
	 switch ($merchant ['subscription_level']) {

		  case 'pilot':
		  case 'standard':

				$links = true;
				break;
	 }
	 
	 foreach ($departments as $department) {
		  
		  $deptDesc = ' --- ';
		  if (isset ($department ['department_desc'])) {
				
				$deptDesc = $department ['department_desc'];
		  }
		  else {
		  }
		  
		  echo '<div class="grid-row-wrapper"><div class="grid-cell grid-cell-center grid-span-all even-cell">' . $deptDesc . '</div></div>';
		  
		  $row = 0;
		  foreach ($department ['items'] as $item) {
		  		
				
				$col = 0;
				$row ++;

				$html = '<div class="grid-row-wrapper">' .
						  '<div class="grid-cell grid-cell-left data-cell">' . strtoupper ($item ['item_desc']) . '</div>';
				
				foreach ($item ['totals'] as $itemData) {

					 if ($itemData ['amount'] < 0) {
						  $itemData ['quantity'] *= -1;
					 }

					 $html .= '<div class="grid-cell grid-cell-right data-cell">' . $itemData ['quantity'] . '</div>';
					 
					 $action = '';
					 if ($links && ($col < count ($periods)) && (intval ($itemData ['quantity']) != 0)) {

						  $url = 'tickets/index/item_search/' . $item ['item_desc'] .
									'/start_date/' . $periods [$col] ['start'] .
									'/end_date/' . $periods [$col] ['end'];

						  $action = 'onclick="controller (\'' .$url . '\', false)"';
						  
						  $html .= '<div class="grid-cell grid-cell-right data-cell">' .
									  '<a class="report-link"' . $action . '>' . $this->moneyFormat ($itemData ['amount']) .
									  '</a>' .
									  '</div>';
					 }
					 else {
						  
						  $html .= '<div class="grid-cell grid-cell-right data-cell">' .
									  $this->moneyFormat ($itemData ['amount']) .
									  '</div>';
					 }
					 
					 $totals [$col] ['amount'] += $itemData ['amount'];
					 $totals [$col] ['quantity'] += $itemData ['quantity'];
					 $grandTotals [$col] ['amount'] += $itemData ['amount'];
					 $grandTotals [$col] ['quantity'] += $itemData ['quantity'];
					 
					 $col ++;
				}

				$html .= '</div>';
				echo $html;
		  }

		  $html ='<div class="grid-row-wrapper">' .
					'<div class="grid-cell grid-cell-left header-cell data-cell">' . __ ('Total') . '</div>';

		  foreach ($totals as $total) {

				$html .= '<div class="grid-cell grid-cell-right header-cell data-cell">' . $total ['quantity'] . '</div>' .
							'<div class="grid-cell grid-cell-right header-cell data-cell">' . $this->moneyFormat ($total ['amount']) . '</a></div>';
		  }

		  $html .= '</div>';
		  
		  echo $html;
		  $totals = $totalsReset;

	 }
	 
	 $html = '<div class="grid-cell-left data-cell">' . __ ('grand total') . '</div>';
	 foreach ($grandTotals as $total) {

		  $html  .= '<div class="grid-cell-right data-cell">' . $total ['quantity'] . '</div>' .
						'<div class="grid-cell-right data-cell">' . $this->moneyFormat ($total ['amount']) . '</div>';
	 }
	 
	 echo $html;
	 
	 ?>
	 
</div>

<script>
 
function search (id) {

	  if ($('#' + id).val ().length > 0) {
			
			controller ('/item-history/index/' + id + '/' + $('#' + id).val (), true);
	  }
 }

 $('#start_date').datepicker ({
	  altFormat: 'yy-mm-dd',
	  altField: '#start_date'});

 function searchDate () {
	  
	  controller ('/item-history/index/start_date/' + $('#start_date').val (), true);
 }
 
</script>

<?= $this->Html->script ('exporter.js') ?>

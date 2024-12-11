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

?>

<style>

 .search-grid {

	  margin-top: 25px;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 120px 1fr 1fr 4fr;
	  grid-column-gap: 15px;
 }

</style> 

<div class="form-grid search-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
 
	 <div class="form-cell form-control-cell">
		  <input type="text" id="start_date" name="start_date" class="form-control datetimepicker-input start-date" autocomplete="off"  onchange="searchDate ()" placeholder="<?= __ ('Start Date') ?>"/>
	 </div>

	 <div>
		  <?php echo $this->Form->select ('ytd', $years, ['id' => 'ytd', 'onchange' => "ytd ()", 'class' => 'custom-dropdown', 'label' => false]); ?>
	 </div>
	 
</div>
<?= $this->Form->end () ?>

<div id="page_export" class="grid-container-<?= $len ?>">
	 
	 <div class="grid-cell grid-cell-right grid-span-all">
		  
		  <a onclick="javascript:skip(<?= $prev ?>, 'sales');" class="action-icon"><i class="far fa-arrow-left fa-large action-icon"></i></a>		
		  <a onclick="javascript:skip(<?= $next ?>, 'sales');" class="action-icon"><i class="far fa-arrow-right fa-large action-icon"></i></a>		  
		  <a onclick="javascript:exporter('sales', <?= $len + 2 ?>);" class="action-icon"><i class="far fa-download fa-large action-icon"></i></a>
		  
	 </div>

	 <?php
	 
	 $format = function ($type, $totalCol) {

		  switch ($type) {
					 
				case 'int': return $totalCol;
				case 'currency': return $this->moneyFormat ($totalCol);
		  }
		  return $this->moneyFormat ($totalCol);
	 };
	 
	 echo '<div class="grid-cell grid-cell-separator grid-cell-left data-cell"></div>';
	 
	 foreach ($periods as $period) {

		  echo '<div class="grid-cell grid-cell-separator grid-cell-right data-cell">'  .  $period ['period']  .  '</div>';
	 }
	 ?>
	 
	 <div class="grid-cell grid-cell-separator grid-cell-right data-cell"><?= __ ('Total') ?></div>
	 
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

		  $this->debug ("sales link... $link");
		  
		  echo '<div class="grid-row-wrapper">';
		  echo '<div class="grid-cell grid-cell-left data-cell">' . __ ($title) . '</div>';
		  
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

						  if ($link == 'period') {
								
								$a = '<a class="report-link" onclick="controller (\'tickets/index/period/' . $colDate . '\', false)">' . $val . '</a>';
						  }
						  else {
								
								$a = '<a class="report-link" onclick="controller (\'tickets/index/period/' . $colDate . '/' . $link . '\', false)">' . $val . '</a>';
						  }
					 }
					 
					 echo '<div class="grid-cell grid-cell-right data-cell">' . $a . '</div>';
				}
				else {
					 
					 echo '<div class="grid-cell grid-cell-right data-cell">' . $val .'</div>';
				}
				$col ++;
		  }
		  
		  echo '</div>';  // grid-row-wrapper
	 }
	 ?>

	 <div class="grid-cell-separator grid-span-all grid-cell-center"><?= __ ('Departments') ?></div>
	 <?php

	 foreach ($totals ['departments'] as $department) {
		  
		  echo '<div class="grid-cell grid-cell-left data-cell">' . strtoupper ($department ['department_desc']) . '</div>';
		  
		  $col = 0;
		  
		  foreach ($department ['totals'] as $total) {
				
				$colDate = '';						  
				if (isset ($periods [$col] ['start'])) {
					 
					 $colDate = strtotime ($periods [$col] ['start']);
				}

				if (($col < 7) && $total != 0) {
					 
					 switch ($merchant ['subscription_level']) {

						  case 'basic':
								echo '<div class="grid-cell grid-cell-right data-cell">' . $format ('currency', $total) . '</div>';
								break;

						  default:
		 						echo '<div class="grid-cell grid-cell-right data-cell">' .
									  '<a class="report-link" onclick="controller (\'tickets/index/period/' . $colDate . '/department_id/' . $department ['id'] . '\')">' .
									  $format ('currency', $total) . '</a></div>';
					 }
				}
				else {

					 echo '<div class="grid-cell grid-cell-right data-cell">' . $format ('currency', $total) . '</div>';
				}
				
				$col ++;
		  }
	 }
	 ?>
	 <div class="grid-cell grid-cell-separator grid-cell-left data-cell"><?= __ ('Totals') ?></div>
	 
	 <?php

	 foreach ($totals ['department_totals'] as $total) {
		  
		  $val = $format ('currency', $total);
		  echo '<div class="grid-cell grid-cell-separator grid-cell-right data-cell">' . $this->moneyFormat ($val) . '</div>';
	 }
	 ?>
</div>

<script>

 function search () {

	  let url = '/sales/index';
	  
	  if ($('#start_date').val ().length > 0) {

			url += '/start_date/' + $('#start_date').val () + ' 00:00:00';
	  }
	  else if ($('#ytd').val () > 0) {

			url += '/ytd/' + $('#ytd').val ();
	  }

	  controller (url, true);
 }
 
 function tickets (url) {

	  console.log (url);
	  window.open ('/pos-app/index/params' + url);
 }
 
 function salesExport () {

	  var csv = '';
	  var sep = '';
	  var col = 0;
	  
	  $('.data-cell').each (function () {				 
			
			if ($($(this) [0].firstChild).is ('a')) {
				 
				 csv += sep + $($($(this) [0].firstChild) [0].firstChild) [0].firstChild.data;
			}
			else {
				 
				 csv += sep + $(this) [0].firstChild.data;
			}
			
			sep = ',';
			col ++;
			if (col == (<?= ($len + 2) ?>)) {
				 
				 csv += '\n';
				 sep = '';
				 col = 0;
			}
	  });

	  csv += '\n';
 	  var e = document.createElement ('a');
	  e.setAttribute ('href', 'data:csv,' + encodeURIComponent (csv));
	  e.setAttribute ('download', 'sales-export.csv');
	  e.style.display = 'none';
	  document.body.appendChild (e);
	  e.click ();
	  document.body.removeChild (e);
 }
 
 function changeYear () {

	  document.getElementById ('sales').submit ();
 }
 
 $('#start_date').datepicker ({
	  altFormat: 'yy-mm-dd',
	  altField: '#start_date'});
 
 function searchDate () {
	  
	  controller ('/sales/index/start_date/' + $('#start_date').val (), true);
 }
 
 function ytd () {
	  
	  controller ('/sales/index/ytd/' + $('#ytd').val (), true);
 }
 
</script>

<?= $this->Html->script ('exporter.js') ?>

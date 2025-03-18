
<div class="row">
	 <div class="col-4">
		  <i class="bx bx-search-alt icon-lg" data-bs-toggle="modal" data-bs-target="#modal_search"></i>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
  				<th align="left"><?= __ ('#') ?></th>
				<th align="left"><?= __ ('Location') ?></th>
				<th><?= __ ('Complete time') ?></th>
				<th style="text-align: center;"><?= __ ('Type') ?></th>
				<th style="text-align: end;"><?= __ ('Items') ?></th>
				<th style="text-align: end;"><?= __ ('Discounts') ?></th>
				<th style="text-align: end;"><?= __ ('Tips') ?></th>
				<th style="text-align: end;"><?= __ ('Void items') ?></th>
				<th style="text-align: end;"><?= __ ('Tender type') ?></th>
				<th style="text-align: end;"><?= __ ('Total') ?></th>
		  </tr>
	 </thead>
	 <tbody>
		  <?php
		  
		  $row = 0;
		  foreach ($tickets as $ticket) {
				
				$action = 'onclick="detail (' . $ticket ['id'] . ')"';

				$tenderDesc = __ ('UNKNOWN');
				if ($ticket ['tender_desc'] !== null) {

					 $tenderDesc = __ (strtoupper ($ticket ['tender_desc']));
				}
				
				$flag = '';
				if ($ticket ['tag'] !== null) {

					 $flag = '<i class="fa fa-flag fa-small fa-warn"></i>';
				}

				$location = $merchant ['bu_names'] [$ticket ['business_unit_id']];
		  ?>

		  <tr role="button" data-bs-toggle="modal" data-bs-target="#ticket_modal" onclick="detail (<?= $ticket ['id'] ?>)">
				<td><?= $ticket ['ticket_no'] ?></td>
				<td><?= $location ?></td>
				<td><?= $ticket ['complete_time'] ?></td>
				<td align="center"><?= $ticketTypes [$ticket ['ticket_type']] ?></td>
				<td align="right"><?= $ticket ['item_count'] ?></td>
				<td align="right"><?= $this->moneyFormat ($ticket ['discounts']) ?></td>
				<td align="right"><?= $this->moneyFormat ($ticket ['tip']) ?></td>
				<td align="right"><?= $ticket ['void_items'] ?></td>
				<td align="right"><?= $tenderDesc ?></td>
				<td align="right"><?= $this->moneyFormat ($ticket ['total']) ?></td>
				
		  </tr>
	 <?php
	 }
	 ?>
	 </tbody>
</table>

<div class="row g-1 mt-3">
	 <div class="col-12 text-center">
		  <div id="pages" class="pagination"></div>
		  <nav class="pagination">

				<nav class="pagination">
					 
 					 <ul class="pagination">
						  <?= $this->Paginator->numbers () ?>
					 </ul>
				</nav>
 		  </nav>
	 </div>
</div>

<div class="modal fade" id="modal_search" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __ ('Search transactions') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
				<div id="search_content" class="modal-body">
					 
					 <form class="row g-1" method="post" action="/tickets">
						  
						  <div class="row mb-3">
								<label for="inputDate" class="col-sm-6 col-form-label">Start date</label>
								<div class="col-sm-6">
									 <input type="date" name="start_date" class="form-control">
								</div>
						  </div>
						  
						  <div class="row mb-3">
								<label for="inputDate" class="col-sm-6 col-form-label">End date</label>
								<div class="col-sm-6">
									 <input type="date" name="end_date" class="form-control">
								</div>
						  </div>
						  
						  <div class="row mb-3">
								<?=
								$this->Form->select ('tender_desc',
															$tenderTypes,
															['class' => 'form-select',
															 'label' => false]);
								?>
						  </div>
						  
						  <div class="row mb-3">
								<?=
								$this->Form->select ('ticket_type',
															$ticketTypes,
															['class' => 'form-select',
															 'label' => false]);
								?>
						  </div>

						  <div class="row g-1">
								<label for="gt" class="col-sm-6 col-form-label"><?= __ ('Amount greater than') ?></label>
								<div class="col-sm-6">
									 <input type="text" dir="rtl" class="form-control currency-format" name="gt" value="" placeholder="<?= __ ('currency_placeholder')?>">
								</div>
						  </div>

						  <div class="row g-1">
								<label for="lt" class="col-sm-6 col-form-label"><?= __ ('Amount less than') ?></label>
								<div class="col-sm-6">
									 <input type="text" dir="rtl" class="form-control currency-format" name="lt" value="" placeholder="<?= __ ('currency_placeholder')?>">
								</div>
						  </div>
						  
						  <div class="row g-1">
								<label for="item_desc" class="col-sm-6 col-form-label"><?= __ ('Transaction No') ?></label>
								<div class="col-sm-6">
									 <?=
									 $this->input ('ticket_no',
														['name' =>'ticket_no',
														 'value' => '',
														 'placeholder' => 'nnn',
														 'class' => 'form-control integer-format',
														 'dir' => 'rtl']);
									 ?>
								</div>
						  </div>
						  
						  <div class="row g-1">
								<label for="item_desc" class="col-sm-6 col-form-label"><?= __ ('Item description') ?></label>
								<div class="col-sm-6">
									 <?=
									 $this->input ('item_desc',
														['name' =>'item_desc',
														 'class' => 'form-control',
														 'value' => '']);
									 ?>
								</div>
						  </div>
						  
						  <div class="row g-1">
								<label for="item_desc" class="col-sm-6 col-form-label"><?= __ ('Last 4 of credit card') ?></label>
								<div class="col-sm-6">
									 <?=
									 $this->input ('last_4',
														['name' =>'last_4',
														 'class' => 'form-control',
														 'value' => '']);
									 ?>
								</div>
						  </div>
						  
						  <div class="row g-1">
								<div class="col-12 text-center">
									 <button type="submit" id="import_button" class="btn btn-primary"><?= __ ('Search') ?></button>
								</div>
						  </div>
					 </form>
				</div>
        </div>
    </div>
</div>

<div class="modal fade" id="ticket_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="item_desc" class="modal-title"><?= __ ('Ticket detail') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="ticket_content" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script>
 
 function detail (id) {
	  
	  $.ajax ({
			url: "/tickets/detail/" + id,
			type: "GET",
			success: function (data) {
				 
				 data = JSON.parse (data);
				 
				 $('#ticket_content').html (data.html)
			}
     });
 }

 $(".currency-format").mask ("#####0.00", {reverse: true});
 $(".integer-format").mask ("#######0");
 
</script>

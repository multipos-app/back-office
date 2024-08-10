<style>

 .invoice-controls-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr;
	  grid-column-gap: 0px;
 }
 .invoice-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 3fr 2fr 1fr 1fr 1fr;
	  grid-column-gap: 0px;
 }
</style>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __('Invoices') ?></legend>

	 <div class="container">
		  
		  <div class="invoice-grid">
				
				<div class="grid-cell grid-cell-left"><?php echo $this->Paginator->sort ('contact', __ ('Contact')); ?></div>
				<div class="grid-cell grid-cell-left"><?php echo $this->Paginator->sort ('email'); ?></div>
				<div class="grid-cell grid-cell-center"></div>
				<div class="grid-cell grid-cell-center"></div>
				<div class="grid-cell grid-cell-right"><?= __ ('Actions') ?></div>

				<?php

				$row = 0;
				foreach  ($invoices as $invoice) {

					 $rowClass = (($row % 2) == 0) ? ' even-cell' : '';
					 $row ++;
				?>
					 
					 <div class="grid-cell grid-cell-left<?= $rowClass ?>">
						  <?php echo $this->Html->link ($invoice ['contact'], ['controller' => 'Invoices',
																								  'action' => 'edit/'.$invoice ['id']]);?>
					 </div>
					 <div class="grid-cell grid-cell-left<?= $rowClass ?>">
						  <?php echo $invoice ['email']  ?>
					 </div>
					 
					 <div class="grid-cell grid-cell-center<?= $rowClass ?>">
					 </div>
					 
					 <div class="grid-cell grid-cell-center<?= $rowClass ?>">
						  <?php echo '<a href=tickets/index/invoice/invoices/' . $invoice ['id'] . '>' .  __ ('Invoices'); ?></a>
					 </div>
					 
					 <div class="grid-cell grid-cell-right<?= $rowClass ?>">
						  <?= $this->Form->postLink ($this->Html->image ('trash.png'), ['action' => 'delete',
																											 $invoice->id], ['escape' => false,
																																	'confirm' => __ ('Delete invoice').' '.$invoice ['invoice_desc'].'?']) ?>  
					 </div>
				<?php
				}
				
				?>
				
		  <div class="grid-cell top15">
				<a href="<?= $this->request->getAttribute ('webroot') ?>invoices/edit" class="btn btn-primary btn-block" target="_blank"><?= __ ('Create Invoice') ?></a>
		  </div>
		  
	 </div>
	 
	 <div class="container">
		  <div class="row">
				<div class="col-sm-12 text-center">
					 
					 <nav class="pagination">
						  <ul class="pagination">
								
								<?php
								echo $this->Paginator->prev (__('Previous'));
								echo $this->Paginator->numbers (['first' => 'First page']);
								echo $this->Paginator->next (__('Next'));
								?>
								
						  </ul>
					 </nav>
				</div>
		  </div>
	 </div>
	 

</fieldset>

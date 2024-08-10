<?= $this->Html->css (['Tickets/search']); ?>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i>
</div>

<div class="form-grid tickets-search-grid">
	 
	 <div class="form-cell form-control-cell">
		  <input type="text" id="start_time" name="start_time" class="form-control start-date" autocomplete="off" placeholder="<?= __ ('Start Date') ?>">
	 </div>
	 
	 <div class="form-cell form-control-cell">
		  <input type="text" id="end_time" name="end_time" class="form-control end-date" autocomplete="off" placeholder="<?= __ ('End Date') ?>">
	 </div>
	 

	 <div class="form-cell form-control-cell">
		  <?php
		  
		  echo $this->Form->select ('tender_type',
											 $tenderType,
											 ['id' => 'tender_type',
											  'onchange' => 'addSearch (this)',
											  'class' => 'custom-dropdown',
											  'label' => false]);
		  ?>
	 </div>
	 <div></div>
	 
	 <div class="form-cell form-control-cell">
		  <?php
		  
		  echo $this->Form->select ('ticket_type',
											 $ticketType,
											 ['id' => 'ticket_type',
											  'onchange' => 'addSearch (this)',
											  'class' => 'custom-dropdown',
											  'label' => false]);
		  ?>
	 </div>
	 <div></div>
	 
	 <div class="form-cell form-desc-cell"><?= __('Amount greater than') ?></div>
	 <div class="form-cell form-control-cell">
		  <?= $this->Form->control ('gt', ['class' => 'form-control currency-format ticket-amount', 'label' => false, 'placeholder' => __ ('currency_placeholder')]) ?>
	 </div>
	 
	 <div class="form-cell form-desc-cell"><?= __('Amount Less than') ?></div>
	 <div class="form-cell form-control-cell">
		  <?= $this->Form->control ('lt', ['class' => 'form-control currency-format ticket-amount', 'label' => false, 'placeholder' => __ ('currency_placeholder')]) ?>
	 </div>
	 
	 <div class="form-cell form-control-cell">
		  <?= $this->Form->control ('ticket_no', ['id' => 'ticket_no', 'class' => 'form-control', 'label' => false, 'placeholder' => __ ('Transaction Number')]) ?>
	 </div>
	 <div></div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="item_search" name="item_search" class="form-control" placeholder="<?= __ ('Item description') ?>"></input>
	 </div>
	 <div></div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="card_search" name="card_search" class="form-control" placeholder="<?= __ ('Cardholer name or last 4 digits') ?>"></input>
	 </div>
	 <div></div>
</div>

<div class="form-submit-grid">
	 <div class="form-cell">
		  <button onclick="javascript:search ()" class="btn btn-primary" ><?= __ ('Search') ?></button>
	 </div>
</div>
	 
</div>

<?= $this->Html->script (['Tickets/search']); ?>



<div class="row g-1 m-3">
	 <div class="col-4">
		  <?=
		  $this->Form->select ('add_discount',
								 $addonTypes,
								 ['id' => 'add_discount',
								  'data-bs-toggle' => 'modal',
								  'data-bs-target' => '#discount_modal',
								  'value' => '',
								  'class' => 'form-select',
								  'label' => false,
								  'required' => 'required'])
		  ?>
	 </div>
</div>

<table class="table table-hover g-1 m-3">
	 <thead>
		  <tr>
				<th><?= __ ('Description'); ?></th>
				<th><?= __ ('Print Description'); ?></th>
				<th><?= __ ('Start Date'); ?></th>
				<th><?= __ ('End Date'); ?></th>
		  </tr>
	 </thead>
	 <tbody>
		  
		  <?php
		  
		  foreach  ($addons as $addon) {

				foreach (['start_time', 'end_time'] as $date) {
					 
					 $addon [$date] = strlen ($addon [$date]) > 0 ? $addon [$date] : '---';
				}
				
				$action = 'onclick="openForm (' . $addon ['id'] . ',\'/discounts/' . $addon ['addon_type'] . '/' . $addon ['id'] . '\')"';
		  ?>

		  <tr>
				<td><?= $addon ['description'] ?></td>
				<td><?= $addon ['print_description'] ?></td>
				<td> <?= $addon ['start_time'] ?> </td>
				<td> <?= $addon ['end_time'] ?> </td>
<?php
}
?>
		  </tr>
	 </tbody>
</table>

<div class="modal fade" id="discount_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="discount_desc" class="modal-title"><?= __ ('Discount') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal_content" class="modal-body">
            </div>
        </div>
    </div>
</div>

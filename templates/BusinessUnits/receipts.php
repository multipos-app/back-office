
<table class="table table-hover">
	 <thead>
		  <tr>
				<th><?= __ ('Store') ?></th>
				<th><?= __ ('Address') ?></th>
				<th><?= __ ('City') ?></th>
				<th><?= __ ('Phone') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>
		  
		  <?php

		  $i = 0;
		  foreach ($locations as $location) {
				
		  ?>
				<tr onclick="receipt (<?= $location ['id'] ?>,<?= $i ?>)">
					 <td><?= $location ['business_name'] ?></td>
					 <td><?= $location ['addr_1'] ?></td>
					 <td><?= $location ['city'] ?></td>
					 <td><span class="phone-format"> <?= $location ['phone_1'] ?></span></td>

				</tr>

				<?php
				
				$i ++;
				}
				?>
	 </tbody>
</table>

<script>

 function receipt (buID, buIndex) {
	  
	  window.location = `/business-units/receipt/${buID}/${buIndex}`;
 }
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});

</script>

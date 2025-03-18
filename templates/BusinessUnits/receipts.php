
<table class="table table-hover">
	 <thead>
		  <tr>
				<th></th>
				<th><?= __ ('Store') ?></th>
				<th><?= __ ('Address') ?></th>
				<th><?= __ ('Phone') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>
	 
	 <?php
	 
	 foreach ($locations as $location) {
		  
	 ?>
		  <tr>
				<td><?= $location ['business_name'] ?></td>
				<td><?= $location ['addr_1'] ?></td>
				<td><?= $location ['phone_1'] ?></td>

		  </tr>

	 <?php
	 }
	 ?>
	 </tbody>
</table>

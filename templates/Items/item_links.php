<?php
if (count ($item ['item_links'])) {

	 foreach ($item ['item_links'] as $link) {
?>
	 <table class="table">
		  <tbody>
				<tr>
					 <td>
						  <?= $link ['item_desc']?>
					 </td>
					 <td align="right">
						  <i class="bx bxs-trash icon-lg"></i>
					 </td>
				</tr>
		  </tbody>
	 </table>
<?php
}
}
?>

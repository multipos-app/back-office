<style>
 .menu-layout-1-grid {

     display: grid;
     width: 100%;
     grid-template-rows: 1.5fr 1fr;
     grid-template-columns: 1fr 1fr;
	  grid-row-gap: 10px;
	  grid-column-gap: 10px;
	  margin-top: 25px;
 }

</style>

<?php

$this->debug ($posConfig ['config'] ['root_layout']);

$grid = 'menu-layout-1-grid';
switch ($posConfig ['config'] ['root_layout']) {
		  
	 case 'layout_1':
		  
		  $grid = 'menu-layout-1-grid';
		  break;
}

?>

<div class="<?= $grid ?>">
	 
	 <div class="grid-cell grid-cell-center">
		  
		  Ticket
				
	 </div>

	 <?php
	 foreach ($posConfig ['config'] ['pos_menus'] as $name => $menu) {
		  
		  $this->debug ($name);

	 ?>
		  <div class="grid-cell grid-cell-center">

				<?= $name ?>
				
		  </div>
	 <?php
	 }
	 ?>

</div>




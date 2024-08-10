<?= $this->Html->css ("menu.css") ?>

<style>
 
 .main-grid {

     display: grid;
     width: 100%;
     grid-template-columns: 2fr 1fr;
	  grid-column-gap: 3px;
	  grid-row-gap: 3px;
 }
 
 .default-pos-layout {

     display: grid;
     width: 100%;
     grid-template-rows: 2fr 1fr;
     grid-template-columns: 1fr 1fr;
	  grid-column-gap: 3px;
	  grid-row-gap: 3px;
 }

 .ticket {
	  grid-row: 1 / span 2;
 }
 
 .btn-display {
	  font-size: 11px;
	  font-weight: 600;
	  color: #fff;
     border-radius: 2px;
     display: inline-block;
	  text-align: left;
     padding:3px 3px 3px 3px;
	  word-wrap: break-word;
	  white-space:normal;
 }

</style>

<fieldset class="maintenance-border">
	 
	 <legend class="maintenance-border"><?= __ ('Select Menu') ?></legend>

	 <div class="main-grid">
		  
		  <div class="grid-cell grid-cell-center">
				
				<div class="default-pos-layout">
					 
					 <div class="grid-cell grid-cell-center ticket">
						  <img src="<?= $this->request->getAttribute ('webroot') ?>img/ticket-display.png" alt="">
					 </div>
					 
					 <?php

					 $menu = $posConfig ['config'] [$posConfig ['config'] ['main_menu'] ['horizontal_menus'] [0] ['name']];

					 $html = '<div id="main_menu" class="menu-grid-display-cells-' . $menu ['width']. '" onclick="edit(\'main_menu\')">';

					 foreach ($menu ['buttons'] as $button) {

						  $html .= '<div class="btn btn-display grid-cell-left ' . $button ['color']. '">' . $button ['text']. '</div>';
					 }

					 $html .= '</div>';

					 echo $html;

					 ?>
					 
					 <?php
					 
					 $menu = $posConfig ['config'] [$posConfig ['config'] ['tender_menu'] ['horizontal_menus'] [0] ['name']];
					 
					 $html = '<div id="tender_menu" class="menu-grid-display-cells-' . $menu ['width']. '" onclick="edit(\'tender_menu\')">';
					 
					 foreach ($menu ['buttons'] as $button) {
						  
						  $html .= '<div class="btn btn-display grid-cell-left ' . $button ['color']. '">' . $button ['text']. '</div>';
					 }
					 
					 $html .= '</div>';
					 
					 echo $html;
					 
					 ?>
					 
				</div>
		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				<h3 top30>
					 <?= __ ('Select a menu to begin')?>
				 </h3>
		  </div>
	 </div>
</fieldset>

<script>
	 
 $("#main_menu").hover (function () {

	  $(this).addClass ('menu-highlight');
	  
 }, function () {
	  
	  $(this).removeClass ('menu-highlight');
 });
 
 $("#tender_menu").hover (function () {

	  $(this).addClass ('menu-highlight');
	  
 }, function () {
	  
	  $(this).removeClass ('menu-highlight');
 });

 function edit (menu) {

	  console.log ('edit... ' + menu);
	  window.location = '/pos-configs/menus/<?= $posConfig ['id'] ?>/' + menu;
 }

</script>

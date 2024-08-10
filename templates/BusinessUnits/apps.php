<style>

 .apps-grid {

     display: grid;
     width: 50%;
     grid-template-rows: auto;
     grid-template-columns: repeat(3, 1fr);
	  grid-column-gap: 0px;
	  grid-row-gap: 10px;
	  margin-top: 25px;
 }
 
</style>

<div class="apps-grid">

	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Version'); ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Date'); ?></div>
	 <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Download'); ?></div>
	 
	 <?php 

	 $row = 0;
	 foreach (scandir ('/multipos/apps', SCANDIR_SORT_DESCENDING) as $file) {
		  
		  $rowClass = (($row % 2) == 0) ? ' even-cell' : '';
		  $row ++;
		  
		  if (strpos ($file, '.apk') > 0) {
				
            $ver = substr ($file, 0, strpos ($file, '.'));
            $d = date ("F j, Y, g:i a e", filemtime ('/multipos/apps/' . $file));
				$link = '<a href=https://vr-apks.posappliance.com/' . $file . '><i class="far fa-download fa-large" ></i></a>';
	 ?>
		  
		  <div class="grid-cell grid-cell-left <?= $rowClass ?>"><?= $ver ?></div>
		  <div class="grid-cell grid-cell-left <?= $rowClass ?>"><?= $d ?></div>
		  <div class="grid-cell grid-cell-right <?= $rowClass ?>">
				<a href="/apps/<?= $file?>" download="<?= $file ?>" '<i class="far fa-download fa-large"></i></a>
		  </div>

		  
	 <?php
	 }
	 }
	 ?>
</div>

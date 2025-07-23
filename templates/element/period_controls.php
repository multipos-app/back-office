
<style>

.search-grid {
	 
	 display: grid;
	 width: 100%;
	 grid-template-columns: 8fr .2fr .2fr 1fr;
	 grid-column-gap: 1em;
 }
 
</style>

<div class="search-grid">
	 <div></div>
	 <div>
		  <i class="bx bx-left-arrow-alt icon-lg" "data-bs-toggle="tooltip" title="<?= __ ('Previous 7 days') ?>" onclick="jump (<?= $prev ?>, '<?= $url ?>')"></i> 
	 </div>
	 
	 <div>
		  <i class="bx bx-right-arrow-alt icon-lg" "data-bs-toggle="tooltip" title="<?= __ ('Next 7 days') ?>" onclick="jump (<?= $prev ?>, '<?= $url ?>')"></i> 
	 </div>
	 
	 <div>
		  <input type="date" id="date_search" class="form-control">
	 </div>
</div>

<script>

 function jump (start, action) {
		  
	  let url = `${action}/index/start_date/${start}`;
	  console.log (`ju.. ${url}`);
	  window.location = url;
 }

 $('#date_search').change (function () {
	  
	  console.log ($('#date_search').val ());

	  if ($('#date_search').val ().length > 0) {

			let url = `<?= $url ?>/index/start_date/${$('#date_search').val ()}`;
			window.location = url;
	  }
});

</script>

<style>
 
 .file-name {
	  padding-left: 10px;
 }
 
 input[type="file"] {
     display: none;
 }

 .custom-file-upload {
     border: 1px solid #ccc;
	  width:100%
     display: inline-block;
     padding: 10px;
     cursor: pointer;
 }
 
</style>

<fieldset class="maintenance-border">
	 
	 <legend class="maintenance-border">Import Menus</legend>
	 
	 <div class="container">

		  <?= $this->Form->create (null, ['type' => 'file']) ?>
				
		  <div class="row file-picker top10">
				
				<div class="col-sm-12 text-left">
					 <label class="custom-file-upload">
						  <input type="file" id="upload_file" class="btn btn-primary btn-block" name="upload_file" onchange="getFile( this);">
						  <?= __ ('Select file to import') ?>
					 </label>
					 <span id="file_name" class="file-name"></span>
				</div>
					 
		  </div>
		  
		  <div class="row top30">
				<div class="col-sm-2 text-left">
					 <input type="submit" id="import-button" value="<?= __ ('Import')?>" class="btn btn-secondary btn-block">
				</div>
				
		  </div>
		  
		  </form>
		  
		  <div class="top30"><?= $results ?></div>
	 </div>
	 
</fieldset>

<script>

 function getFile (file) {
	  
     var file = file.files [0];  
     $('#file_name').html (file.name);
 	  $('#import-button').removeClass ('btn-secondary');
 	  $('#import-button').addClass ('btn-success');
}
 
</script>

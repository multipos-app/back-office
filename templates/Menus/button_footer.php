<div class="form-submit-grid">
	 
	 <div>
		  <button type="button" class="btn btn-success" onclick="buttonClose ()"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div>
		  <button type="button" class="btn btn-warning" onclick="del ()"><?= __ ('Clear') ?></button>
	 </div>
	 
</div>

<script>
 
 function del () {
	  
 	  console.log ('del... ' + container + ' ' + menu + ' ' + pos);
	  posConfig.config.pos_menus [container] ['horizontal_menus'] [menu] ['buttons'] [pos] = {"text": "", "class": "Null", "color": "#fff"};
	  render (container);
	  dirty (true);
 	  $('#button_container').toggleClass ('on');
}
 
</script>

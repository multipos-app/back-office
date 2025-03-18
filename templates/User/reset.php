<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	 
	 <div class="panel panel-info" style="border: solid 1px black;">
		  
        <div class="panel-heading" style="color:#fff; background-color:#020202;">
				
            <div class="panel-title">Change password</div>
				
        </div>
		  
		  <div style="padding-top:30px" class="panel-body">
				
				<div class="form-group">
					 
					 <div style="margin-bottom: 25px" class="input-group">
						  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						  <input id="pw1" name="pw1" type="password" class="form-control" value="" placeholder="Password">                                        
					 </div>
					 
					 <div style="margin-bottom: 25px" class="input-group">
						  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						  <input id="pw2" name="pw2" type="password" class="form-control" value="" placeholder="Re-enter password">                                    
					 </div>
					 
					 <div style="margin-top:10px" class="form-group">
						  
						  <div class="col-sm-12 controls">
								<button class="btn btn-success" onclick="resetPW ()">Submit</button>
						  </div>
					 </div>
				</div>
		  </div>
	 </div> 
</div>

<script>

 var resetID = '<?= $resetID ?>';

 function resetPW () {
	  
	  if (($('#pw1').val ().length == 0) ||
			($('#pw2').val ().length == 0)) {
			
			alert ('<?= __ ('Password required')?>');
			return;
	  }

	  if ($('#pw1').val () != $('#pw2').val ()) {
			
			alert ('<?= __ ('Passwords must match')?>');
			return;
	  }
	  
	  
	  let url = '/user/reset/' + resetID;
	  	  
	  $.ajax ({type: "POST",
				  url: url,
				  data: {"pw": $('#pw1').val ()},
				  success: function (data) {

						console.log (data);
						data = JSON.parse (data);

						if (data.status == 0) {

							 window.location = '/';
						}
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 }
 
</script>

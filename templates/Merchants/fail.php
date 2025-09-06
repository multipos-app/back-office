

<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	 
	 <div class="panel panel-info" style="border: solid 1px black;">
		  
        <div class="panel-heading" style="color:#fff; background-color:#020202;">
				
            <div class="panel-title">Login status</div>
				
        </div>
		  
		  <div style="padding-top:30px" class="panel-body">
				
				<div class="form-group form-horizontal">
					 
					 <div style="margin-top:10px;margin-left:5px;" class="form-group">
						  <h4><?= __ ('Bad user name or password') ?></h4>
					 </div>

						  <div style="margin-top:10px" class="form-group">
								
								<div class="col-sm-12 controls">
									 <button type="submit" id="btn-login" class="btn btn-success" onclick="window.location = '/'"><?= __ ('Try again') ?></button>
								</div>
						  </div>
						  
				</div>
		  </div>
	 </div> 
</div>

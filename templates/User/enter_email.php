
<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	 
	 <div class="panel panel-info" style="border: solid 1px black;">
		  
        <div class="panel-heading" style="color:#fff; background-color:#020202;">
				
            <div class="panel-title">Enter email</div>
				
        </div>
		  
		  <div style="padding-top:30px" class="panel-body">
				
				<div class="form-group">
					 
					 <form class="form-horizontal" role="form" action="<?= $this->request->getAttribute ('webroot') ?>user/check-email/" method="post">
						  
						  <div style="margin-bottom: 25px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="email" name="email" type="text" class="form-control" value="" placeholder="email">                                        
						  </div>
						  
						  <div style="margin-top:10px" class="form-group">
								
								<div class="col-sm-12 controls">
									 <button type="submit" id="btn-login" class="btn btn-success">Reset</button>
								</div>
								
						  </div>

					 </form>
				</div>
		  </div>
	 </div> 
</div>

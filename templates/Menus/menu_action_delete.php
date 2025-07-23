 
</style>

<form id="menu_actions" method="post" action="/menus/action/menu_action_delete/<?= $configID ?>/<?= $menuName ?>/<?= $menuIndex ?>">
	 
	 <input type="hidden" name="action" value="menu_action_delete">
	 
	 <div class="row mt-3">

		  <div class="row mt-3">
				<div class="col-sm-12 text-left">
					 <?= __ ('Delete menu')?>&nbsp;"<?= $name ?>"?
				</div>
		  </div>
	 </div>
		  
	 <div class="row mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" id="menu_delete" class="btn btn-danger"><?= __ ('Delete') ?></button>
		  </div>
	 </div>
</div>


container = '';
menu = 0;
pos = 0;
isDirty = false;
containers = [];

/**
 *
 * select and render a menu
 *
 */
function select (c) {
	 
	 container = c;
	 menu = $('#' + container + '_select').val ();
	 console.log ('select... ' + container + ' ' + menu);
	 render (c);
}

/**
 *
 * 
 *
 */

function render (c) {

	 container = c;
	 
	 if (typeof posConfig.config ['pos_menus'] [container] ['horizontal_menus'] === "undefined") {

		  posConfig.config ['pos_menus'] [container] ['horizontal_menus'] = Array ();
	 }
	 
	 let menus = posConfig.config ['pos_menus'] [container] ['horizontal_menus'];

	 let name = '';
	 let height = 0;
	 let width = 0;
	 
	 if (posConfig.config ['pos_menus'] [container] ['horizontal_menus'].length > 0) {
		  
		  console.log ('render... ' + container + ' ' + menu);
		  
		  let buttons = menus [menu] ['buttons'];

		  name = menus [menu] ['name'];
		  width = menus [menu] ['width'];
		  height = parseInt (menus [menu] ['buttons'].length / width);
		  
		  let html = '<div class="button-grid button-' + width + '-grid">';
		  		  
		  for (i = 0; i < buttons.length; i ++) {

				b = buttons [i];
				let text = '';
				if (b.text != null) {

					 text = b.text.toUpperCase ();
				}

				// handle legacy colors
				
				let backgroundStyle = '';
				let backgroundClass = '" ';

				if (b.color != null) {
					 
					 console.log (b);
					 
					 if (b.color.startsWith ('color')) {
						  
						  backgroundClass = ' ' + b.color + '"';
					 }
					 else {
						  
						  backgroundStyle = 'style="color:white;background: ' + b.color + ';"';
					 }
				}

				html +=
					 '<div id="' + container + '_' + menu + '_' + i + '" ' +
					 'class="grid-cell grid-cell-left button' + backgroundClass +
					 'onclick="button (\'' + container + '\', ' + menu + ', ' + i + ')" ' +
					 backgroundStyle +
					 '>' +
					 text +
					 '</div>';
		  }
		  
		  html += '</div>';
		  
		  $('#' + container).html (html);
	 }
	 
	 html =
		  '<select id="' + container + '_select" onchange="select (\'' + container + '\')" class="custom-dropdown">' +
		  '<option disabled>Select menu</option>';
	 
	 for (i = 0; i < menus.length; i ++) {
		  
		  let name = menus [i].name;
		  html += '<option value=' + i + '>' + name + '</option>';
	 }
	 
	 html += '</select>';
	 
	 $('#' + container + '_picker').html (html);
	 $('#' + container + '_select').val (menu);
	 $('#' + container + '_name').val (name);
	 $('#' + container + '_rows').val (height);
	 $('#' + container + '_cols').val (width);

	 console.log ('render... ' + container + ' ' + menu + ' ' + name + ' ' + height + " " + width);
}

/**
 *
 * Menu level actions
 *
 */

function actions (c) {

	 console.log (posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu]);

	 switch ($('#' + c + '_action').val ()) {

	 case 'add_before':
	 case 'add_after':
		  
		  addMenu (c, menu, $('#' + c + '_action').val ());
		  break;
		  
	 case 'rename':
		  
		  posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['name'] = $('#' + c + '_name').val ().toUpperCase ();
		  break;

	 case 'delete':

		  console.log ('delete... ' + c + ' ' + menu);
		  if (confirm ('Are you sure you want to delete ' + posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['name'])) {
				
				posConfig.config ['pos_menus'] [c] ['horizontal_menus'].splice (menu, 1);
				menu = 0;
		  }
		  break;
	 }

	 render (c);
	 dirty (true);
	 $('#' + c + '_action').val (null);
}

/**
 *
 * Add a new menu 
 *
 */

function addMenu (c, m, where) {

	 let insert = 0;
	 
	 switch (where) {

	 case 'add_before':

		  insert = m;
		  break;

	 case 'add_after':
		  
		  insert = m + 1;
		  break;
	 }

	 console.log ('add menu... ' + c + ' ' + m + ' ' + where + ' ' + insert);
	 
	 container = c
	 buttons = Array ();
	 for (i=0; i < (parseInt ($('#' + container + '_rows').val ()) * parseInt ($('#' + container + '_cols').val ())); i ++) {

		  buttons.push ({"text": "", "class": "Null", "color": "#fff"});
	 }

	 posConfig.config ['pos_menus'] [container] ['horizontal_menus'].splice (insert,
																									 0,
																									 {"type": "functions",
																									  "name": $('#' + container + '_name').val ().toUpperCase (),
																									  "width": parseInt ($('#' + container + '_cols').val ()),
																									  "buttons": buttons});
 	 console.log ('add menu... ' + container);
 	 console.log (posConfig.config ['pos_menus'] [container]);

	 menu = 0;
	 pos = 0;
	 dirty (true);
}

/**
 *
 * Edit/Add a button 
 *
 */

function button (c, m, p) {

	 console.log ('button... ' + c + ' ' + m + ' ' + p);
	 
	 let url = '/menus/button';

	 container = c;
	 menu = m;
	 pos = p;
	 containers [c] = [m, p];
	 
	 data = posConfig.config.pos_menus [c] ['horizontal_menus'] [m] ['buttons'] [p];
	 data ['pos_config_id'] = posConfig ['id'];
	 data ['container'] = c;
	 data ['menu'] = m;
	 data ['pos'] = p;
	 	 
	 if (!$('#button_container').hasClass ('on')) {
	  
		  $('#button_container').toggleClass ('on');
	 }
	 
	 $.ajax ({type: "POST",
				 url: url,
				 data: data,
				 success: function (data) {

					  data = JSON.parse (data);
					  console.log (data);

					  $('#button_container').html (data.html);
				 }
				});
	 
	 dirty (true);
}

function changeStyle (c) {
	 
	 posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['style'] = $('#style').val ();
	 dirty (true);
}

/**
 *
 * mark as dirty
 *
 */

function dirty (d) {

	 isDirty = d;
	 if (isDirty) {
		  
		  $('#menu_update').removeClass ('btn-secondary');
		  $('#menu_update').addClass ('btn-success');
	 }
}

/**
 *
 * save the current menu 
 *
 */

$('#menu_update').on ('click', function (e){

 	 let url = '/menus/update/' + posConfig.id;

	 console.log ('update menu...');
	 
	 $.ajax ({type: "POST",
				 url: url,
				 data: posConfig,
				 success: function (data) {
					  
					  controller ('pos-configs', false);
				 },
				 fail: function () {
					  
					  console.log ('fail...');
				 },
				 always: function () {
					  
					  console.log ('always...');
				 }
				});
});

/**
 *
 * render menus...
 *
 */

$.each (posConfig.config ['pos_menus'], function (container, menus) {

	 containers [container] = [0, 0];
 	 render (container);
});


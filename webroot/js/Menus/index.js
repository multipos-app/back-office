/**
 * Copyright (C) 2023 multiPos, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

var container = '';       // the screen menu areas
var menu = 0;             // index of the menu in the container
var pos = 0;              // index of the button in the menu
var isDirty = false;      // something has changed
var containers = [];      // list of menu areas
var control = null;       // control associated with the current button
var buttonColor = null;   // color of the button
var buttonText = null;    // text of the button
var buttonID = null;      // id of button
var colorIndex = -1;      // selected color

/**
 *
 * select and render a menu
 *
 */
function select (c) {
	 
	 container = c;
	 menu = $('#' + container + '_select').val ();	 
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
		  
		  let buttons = menus [menu] ['buttons'];
		  
		  menus [menu] ['size'] = buttons.length;
		  
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

				if (b.class == 'Null') {
					 
					 html +=
						  '<div id="' + container + '_' + menu + '_' + i + '" ' +
						  'class="grid-cell grid-cell-center button" ' +
						  'onclick="edit (\'' + container + '\', ' + menu + ', ' + i + ')" ' +
						  'style="color:white;background: #eeeeee;">' +
						  '<i class="fa fa-plus fa-large fa-center"></i>' +
						  '</div>';
				}
				else {
					 
					 html +=
						  '<div id="' + container + '_' + menu + '_' + i + '" ' +
						  'class="grid-cell grid-cell-left button" ' +
						  'onclick="edit (\'' + container + '\', ' + menu + ', ' + i + ')" ' +
						  'style="color:white;background: ' + b.color + ';"' +
						  '>' +
						  text +
						  '</div>';
				}				
		  }
		  
		  html += '</div>';
		  
		  $('#' + container).html (html);
	 }
	 
	 html =
		  '<select id="' + container + '_select" onchange="select (\'' + container + '\')" class="custom-dropdown menu-picker">' +
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
}

/**
 *
 * Menu level actions
 *
 */

function actions (c) {


	 switch ($('#' + c + '_action').val ()) {

	 case 'add_before':
	 case 'add_after':
		  
		  addMenu (c, menu, $('#' + c + '_action').val ());
		  break;
		  
	 case 'rename':
		  
		  posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['name'] = $('#' + c + '_name').val ().toUpperCase ();
		  break;

	 case 'resize':

		  curSize = posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['size'];
		  newSize = $('#' + c + '_rows').val () * $('#' + c + '_cols').val ();
		  
		  if (newSize < curSize) {

				if (!confirm ('New size may remove some existing buttons.')) {
					 
					 return;
				}
		  }
		  
		  if (newSize < curSize) {
				
				posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['buttons'].length = newSize;
		  }
		  else {
				
	 			for (i = 0; i < (newSize - curSize); i ++) {
					 
					 let b = {"class": "Null", "color": "#eeeeee", "text": ""};
					 
					 posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['buttons'].push (b);
				}
		  }
		  
		  render (c);
		  
		  break;
		  
	 case 'delete':

		  if (confirm ('Are you sure you want to delete ' + posConfig.config ['pos_menus'] [c] ['horizontal_menus'] [menu] ['name'])) {
				
				posConfig.config ['pos_menus'] [c] ['horizontal_menus'].splice (menu, 1);
				menu = 0;
		  }
		  break;
	 }

	 dirty (true);
	 render (c);
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
	 
	 container = c
	 buttons = Array ();
	 for (i=0; i < (parseInt ($('#' + container + '_rows').val ()) * parseInt ($('#' + container + '_cols').val ())); i ++) {

		  buttons.push ({"text": "", "class": "Null", "color": "#eeeeee"});
	 }

	 posConfig.config ['pos_menus'] [container] ['horizontal_menus'].splice (insert,
																									 0,
																									 {"type": "functions",
																									  "color": "#eeeeee",
																									  "name": $('#' + container + '_name').val ().toUpperCase (),
																									  "width": parseInt ($('#' + container + '_cols').val ()),
																									  "buttons": buttons});
	 menu = 0;
	 pos = 0;
	 dirty (true);
}

/**
 *
 * Edit/Add a button 
 *
 */

function edit (c, m, p) {
	 
	 let url = '/menus/button';

	 container = c;
	 menu = m;
	 pos = p;
	 containers [c] = [m, p];
	 buttonID = '#' + container + '_' + menu + '_' + pos;

	 data = posConfig.config.pos_menus [c] ['horizontal_menus'] [m] ['buttons'] [p];
	 
	 if (data.class == 'Null') {
		  
		  $(buttonID).css ("background-color", '#999999');
		  $(buttonID).html ('');
	 }
	 
	 data ['pos_config_id'] = posConfig ['id'];
	 data ['container'] = c;
	 data ['menu'] = m;
	 data ['pos'] = p;
	 data ['exists'] = true;
	 
	 if (!$('#button_container').hasClass ('on')) {
		  
		  $('#button_container').toggleClass ('on');
	 }

	 $.ajax ({type: "POST",
				 url: url,
				 data: data,
				 success: function (data) {

					  data = JSON.parse (data);
					  $('#button_container').html (data.html);
				 }
				});
	 
	 dirty (true);
}

/**
 *
 * update the edit and button text
 *
 */

function updateName (container) {

	 posConfig.config.pos_menus [container] ['horizontal_menus'] [menu] ['name'] = $('#' + container + '_name').val ().toUpperCase ();
	 dirty (true);
}

/**
 *
 * update the edit and button text
 *
 */

function buttonDesc (text) {

	 $('#button_desc').val (text);
	 posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos] ['text'] = text;
	 posConfig.config.pos_menus [container] ['horizontal_menus'] [menu].buttons [pos] ['color'] = '#999999';
	 render (container);
}

/**
 *
 * close the button edit dialog
 *
 */

function buttonClose () {
	 
	 $('#button_container').html ('');
	 $('#button_container').toggleClass ('on');
}

/**
 *
 * clear button
 *
 */

function buttonClear () {
	 
	 posConfig.config.pos_menus [container] ['horizontal_menus'] [menu] ['buttons'] [pos] = {"text": "", "class": "Null"};
	 buttonClose ();
	 render (container);
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

	 $.ajax ({type: "POST",
				 url: '/menus/update/' + posConfig.id,
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


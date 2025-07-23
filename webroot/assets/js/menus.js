
const X_KEY = 88;
const C_KEY = 67;
const V_KEY = 86;
const ESC_KEY = 27;
empty = {text:"", class:"Null", color:"transparent"};
var selected = -1;
var copy = null;

const menus = {

	 modified: false,
	 modal: new bootstrap.Modal (document.getElementById ("menus_modal"), {}),
	 
	 setModal: function (title, content) {
		  
		  if (!$('#menus_modal').hasClass('show')) {
				
				menus.modal.show ();
		  }

		  $('#modal_title').html (title);
		  $('#modal_content').html (content);
	 },
		  
	 /**
	  *
	  * send current menu to server for update
	  *
	  **/
	 
	 update: function () {
		  		  
		  let data = {config_id: configID,
						  menu_name: menuName,
						  menu_index: menuIndex,
						  menu: curr};
	 	  
		  $.ajax ({url: "/menus/update/",
					  type: 'POST',
					  data: data,
					  success: function (data) {
							
							data = JSON.parse (data);
							window.location = multipos.pathname;
					  }
					 });
	 },
	 
	 /**
	  *
	  * draw the menu buttons
	  *
	  **/
	 
	 render: function (menu) {
		  
		  let pos = 0;
		  let width = curr.width;
		  let html = '<table class="table button-menu"><tbody>';
		  let row = '';
		  		  
		  $(menu).each (function (i, b) {
				
				if ((i % width) == 0) {
					 
					 html += '<tr>';
				}
				
				let text = '';
				let bclass = 'empty-button';
				if ($.inArray ('text', b)) {

					 text = b.text;
					 bclass = 'menu-button';
				}
				
				let color = $.inArray ('color', b) ? b.color : '#fff';

				if (b.class == 'Null') {

					 html +=
						  `<td id="b_${pos}" ` +
						  `class="${bclass}"` +
						  `style="background-color: ${color};" ` +
						  `onclick="menus.select (${pos})" ` +
						  `ondblclick="menus.create (${pos})">` +
						  text +
						  '</td>';
				}
				else {
				html +=
						  `<td id="b_${pos}" ` +
						  `class="${bclass}"` +
						  `style="background-color: ${color};" ` +
						  `onclick="menus.select (${pos})" ` +
						  `ondblclick="menus.button (${pos})">` +
						  text +
						  '</td>';
				}

				if ((i % width) == (width - 1)) {
					 
					 html += '</tr>';
				}
				
				pos ++;
		  });

		  html += '</tbody></table>';
		  
		  $('#button_grid').html (html);
	 },
	 
	 /**
	  *
	  * load a new menu
	  *
	  **/
	
	 menu: function (menuName) {
		  
		  window.location = `/menus/index/${configID}/${menuName}/0`;
	 },

	 /**
	  *
	  * get the button details from the server and load edit dialog
	  *
	  **/

	 button: function (p) {

		  menus.modified ();

		  pos = p;
		  data = {config_id: configID,
					 menu_name: menuName,
					 menu_index: menuIndex,
					 pos: pos,
					 class: curr.buttons [p].class};
		  
		  let url = '/buttons';
		  $.ajax ({
				url: url,
				type: "POST",
				data: data,
				success: function (data) {
					 
					 $('#modal_content').html ('');
					 data = JSON.parse (data);

					 console.log (data);
					 
					 $('#modal_content').html (data.html);
					 menus.modal.show ();
				}
		  });
	 },
	 
	 /**
	  *
	  * display the create button modal
	  *
	  **/

	 create: function (p) {
		  
		  pos = p;
		  console.log (`create button... ${pos}`);
		  $('#create_modal').modal ('show');
	 },
	 
	 /**
	  *
	  * select a button
	  *
	  **/

	 select: function (pos) {
		  
		  if (selected >= 0) {
				
				$(`#b_${selected}`).removeClass ("select-button");
		  }
		  
		  selected = pos;
		  $(`#b_${pos}`).addClass ("select-button");
	 },

	 /**
	  *
	  * trap keyboard controls
	  *
	  **/

	 keyDown: function (e){
		  
		  if (selected == -1) {

				return;
		  }
		  
		  if (e.ctrlKey) {

				if (selected >= 0) {
					 
					 switch (e.keyCode) {

					 case X_KEY:  // ctrl x

						  copy = curr.buttons [selected];
						  curr.buttons [selected] = empty;
						  menus.render (curr.buttons)
						  break;
						  
					 case C_KEY:  // ctrl c
						  
						  copy = curr.buttons [selected];
						  break;
						  
					 case V_KEY:  // ctrl v

						  if (copy != null) {
								
								curr.buttons [selected] = copy;
								menus.render (curr.buttons)
						  }
						  break;
					 }
					 
					 menus.modified ();
				}
		  }
		  else {
				
				switch (e.keyCode) {
					 
				case ESC_KEY:
					 
					 copy = null;
					 pos = 0;
					 selected = -1;
					 menus.render (curr.buttons);
					 break;
				}
		  }
	 },

	 /**
	  *
	  * load a sub menu
	  *
	  **/

	 subMenu: function () {
		  
		  menuIndex = $('#sub_menu_index').val ();
		  
		  if (menus.modified) {
				
				if (!confirm (confirmChanges)) {
					 
					 return;
				}
		  }
		  
		  window.location = `/menus/index/${configID}/${menuName}/${menuIndex}`;
	 },

	 /**
	  *
	  * mark menu as changed
	  *
	  **/

	 modified: function () {

		  modified = true;
		  $('#save_menu').removeClass ('btn-secondary');
		  $('#save_menu').addClass ('btn-success');
	 }
}

/**
 *
 * menus.render the first menu
 *
 **/

menus.render (curr.buttons);
$("body").bind ("keydown", menus.keyDown);

/**
 *
 * color utils
 *
 **/

function rgbToHex (rgb) {

	 rgb = rgb.match (/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	 
	 function hex (x) {
		  
		  return ("0" + parseInt(x).toString(16)).slice(-2);
	 }
	 
	 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

/**
 *
 * modify the current menu
 *
 **/

 $('#menu_actions').change (function () {

	  console.log ('m a... ' + $('#menu_actions').val ());
	  
	  if ($('#menu_actions').val () == 0) {
			return;
	  }
	  
	  $.ajax ({
			url: `/menus/action/${$('#menu_actions').val ()}/${configID}/${menuName}/${menuIndex}`,
			type: "GET",
			success: function (data) {
				 
				 data = JSON.parse (data);
				 $('#modal_title').html (data.title);
				 $('#modal_content').html (data.html);
				 menus.modal.show ();
			}
	  });
 });

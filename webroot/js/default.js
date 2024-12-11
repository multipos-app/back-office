
/**
 *
 *  Setup the menu toggle for small screens
 *  Load initial page via controller method
 *
 */

$(document).ready (function () {
	 
	 controller (ctrl);
	 
	 $('[data-toggle="offcanvas"]').click (function () {
		  
		  $('#wrapper').toggleClass ('toggled');
	 });

	 $('body') [0].classList.toggle ('mobile-nav-active');
	 
	 $(window).on ('popstate', function (e) {		

		  /**
			*
			* ignore the first pgge, it is the current
			*
			*/
		  
		  pages.pop ();
		  
		  if (pages.length > 0) {
				
				let page = pages.pop ();
				controller (page, false);
		  }			
	 });

	 TweenMax.to ("#main_nav", .4, { scale: 1, ease:Sine.easeInOut});
	 $("#bu_nav").fadeOut (0);

	 $("#bu_toggle").click (function () {

		  if (bu) {
				
				$("#bu_nav").fadeOut (500);
				$("#main_nav").fadeIn (500);				 
				bu = false;
		  }
		  else {
				
				$("#main_nav").fadeOut (500);
				$("#bu_nav").fadeIn (500);
				bu = true;
		  }
	 });

});

/**
 *
 * Menu visibility toggle
 *
 */

$('.mobile-nav-toggle').click (function (e) {
	 
	 e.currentTarget.classList.toggle ('bi-list')
	 e.currentTarget.classList.toggle ('bi-x')
	 $('body') [0].classList.toggle ('mobile-nav-active')
});

/**
 *
 * 
 *
 */

function buSelect (buIndex) {

	 if (buIndex != buCurrent) {
		  
		  $('#bu_icon_' + buCurrent).removeClass ('bx-checkbox-checked');
		  $('#bu_icon_' + buCurrent).addClass ('bx-checkbox');
		  $('#bu_icon_' + buIndex).removeClass ('bx-checkbox');
		  $('#bu_icon_' + buIndex).addClass ('bx-checkbox-checked');
		  buCurrent = buIndex;

		  console.log (pageAction);
		  
		  controller (pageAction, false);
	 }
}

/**
 *
 * Main ajax method, save pages and post query to server
 *
 */

var pages = [];
var timerID = null;
var session = null;
var pageAction = 'sales';

function controller (action, absolute) {
	 
	 if (session != null) {

		  clearTimeout (session);
	 }

	 /**
	  * logout after 20 minutes
	  */
	 
	 session = setTimeout (() => {
		  
		  window.location = '/';
		  
	 }, "1200000");

	 pageAction = action;
	 pageAbsolute = absolute;
	 
	 pages.push (action);

	 if (pages.length > 20) {

		  pages.shift ();
	 }
	 
	 // $('#main_content').html ('<div class="loader"></div>');
	  $('#multipos_modal_overlay').show ();

	 let url = '';
	 if (absolute) {

		  url = action;
	 } else {
		  
		  url = '/' + action;
	 }

	 if (buCurrent > 0) {

		  url += '/business_unit_id/' + merchants [buCurrent] ['id'];
	 }

	 url = url.replace ('//', '/');
	 
	 console.log ('controller... ' + url + ' ' + action)
	 
	 $.ajax ({type: "GET",
				 url: url,
				 statusCode: {
					  500: function () {
							
							$('#multipos_modal_overlay').hide ();
							alert ("Internal error, please contact support");
							
							if (pages.length > 1) {
		  
								 pages.pop ();
								 controller (pages.pop (), false);
							}
							else {
								 
								 controller ('/sales', true);
							}
					  }
				 },
				 success: function (data) {
					  
					  data = JSON.parse (data);

					  if (data.action == 'logout') {

							window.location = '/merchants/logout/' + pages.pop ();
							return;
					  }
					  
					  $('#multipos_modal_overlay').hide ();
				  
					  $('head title').text (data.title);
					  $('#main_content').html (data.html);
					  $('#pages').html (data.pages);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
	 
    $('body') [0].classList.toggle ('mobile-nav-active')
}

/**
 *
 * Skip forward/back in report periods
 *
 */

function skip (time, c) {
	 
	 controller ('/' + c + '/index/start_date/' + time, true);
}

/**
 *
 * Report column sort
 *
 */

var direction = 'asc';
function colSort (action, col) {

	 let url = '/' + action + '/index?sort=' + col + '&direction=' + direction;
	 console.log (url);
	 
	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {
					  
					  data = JSON.parse (data);
					  $('#main_content').html (data ['html']);
					  
					  direction = (direction == 'asc') ? 'desc' : 'asc';
				 }
				});
};

/**
 *
 * form/edit slideout section
 *
 */

tagID = 0;
actionID = '';

function openForm (id, url, action = 'action_form') {

	 console.log ('tag... #tag_' + id + ' ' + url + ' ' + action);
	 
	 if (tagID > 0) {

		  $('#tag_' + id).html ('');
		  closeForm ();
	 }
	 
	 tagID = id;
	 actionID = action;
	 
	 $('#' + action).toggleClass ('on');
	 $('#tag_' + id).html ('<i class="fa fa-circle-small fa-small"></i>')
	 
	 console.log ('open form... ' + url);

	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {
                 
					  data = JSON.parse (data);
					  $('#' + action).html (data.html);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
}

function closeForm () {

	 $('#' + actionID).toggleClass ('on');
	 if (tagID > 0) {

		  $('#tag_' + tagID).html ('');
	 }
	 tagID = 0;
	 $('#' + actionID).html ('');
}

function del (control, id, warn) {

	 
	 if (confirm (warn)) {

		  let url = '/' + control + '/delete/' + id;
		  console.log ('del... ' + control + " " + id + " " + url);

		  $.ajax ({type: "POST",
					  url: url,
					  success: function (data) {
							
							console.log (data);
							
							data = JSON.parse (data);
							closeForm ();
							controller (control, false);
					  }
					 });
	 }
}

function buSelect () {

	 let url = '/pos-app/bu/' + $('#bu_select').val ();
	 console.log ('bu sel... ' + pageAction + ' ' + url);

	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {

					  controller (pageAction, false);
				 }
				});
}


function controllerBack () {

	 console.log (pages);
	 
	 if (pages.length > 1) {
		  
		  pages.pop ();
		  controller (pages.pop (), false);
	 }
}

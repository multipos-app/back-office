

ticketID = 0;

function detailClose () {

	 $('#detail').toggleClass ('on');
	 if (ticketID > 0) {

		  $('#ticket_' + ticketID).html ('');
	 }
	 ticketID = 0;
}

function detail (tid) {

	 
	 if (ticketID > 0) {

		  $('#ticket_' + ticketID).html ('');
		  detailClose ();
	 }

	 let flag = '<i class="fa fa-circle-small fa-small"></i>';
	 
	 ticketID = tid;
	 
	 $('#ticket_' + tid).html (flag);

	 let url = '/tickets/detail/' + tid;
	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {
                 
					  data = JSON.parse (data);
					  $('#detail').toggleClass ('on');
					  $('#detail').html (data.html);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
}

function flag () {
	 
	 let url = '/tickets/flag/' + ticketID + '/' + $('#flag').val ();
	 console.log ('flag... ' + url);

	 $('#flag_' + ticketID).html ('<i class="fa fa-flag fa-small fa-warn">');

	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {
                 
					  data = JSON.parse (data);
					  console.log (data);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
}

function loadSearch () {
	 
	 $('#detail').toggleClass ('on');
	 
	 let url = '/tickets/search';
	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {
                 
					  data = JSON.parse (data);
					  $('#detail').html (data.html);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
}

function period (start) {

	 controller ('/tickets/index/period/' + start, true);
}

function salesExport () {

	 var csv = '';
	 var sep = '';
	 var col = 0;
	 
	 $('.data-cell').each (function () {				 
		  
		  /* if ($($(this) [0].firstChild).is ('a')) {
			  
			  csv += sep + $($($(this) [0].firstChild) [0].firstChild) [0].firstChild.data;
			  }
			  else {
		  */
		  
		  if ($(this) [0].firstChild == null) {

				csv += sep;
		  }
		  else {

				csv += sep + $(this) [0].firstChild.data;
		  }
		  /* }*/
		  
		  sep = ',';
		  col ++;
		  if (col == 12) {
				
				csv += '\n';
				sep = '';
				col = 0;
		  }
	 });

	 csv += '\n';
 	 var e = document.createElement ('a');
	 e.setAttribute ('href', 'data:csv,' + encodeURIComponent (csv));
	 e.setAttribute ('download', 'tickets-export.csv');
	 e.style.display = 'none';
	 document.body.appendChild (e);
	 e.click ();
	 document.body.removeChild (e);
}

$('.start-date').datepicker ({
	 dateFormat: 'mm/dd/yy',
	 altFormat: 'yy-mm-dd',
	 altField: '#start_date',
	 maxDate: 0});

$('.end-date').datepicker ({
	 dateFormat: 'mm/dd/yy',
	 altFormat: 'yy-mm-dd',
	 altField: '#end_date',
	 maxDate: 0});

$(".currency-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});

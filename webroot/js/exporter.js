/**
 *
 * export to csv
 *
 */

function exporter (name, len, header = '') {

	 console.log (header);
	 
	 var csv = '';
	 var sep = '';
	 var col = 0;

	 if (header.length > 0) {

		  csv += header + "\n";
	 }
		  
	 $('.data-cell').each (function () {				 
		  
		  if ($($(this) [0].firstChild).is ('a')) {
				
				csv += sep + $($($(this) [0].firstChild) [0].firstChild) [0].firstChild.data;
		  }
		  else if ($(this) [0].firstChild !== null) {
				
				csv += sep + $(this) [0].firstChild.data;
		  }
		  else {
				
				csv += sep;
		  }
		  
		  sep = ',';
		  col ++;
		  if (col == (len)) {
				
				csv += '\n';
				sep = '';
				col = 0;
		  }
	 });

	 csv += '\n';
 	 var e = document.createElement ('a');
	 e.setAttribute ('href', 'data:csv,' + encodeURIComponent (csv));
	 e.setAttribute ('download', name + '-export.csv');
	 e.style.display = 'none';
	 document.body.appendChild (e);
	 e.click ();
	 document.body.removeChild (e);
}

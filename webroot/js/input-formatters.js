// js fiddle... https://jsfiddle.net/gt2Ly5rt/6/

var inputUpdate = true;  // cleaer field if new focus, overwrite mode
var locale = 'en_US';  // cleaer field if new focus, overwrite mode

function currencyFormatter (l) {
	 
	 if (l.length == 0) {

		  locale = 'en_US';
	 }
	 else {

		  locale = l;
	 }

	 $('input.currency-format').on ('keydown', function (e) {
		  		  
		  // 48 - 57
		  // 96 - 105
		  // 8 - backspace
		  // 46 - delete
		  
		  if (!inputUpdate) {
				
				//$(this).val ('');
		  }

		  console.log ('key code... ' + e.keyCode);

		  if (((e.keyCode > 47) && (e.keyCode < 58)) ||
				((e.keyCode > 95) && (e.keyCode < 106)) ||
				(e.keyCode == 8) ||
				(e.keyCode == 46)) {

				e.preventDefault ();
				
				// backspace & del
				
				if ($.inArray (e.keyCode,[8,46]) !== -1) {
					 $(this).val ('');
					 return;
				}
				
				var a = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "`", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
				var n = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
				
				var value = $(this).val ();
				var clean = value.replace (/\./g,'').replace (/,/g,'').replace (/^0+/, '');   
				
				var charCode = String.fromCharCode (e.keyCode);
				var p = $.inArray (charCode, a);
     			
				if (p !== -1) {
					 
					 value = clean + n[p];
					 
					 if (value.length == 2) value = '0' + value;
					 if (value.length == 1) value = '00' + value;
					 
					 var formatted = '';
					 for (var i=0;i<value.length;i++) {
						  var sep = '';

						  switch (locale) {

								case 'en_US':
								
								if (i == 2) sep = '.';
								if (i > 3 &&  (i+1) % 3 == 0) sep = ',';
								break;

						  default:
								
								if (i == 2) sep = ',';
								if (i > 3 &&  (i+1) % 3 == 0) sep = '.';
						  }
						  
						  // if (i == 2) sep = ',';
						  // if (i > 3 &&  (i+1) % 3 == 0) sep = '.';
						  
						  formatted = value.substring (value.length-1-i, value.length-i) + sep + formatted;
					 }
					 
					 inputUpdate = true;
					 $(this).val (formatted);
				}
		  }
		  else {
				return;
		  }
	 });
	 
	 $('input.currency-format').focus (function (e) {

		  $(this).select ();
		  inputUpdate = false;
	 });}

function percentFormatter () {
	 
	 $('input.percent-format').on ('keydown', function (e) {
		  
		  // 48 - 57
		  // 96 - 105
		  // 8 - backspace
		  // 46 - delete
		  
		  if (!inputUpdate) {
				
				$(this).val ('');
		  }

		  if (((e.keyCode > 47) && (e.keyCode < 58)) ||
				((e.keyCode > 95) && (e.keyCode < 106)) ||
				(e.keyCode == 8) ||
				(e.keyCode == 46)) {

				e.preventDefault ();
				
				// backspace & del
				
				if ($.inArray (e.keyCode,[8,46]) !== -1) {
					 $(this).val ('');
					 return;
				}
				
				var a = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "`", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
				var n = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
				
				var value = $(this).val ();
				var clean = value.replace (/\./g,'').replace (/,/g,'').replace (/^0+/, '');   
				
				var charCode = String.fromCharCode (e.keyCode);
				var p = $.inArray (charCode, a);
     			
				if (p !== -1) {
					 
					 value = clean + n[p];
					 
					 if (value.length == 2) value = '0' + value;
					 if (value.length == 1) value = '00' + value;
					 
					 var formatted = '';
					 for (var i=0;i<value.length;i++)
					 {
						  var sep = '';
						  if (i == 3) sep = '.';
						  if (i > 4 &&  (i+1) % 4 == 0) sep = ',';
						  // if (i == 2) sep = ',';
						  // if (i > 3 &&  (i+1) % 3 == 0) sep = '.';
						  
						  formatted = value.substring (value.length-1-i,value.length-i) + sep + formatted;
						  inputUpdate = true;
					 }
					 
					 $(this).val (formatted);
				}
		  }
		  else {
				return;
		  }
	 });
	 
	 $('input.percent-format').focus (function (e) {

		  $(this).select ();
		  inputUpdate = false;
	 });
}

function integerFormatter () {
	 
	 $('input.integer-format').on ('keydown', function (e) {

		  if (e.keyCode == 9) {  // tab

				return true;
		  }
		  
		  e.preventDefault ();
	 
		  // 48 - 57
		  // 96 - 105
		  // 8 - backspace
		  // 46 - delete

		  if (!inputUpdate) {
				
				$(this).val ('');
		  }
		  
		  if (((e.keyCode > 47) && (e.keyCode < 58)) ||
				((e.keyCode > 95) && (e.keyCode < 106)) ||
				(e.keyCode == 8) ||
				(e.keyCode == 46)) {
								
				// backspace & del
				
				if ($.inArray (e.keyCode,[8,46]) !== -1) {
					 $(this).val ('');
					 return;
				}
				
				var a = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "`", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
				var n = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
				
				var charCode = String.fromCharCode (e.keyCode);
				var p = $.inArray (charCode, a);
				
				if (p !== -1) {
					 
					 var value = $(this).val () + n[p];
					 $(this).val (value);
				}

				inputUpdate = true;
		  }
		  else {
				return;
		  }
	 });

	 $('input.integer-format').focus (function (e) {

		  $(this).select ();
		  inputUpdate = false;
	 });

}

function numberFormat (e, event, len) {
	 		  
    event = event || window.event;
	 
	 switch (event.keyCode) {

	 case 8:
	 case 46:
		  return;

	 case 48:
	 case 49:
	 case 50:
	 case 51:
	 case 52:
	 case 53:
	 case 54:
	 case 55:
	 case 56:
	 case 57:
	 case 58:
	 case 59:
		  
		  event.preventDefault ();

		  if ($(e).val ().length < len) {
				
				$(e).val ($(e).val () + (event.keyCode - 48).toString ());
		  }
		  break;

	 default: 
		  event.preventDefault ();	 		  
	 }
}

function currencyToDecimal (val) {
	 
	 switch (locale) {

	 case 'en_US':

		  val = val.replace (',' , '');
		  break;
		  
	 default:
		  
		  val = val.replace ('.', '').replace (',', '.');
	 }
	 
	 return val;
}

function decimalToCurrency (value) {
	 
	 var formatted = '';
	 value = value.replace ('.', '').replace (',', '');
	 
	 for (var i=0; i<value.length; i++) {
		  
		  var sep = '';

		  switch (locale) {

		  case 'en_US':
								
				if (i == 2) sep = '.';
				if (i > 3 &&  (i+1) % 3 == 0) sep = ',';
				break;
				
		  default:
				
				if (i == 2) sep = ',';
				if (i > 3 &&  (i+1) % 3 == 0) sep = '.';
		  }
		  
		  formatted = value.substring (value.length-1-i, value.length-i) + sep + formatted;
	 }
	
	 return formatted;
}

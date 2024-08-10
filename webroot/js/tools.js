
function select (name, a, value) {

	 let html = '<select name="' + name + '" id="' + name + '" class="custom-dropdown">';
	 $.each (a, function (k, v) {

		  let selected = (k == value) ? ' selected' : '';
		  html += '<option value=' + k + selected + '>' + v + '</option>';
	 });
	 html += '</select>';
	 
	 return html;
}

const validateEmail = (email) => {
	 
	 return email.match (/^[^\s@]+@[^\s@]+\.[^\s@]+$/);
};

const validate = () => {

	 
	 const $result = $ ('#email_result');
	 const email = $ ('#email').val ();

	 console.log ('validate... ' + email);

	 $result.text ('');

	 if (validateEmail (email)) {
		  
		  $result.text (email + ' is valid');
		  $result.css ('color', 'green');
	 }
	 else {
		  
		  $result.text (email + ' is not valid');
		  $result.css ('color', 'red');
	 }
	 return false;
}

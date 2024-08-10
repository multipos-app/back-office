
$('#add_pricing').change (function () {

	 console.log ('add pricing... ' + $('#add_pricing').val ());
	 openForm (0, '/pricing/' + $('#add_pricing').val ());
});


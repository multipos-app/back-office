$('#profile_update').on ('click', function (e) {

    e.preventDefault ();

	 let data = {profile_id: profile.id,
					 profile_desc: $('#profile_desc').val (),
					 permissions: []};
	 
	 $(".styled").each (function (index) {

		  if (!$(this).is (':checked')) {

				data.permissions.push ($(this).attr ('name'));
		  }
	 });

	 console.log (data);
	 
	 let url = '/profiles/update/' + profile.id;

	 $.ajax ({type: "POST",
				 url: url,
				 data: data,
				 success: function (data) {

					  closeForm ();
					  controller ('profiles', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});


var itemLink = null;

function renderLinks () {
	 
	 $('#item_link_grid').html ('');
	 if (itemLinks.length == 0) {

		  return;
	 }
	 
	 var html = '<div class="form-grid item-link-grid">';
	 	 
	 $.each (itemLinks, function (i, link) {

		  options = '';
		  
		  $.each (linkTypes, function (key, value) {
				
				var selected = '';
				if (link.link_type == key) {

					 selected = ' selected';
				}

				options += 
					 '<option value=' + key + selected + '>' + value + '</option>';
		  });
		  
		  console.log ('link...');
		  console.log (link);

		  let id = link.id;
		  if ("item_link_id" in link) {

				id = link.item_link_id;
		  }
		  
		  html +=
				'<input type="hidden" name="item[item_links][' + i + '][item_link_id]" value="' + id + '"/>' +
				'<div class="form-cell form-desc-cell">' +
				link.item_desc +
				'</div>' +
				
				'<div class="select">' +
				'<select name="item[item_links][' + i + '][link_type]" required="required">' +
				options +
				'</select>' + 
				'</div>' +
				
		  '<div class="form-cell icon-cell">' +
				'<i class="far fa-trash fa-med" onclick="javacript:delLink(' + i + ')"></i>' +
				'</div>';
		  
	 });
	 
	 html +=
		  '</div>';
	 $('#item_link_grid').html (html);
}

function addLink () {

	 let url = '/items/add-link/' + item.id + '/' + addLinkID + '/' + $('#link_type').val ();
	 
	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {

					  data = JSON.parse (data);

					  console.log (data)
					  
					  itemLinks.push (data.link);
					  
 					  $('#link_desc').val ('');
 					  $('#link_type').val (0);
					  
					  renderLinks ();
				 }
				});
}

function delLink (i) {

	 itemLinks.splice (i, 1);
	 renderLinks ();
}

$('#link_desc').typeahead ({
	 
	 source: function (query, result) {
		  
		  $.ajax ({
				url: "/search/items/sku_and_desc/" + query + "/sku_and_desc",
				type: "GET",
				success: function (data) {
					 
					 data = JSON.parse (data);
					 result ($.map (data, function (item) {

						  return item;
					 }));
				}
		  });
	 },
	 updater: function (linkItem) {
		  
		  if (item.id == linkItem.id) {
				
				alert ('Linking an item to itself is not Allowed');
				return;
		  }
		  
		  addLinkID = linkItem.id;

		  return linkItem.name;
	 }
});

renderLinks ();
$(".currency-format").mask ("#,##0.00", {reverse: true});
$(".integer-format").mask ("#######0");

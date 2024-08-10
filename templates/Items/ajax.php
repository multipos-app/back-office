<?php

if ($response ['paginate']) {
	 
	 $response ['pages'] = '<nav class="pagination">' .
								  '<ul class="pagination">' .
								  $this->Paginator->numbers (['separator' => false,
																		'tag' => 'li',
																		'currentTag' => 'span',
																		'currentClass' => 'active color_black',
																		'templates' => ['number' => '<li><a onclick="javascript:controller (\'items/?page={{text}}\', false)">{{text}}</a></li>']]) .
								  '</ul>' .
								  '</nav>';
}
echo json_encode ($response);

?>

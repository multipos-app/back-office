<?php

$response ['pages'] = '<nav class="pagination">' .
							 '<ul class="pagination">' .
							 $this->Paginator->numbers (['separator' => false,
																  'tag' => 'li',
																  'currentTag' => 'span',
																  'currentClass' => 'active',
																  'templates' => ['number' => '<li><a onclick="javascript:controller (\'items/inv-items/?page={{text}}\', false)">{{text}}</a></li>']]) .
							 '</ul>' .
							 '</nav>';

echo json_encode ($response);

?>

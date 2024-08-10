<?php

if (isset ($response ['paginate']) && ($response ['paginate'])) {
 	 
 	 $response ['pages'] = '<nav class="pagination">' .
 								  '<ul class="pagination">' .
								  
 								  /* $this->Paginator->prev (['separator' => false,
 									  'templates' => ['number' => '<li><a onclick="javascript:controller (\'' . $response ['paginate'] . '?page={{text}}\', false)">&lt;&lt;</a></li>']]) .*/
								  
 								  $this->Paginator->numbers (['separator' => false,
																		'tag' => 'li',
 																		'currentTag' => 'span',
 																		'currentClass' => 'active',
 																		'templates' => ['number' => '<li><a onclick="javascript:controller (\'' . $response ['paginate'] . '?page={{text}}\', false)">{{text}}</a></li>']]) .
								  
 								  /* $this->Paginator->next (['separator' => false,
 									  'templates' => ['number' => '<li><a onclick="javascript:controller (\'' . $response ['paginate'] . '?page={{text}}\', false)">&gt;&gt;</a></li>']]) .*/
 								  '</ul>' .
 								  '</nav>';
}

echo json_encode ($response);

?>

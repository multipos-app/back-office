<?php

/**
 *
 * draw the locations select list
 *
**/

if (count ($merchant ['bu_list']) > 3) {
	 echo $this->Form->select ('bu_select',
										$merchant ['bu_list'], ['id' => 'bu_select',
																		'onchange' => 'buSelect ()',
																		'class' => 'custom-dropdown',
																		'label' => false]);
}
?>


<?php

$totals = ['discounts' => array_fill (0, $len + 1, 0),
           'refunds' => array_fill (0, $len + 1, 0),
           'no_sales' => array_fill (0, $len + 1, 0),
           'tips' => array_fill (0, $len + 1, 0),
			   __ ('Totals') => null,
           'net' => array_fill (0, $len + 1, 0),
			  'total' => array_fill (0, $len + 1, 0),
			  'gross' => array_fill (0, $len + 1, 0),
           'tax' => array_fill (0, $len + 1, 0),
 			  __ ('Tenders') => null,
           'card' => array_fill (0, $len + 1, 0),
           'credit' => array_fill (0, $len + 1, 0),
           'cash' => array_fill (0, $len + 1, 0),
           'wallet' => array_fill (0, $len + 1, 0),
           'check' => array_fill (0, $len + 1, 0),
           'other' => array_fill (0, $len + 1, 0),
           'gift_card' => array_fill (0, $len + 1, 0),
           'square_gift_card' => array_fill (0, $len + 1, 0),
           'square_account' => array_fill (0, $len + 1, 0),
           'split' => array_fill (0, $len + 1, 0),
           'taxable' => array_fill (0, $len + 1, 0),
           'non_taxable' => array_fill (0, $len + 1, 0)];
?>

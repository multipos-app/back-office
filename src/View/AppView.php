<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use \NumberFormatter;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */

    private $fields;

    public function initialize(): void { }

	 // public function input ($id, $val, $icon, $class, $click, $placeholder) {
    
    public function input ($icon, $args) {
		  
		  $html =
				'<div class="grid-cell input-group-grid">' .
				'<div class="grid-cell grid-cell-left grid-cell-middle">' .
				'<i class="fa ' . $icon . ' fa-med"></i>' .
            '</div>' .
				'<div class="grid-cell grid-cell-left">' .
            '<input type="text"';

        foreach ($args as $key => $val) {

            $html .= ' ' . $key . '="' . $val . '"';
        }
        
        $html .=
				'>' .
				'</div>' .
				'</div>';
        
		  return $html;
	 }

    public function moneyFormat ($val) {
        
        $fmt = new NumberFormatter ('en_US', NumberFormatter::CURRENCY);
        return $fmt->formatCurrency (floatval ($val), "USD");
    }
    
    public function debug ($obj) {
        
        switch (gettype ($obj)) {

				case 'array':
				case 'object':

					 $this->log (json_encode ($obj, JSON_PRETTY_PRINT), 'debug');
					 break;

				case 'string':

					 $this->log ($obj, 'debug');
					 break;
					 
				default:

					 $this->log ("log type unkown... " . gettype ($obj));
        }
    }
}

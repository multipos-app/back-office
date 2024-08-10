<?php

namespace App\Controller;
class CoreController extends AppController {

	 function index (...$params) {

		  $this->debug ("Core...");
		  exit;
	 }

	 function skin () {
		  
		  $this->debug ("Core/skin...");
		  exit;
	 }
}


<?php

namespace App\Core;

use App\Core\View;
use Plasticbrain\FlashMessages\FlashMessages;

abstract class Controller
{

	protected $view;
	protected $flash;

	public function __construct(View $view, FlashMessages $flash)
	{
		$this->view = $view;
		$this->flash = $flash;
	}
}

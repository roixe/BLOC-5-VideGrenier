<?php

namespace App\Controllers;

use \Core\View;
use Exception;

/**
 * Home controller
 */
class Home extends \Core\Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function indexAction()
    {
        $this->view->renderTemplate('Home/index.html', []);
    }
}

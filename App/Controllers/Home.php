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

    /**
     * Constructeur avec injection de dépendance sur la classe View
     */
    public function __construct($view = null)
    {
        $this->view = $view ?: new View();
    }

    /**
     * Affiche la page d'accueil
     *
     * @return void
     * @throws Exception
     */
    public function indexAction()
    {
        $this->view->renderTemplate('Home/index.html', []);
    }
}

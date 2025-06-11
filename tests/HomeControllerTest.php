<?php


use PHPUnit\Framework\MockObject\MockMethod;
use PHPUnit\Framework\TestCase;
use App\Controllers\Home;
use App\Core\View;

class HomeControllerTest extends TestCase
{

    public function testIndexAction_runsWithoutErrors()
    {
        $controller = new Home();
    
        // On vérifie simplement que la méthode ne lance pas d'exception
        $this->expectNotToPerformAssertions();
    
        $controller->indexAction();
    }
}

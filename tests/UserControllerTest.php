<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use App\Models\User;
use App\Core\View;

class UserControllerTest extends TestCase
{
    private $userMock;
    private $controller;

    protected function setUp(): void
    {
        $this->userMock = $this->createMock(User::class);
        $this->controller = new UserController($this->userMock);

        // Mock View::renderTemplate statique (optionnel selon ta config)
        // Par exemple, on pourrait stubber View::renderTemplate si tu utilises un wrapper testable
    }

    
}

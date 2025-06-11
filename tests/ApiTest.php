<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Api;
use App\Models\Articles;
use App\Models\Cities;

class ApiTest extends TestCase
{
    protected function setUp(): void
    {
        $_GET = [];
    }

    public function testProductsActionReturnsJsonSansMock()
{
    $_GET['sort'] = 'date';


    ob_start();
    $controller = new Api([]);
    $controller->ProductsAction();
    $output = ob_get_clean();

    $this->assertJson($output);

    $data = json_decode($output, true);

    $this->assertIsArray($data);
    $this->assertArrayHasKey('id', $data[0]);
    $this->assertArrayHasKey('name', $data[0]);
}

    public function testProductsActionReturnsJsonWithoutMock()
    {
        $_GET['sort'] = 'date';
    
        // Tu dois t'assurer que Articles::getAll('date') fonctionne
        ob_start();
        $controller = new Api([]);
        $controller->ProductsAction();
        $output = ob_get_clean();
    
        $this->assertJson($output);
    }
    
}

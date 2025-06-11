<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Api;

class ApiTest extends TestCase
{
    protected function setUp(): void
    {
        $_GET = [];
    }

    public function testProductsActionReturnsJson()
    {
        $_GET['sort'] = 'date';

        ob_start();
        $controller = new Api([]);
        $controller->ProductsAction();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        if (!empty($data)) {
            $this->assertArrayHasKey('id', $data[0]);
            $this->assertArrayHasKey('name', $data[0]);
        }
    }

    public function testProductsActionWithoutSortParam()
    {
        ob_start();
        $controller = new Api([]);
        $controller->ProductsAction();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertIsArray($data);
    }

    public function testProductsActionWithInvalidSortReturnsJson()
    {
        $_GET['sort'] = 'invalid_sort';

        ob_start();
        $controller = new Api([]);
        $controller->ProductsAction();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertIsArray($data);
    }

    public function testCitiesActionReturnsJson()
    {
        $_GET['query'] = 'Paris';

        ob_start();
        $controller = new Api([]);
        $controller->CitiesAction();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);

        $this->assertIsArray($data);

        if (!empty($data)) {
            $this->assertArrayHasKey('id', $data[0]);
            $this->assertArrayHasKey('name', $data[0]);
        }
    }

    public function testCitiesActionWithoutQueryReturnsJson()
    {
        ob_start();
        $controller = new Api([]);
        $controller->CitiesAction();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertIsArray($data);
    }
}

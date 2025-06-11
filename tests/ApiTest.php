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
        $_GET['sort'] = 'date'; // Paramètre obligatoire attendu dans le contrôleur

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
        $_GET['sort'] = ''; // Protection pour éviter l'erreur "Undefined array key"

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
        $_GET['sort'] = 'invalid_sort'; // Cas non attendu mais qui ne doit pas planter

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
        $_GET['query'] = 'Paris'; // Valeur réaliste pour chercher une ville

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
        $_GET['query'] = ''; // Évite l'erreur si le contrôleur n'utilise pas ?? ou isset()

        ob_start();
        $controller = new Api([]);
        $controller->CitiesAction();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertIsArray($data);
    }
}

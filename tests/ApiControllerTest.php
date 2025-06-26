<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Api;

class ApiControllerTest extends TestCase
{
    /**
     * Test que getProductsData() retourne bien les articles
     */
    public function testGetProductsData_returnsExpectedArticles()
    {
        // 1. Création d’un faux modèle Articles
        $mockArticlesModel = $this->createMock(\App\Models\Articles::class);

        // 2. On définit ce que le mock doit retourner quand on appelle getAll()
        $mockArticlesModel->expects($this->once())
            ->method('getAll')
            ->with(['sort' => 'recent'])
            ->willReturn([
                ['id' => 1, 'title' => 'Article Test']
            ]);

        // 3. On instancie le contrôleur avec le mock injecté
        $controller = new Api($mockArticlesModel, null);

        // 4. On appelle la méthode pure (sans header)
        $result = $controller->getProductsData(['sort' => 'recent']);

        // 5. On vérifie le résultat
        $this->assertIsArray($result);
        $this->assertEquals('Article Test', $result[0]['title']);
    }

    /**
     * Test que getCitiesData() retourne bien les résultats de recherche
     */
    public function testGetCitiesData_returnsExpectedCities()
    {
        $mockCitiesModel = $this->createMock(\App\Models\Cities::class);

        $mockCitiesModel->expects($this->once())
            ->method('search')
            ->with(['query' => 'paris'])
            ->willReturn(['Paris', 'Paris 2']);


        $controller = new Api(null, $mockCitiesModel);

        $result = $controller->getCitiesData(['query' => 'paris']);

        $this->assertIsArray($result);
        $this->assertContains('Paris', $result);
    }
}

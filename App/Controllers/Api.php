<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;

class Api extends \Core\Controller
{
    private $articlesModel;
    private $citiesModel;

    public function __construct($articlesModel = null, $citiesModel = null)
    {
        $this->articlesModel = $articlesModel ?? new Articles();
        $this->citiesModel = $citiesModel ?? new Cities();
    }

    public function getProductsData(array $query): array
    {
        return $this->articlesModel->getAll($query);
    }

    public function getCitiesData(array $query): array
    {
        return $this->citiesModel->search($query);
    }

    public function ProductsAction($query = null)
    {
        $query = $query ?? $_GET;
        $data = $this->getProductsData($query);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function CitiesAction($query = null)
    {
        $query = $query ?? $_GET;
        $data = $this->getCitiesData($query);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}


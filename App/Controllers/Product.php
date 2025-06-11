<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

class Product extends \Core\Controller
{
    private Articles $articlesModel;
    private Upload $uploadUtility;

    public function __construct(Articles $articlesModel = null, Upload $uploadUtility = null)
    {
        $this->articlesModel = $articlesModel ?? new Articles();
        $this->uploadUtility = $uploadUtility ?? new Upload();
    }

    /**
     * Création d’un produit, renvoie tableau avec erreurs et id
     */
    public function createProduct(array $postData, array $filesData, int $userId): array
    {
        $errors = [];

        if (!isset($filesData['picture']) || $filesData['picture']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Une photo est obligatoire pour publier une annonce.";
        }

        if (empty($errors)) {
            try {
                $f = $postData;
                $f['user_id'] = $userId;

                $id = $this->articlesModel->save($f);
                $pictureName = $this->uploadUtility->uploadFile($filesData['picture'], $id);
                $this->articlesModel->attachPicture($id, $pictureName);

                return ['errors' => [], 'id' => $id];
            } catch (\Exception $e) {
                $errors[] = "Une erreur s'est produite : " . $e->getMessage();
            }
        }

        return ['errors' => $errors, 'id' => null];
    }

    /**
     * Affiche la page d'ajout
     */
    public function indexAction()
    {
        if (isset($_POST['submit'])) {
            $result = $this->createProduct($_POST, $_FILES, $_SESSION['user']['id']);

            if (empty($result['errors'])) {
                header('Location: /product/' . $result['id']);
                exit;
            }

            View::renderTemplate('Product/Add.html', [
                'errors' => $result['errors']
            ]);
        } else {
            View::renderTemplate('Product/Add.html');
        }
    }

    /**
     * Récupère les données pour afficher un produit
     */
    public function getShowData(int $id): array
    {
        $this->articlesModel->addOneView($id);
        $suggestions = $this->articlesModel->getSuggest();
        $article = $this->articlesModel->getOne($id);

        return [
            'article' => $article[0] ?? null,
            'suggestions' => $suggestions
        ];
    }

    /**
     * Affiche la page d'un produit
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            $data = $this->getShowData($id);
        } catch (\Exception $e) {
            $data = [
                'article' => null,
                'suggestions' => []
            ];
        }

        View::renderTemplate('Product/Show.html', $data);
    }
}

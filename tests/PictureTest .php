<?php


use PHPUnit\Framework\MockObject\MockMethod;
use PHPUnit\Framework\TestCase;
use App\Controllers\Product;

class PictureTest extends TestCase
{
    public function testMissingPhotoTriggersError()
    {
        // Simule un POST sans fichier
        $_POST['submit'] = true;
        $_FILES['picture'] = [
            'error' => UPLOAD_ERR_NO_FILE
        ];
        $_SESSION['user']['id'] = 1;

        // On capture la sortie du contrôleur
        ob_start();
        // Création d'un tableau pour l'argument $route_params comme attendu par le constructeur
        $route_params = []; 
        $controller = new Product($route_params);
        $controller->indexAction();
        $output = ob_get_clean();
        // Vérifie que le message d'erreur est présent
        $this->assertStringContainsString('Une photo est obligatoire', $output);
    }
}

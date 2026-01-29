<?php

namespace App\Controller;

use App\Service\Exception\UploadException;
use App\Service\UploadService;

class HomeController extends AbstractController
{
    private UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    /**
     * Méthode d'affichage de la page d'accueil
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->render("home", "Accueil");
    }

    /**
     * Méthode de test (param url)
     * @param int $nbr
     * @return void
     */
    public function test(int $nbr)
    {
        echo "valeur saisie " . $nbr;
    }

    /**
     * Méthode de test d'upload de fichier
     * @return mixed
     */
    public function testUpload(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();
        
        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("upload", "test upload files", $data);
            }
            try {
                $data["msg"] = "Le fichier : " . $this->uploadService->uploadFile($_FILES["upload"]) . " a été importé";
            } catch(UploadException $ue) {
                $data["errors"]["upload"] = $ue->getMessage();
            }
        }

        return $this->render("upload", "test upload files", $data);
    } 
}

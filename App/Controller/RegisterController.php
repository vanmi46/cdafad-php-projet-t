<?php

namespace App\Controller;

use App\Service\SecurityService;

class RegisterController extends AbstractController
{
    private SecurityService $securityService;

    //Injection du UserRepository
    public function __construct()
    {
        $this->securityService = new SecurityService();
    }

    //Méthode pour s'inscrire
    public function register(): mixed
    {
        $data = [];
        //Génération du token CSRF
        $data["csrf_token"] = $this->getCsrfToken();
        
        //Test si le formulaire est submit
        if ($this->isFormSubmitted($_POST,  "submit")) {
            //Vérification du token CSRF
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("register", "S'inscrire", $data);
            }
            //Ajout du compte en BDD
            $result = $this->securityService->saveUser($_POST);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("register", "S'inscrire", $data);
    }

    //Méthode pour se connecter
    public function login(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();

        //Test si le formulaire est soumis
        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("login", "Se connecter", $data);
            }
            //Logique de la connexion
            $result = $this->securityService->authenticate($_POST);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("login", "Se connecter", $data);
    }

    //Méthode pour se connecter
    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}

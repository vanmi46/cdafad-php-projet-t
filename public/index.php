<?php

session_start();

//gérer les routes

include '../vendor/autoload.php';
//Import des ressources
use Dotenv\Dotenv;

//Import du fichier .env
$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

//import des controllers
use App\Controller\HomeController;
use App\Controller\RegisterController;

//instancier les controllers
$homeController = new HomeController();
$registerController = new RegisterController();

//Analyse de l'URL avec parse_url() et retourne ses composants
$url = parse_url($_SERVER['REQUEST_URI']);
//test soit l'url a une route sinon on renvoi à la racine
$path = isset($url['path']) ? $url['path'] : '/';

//Comparer avec la liste d'url :
switch ($path) {
    case '/':
        $homeController->index();
        break;
    case '/login':
        $registerController->login();
        break;
    case '/register':
        $registerController->register();
        break;
    case '/logout':
        $registerController->logout();
        break;
    default:
        echo "erreur 404";
        break;
}
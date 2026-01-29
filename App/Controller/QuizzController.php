<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\QuizzService;

class QuizzController extends AbstractController
{
    private CategoryService $categoryService;
    private QuizzService $quizzService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->quizzService = new QuizzService();
    }

    public function addQuizz(): mixed
    {
        $categories = $this->categoryService->getAllCategories();
        $data = [];
        $data["categories"] = $categories;
        $data["csrf_token"] = $this->getCsrfToken();

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("add_quizz", "Ajouter un quizz", $data);
            }
           $result = $this->quizzService->addQuizz($_POST);
           $data["errors"] = $result["errors"] ?? [];
           $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("add_quizz", "Ajouter un quizz", $data);
    }
}

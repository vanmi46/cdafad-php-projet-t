<?php

namespace App\Controller;

use App\Service\CategoryService;

class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function addCategorie(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("add_category", "Ajouter une categorie", $data);
            }
            $result = $this->categoryService->saveCategory($_POST);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("add_category", "Ajouter une categorie", $data);
    }

    public function showAllCategories(): mixed
    {
        $categories = $this->categoryService->getAllCategories();
        return $this->renderComponent("all_categories", ["categories" => $categories]);
    }
}

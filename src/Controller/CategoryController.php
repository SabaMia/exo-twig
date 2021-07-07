<?php


namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route ("/categories", name="listCategories")
     * création de la première page avec sa route et la fonction qui renvoie au fichier twig correspondant
     */
    public function listCategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('list-categories.html.twig',[
            'categories' => $categories
        ]);
    }

    /**
     * @Route ("/category/{id}", name="showCategories")
     * création de la deuxième page avec sa route et la fonction qui renvoie au fichier twig correspondant
     * on insère l'id à chaque fois que le contenu doit correspondre à celui de l'article sélectionné
     */
    public function showCategories($id, CategoryRepository $categoryRepository)
    {
        // afficher l'article avec à partir de son id (wilcard dans l'url))
        $category = $categoryRepository->find($id);
        return $this->render('show-categorie.html.twig',[
                'category' => $category
        ]);
    }



}
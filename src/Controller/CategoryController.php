<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private $categories = [
        1 => [
            "title" => "Politique",
            "content" => "Tous les articles liés à Jean Lassalle",
            "id" => 1,
            "published" => true,
        ],
        2 => [
            "title" => "Economie",
            "content" => "Les meilleurs tuyaux pour avoir DU FRIC",
            "id" => 2,
            "published" => true
        ],
        3 => [
            "title" => "Securité",
            "content" => "Attention les étrangers sont très méchants",
            "id" => 3,
            "published" => false
        ],
        4 => [
            "title" => "Ecologie",
            "content" => "Hummer <3",
            "id" => 4,
            "published" => true
        ]
    ];


    /**
     * @Route ("/", name="listCategories")
     * création de la première page avec sa route et la fonction qui renvoie au fichier twig correspondant
     */
    public function listCategories()
    {
        return $this->render('list-categories.html.twig',[
            'categories' => $this-> categories
        ]);
    }

    /**
     * @Route ("/categorie/{id}", name="showCategories")
     * création de la deuxième page avec sa route et la fonction qui renvoie au fichier twig correspondant
     * on insère l'id à chaque fois que le contenu doit correspondre à celui de l'article sélectionné
     */
    public function showCategories($id)
    {
        return $this->render('show-categorie.html.twig',[
                'categorie' => $this-> categories[$id]
        ]);
    }



}
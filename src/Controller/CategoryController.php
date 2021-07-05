<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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



    /*
     * @Route ('/', name="listArticles")
     * création de la première page avec sa route et la fonction qui renvoie au fichier twig correspondant
     */
    public function listArticles()
    {
        return $this->render('list-articles.html.twig',[
            'article' => $this-> articles
        ]);
    }

    /*
     * @Route ('/article/{id}', name="showArticle")
     * création de la deuxième page avec sa route et la fonction qui renvoie au fichier twig correspondant
     * on insère l'id à chaque fois que le contenu doit correspondre à celui de l'article sélectionné
     */
    public function showArticles($id)
    {
        return $this->render('show-article.html.twig',[
                'article' => $this-> articles[$id]
        ]);
    }



}
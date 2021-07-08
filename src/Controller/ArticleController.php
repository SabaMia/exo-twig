<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articleList")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        //Pour faire un SELECT il faut utiliser ArticleRepository
        // il faut l'instancier avec l'autowire en argument du controleur puis de la variable
        $articles = $articleRepository->findAll();
        //je lui demande de la renvoyer à ma vue
        return $this->render('article_list.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/article/{id}", name="articleShow")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        // afficher l'article avec à partir de son id (wilcard dans l'url))
        $article = $articleRepository->find($id);

        //Erreur 404 si quelqu'un essaye de rentrer un id qui n'existe pas
        if (is_null($article)) {
            throw new NotFoundHttpException();
        }


        return $this->render('article_show.html.twig', [
            'article' => $article
        ]);
    }

}
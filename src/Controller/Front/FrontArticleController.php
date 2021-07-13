<?php


namespace App\Controller\Front;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        //Pour faire un SELECT il faut utiliser ArticleRepository
        // il faut l'instancier avec l'autowire en argument du controleur puis de la variable
        $articles = $articleRepository->findAll();
        //je lui demande de la renvoyer à ma vue
        return $this->render('front/article_list.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        // afficher l'article avec à partir de son id (wilcard dans l'url))
        $article = $articleRepository->find($id);

        //Erreur 404 si quelqu'un essaye de rentrer un id qui n'existe pas
        if (is_null($article)) {
            throw new NotFoundHttpException();
        }

        return $this->render('front/article_show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(ArticleRepository $articleRepository, Request $request)
    {
        //On commence par tester la route
        //dump('search');die;
        //On fait un test avec mot présent dans un article
        //$term = '6';
        //on modifie le $term pour qu'il récupère la méthode get du formulaire
        $term = $request->query->get('q');
        $articles = $articleRepository
            ->searchByTerm($term);
        return $this->render('front/article_search.html.twig',[
            'articles'=>$articles,
            'term'=>$term]);
        //Ensuite on va modifier le fichier ArticleRepository pour créer la requête
    }
}
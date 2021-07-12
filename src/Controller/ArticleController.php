<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles/insert", name="articleInsert")
     */
    public function insertArticle(EntityManagerInterface $entityManager)
    {
        //On utilise l'entité article pur créer un nouvel article en BDD
        //une instance de l'entité = un enregistrement dans la bdd
        $article = new Article();

        //On utilise les setters de l'entité article pour renseigner les valeurs des colonnes
        $article->setTitle('Titre article depuis le controleur');
        $article->setContent('blablalbla');
        $article->setIsPublished(true);
        $article->setCreationDate(new \DateTime('NOW'));

        // On récupère l'entitté créée ici et on la pré sauvegarde
        $entityManager->persist($article);

        // une fois toutes les entités pré sauvegardées on les insère en bdd
        $entityManager->flush();

        dump('ok'); die;
    }

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
        return $this->render('article_search.html.twig',[
            'articles'=>$articles,
            'term'=>$term]);
        //Ensuite on va modifier le fichier ArticleRepository pour créer la requête
    }


}
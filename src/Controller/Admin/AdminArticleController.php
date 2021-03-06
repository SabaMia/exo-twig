<?php


namespace App\Controller\Admin;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles/insert", name="admin_article_insert")
     */

    public function insertArticle(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {

        //On utilise l'entité article pur créer un nouvel article en BDD
        //une instance de l'entité = un enregistrement dans la bdd
        $article = new Article();
        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Article
        $articleForm = $this->createForm(ArticleType::class, $article);
        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $articleForm->handleRequest($request);

        // si le formulaire a été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre l'article
        // créé en bdd
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            /** @var UploadedFile $brochureFile */
            $imageFile = $articleForm->get('imageFile')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setImageFilename($newFilename);
            }



            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'L\'article ' . $article->getTitle() . ' a bien été créé !'
            );
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_article_list');
        }
        return $this->render('admin/admin_insert_article.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }


        /*public function insertArticle(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository,
                                  TagRepository $tagRepository)
    {*/
        //On utilise les setters de l'entité article pour renseigner les valeurs des colonnes
        /*$article->setTitle('Titre article depuis le controleur');
        $article->setContent('blablalbla');
        $article->setIsPublished(true);
        $article->setCreationDate(new \DateTime('NOW'));*/

        // je récupère la catégorie dont l'id est 1 en bdd
        // doctrine me créé une instance de l'entité category avec les infos de la catégorie de la bdd
        //$category = $categoryRepository->find(1);
        // j'associé l'instance de l'entité categorie récupérée, à l'instane de l'entité article que je suis
        // en train de créer
        /*$article->setCategory($category);

        $tag = $tagRepository->findOneBy(['title' => 'info']);

        if (is_null($tag)) {
            $tag = new Tag();
            $tag->setTitle("nouveau tag");
            $tag->setColor("purple");
        }
        $entityManager->persist($tag);

        $article->setTags($tag);

        // On récupère l'entité créée ici et on la pré sauvegarde
        $entityManager->persist($article);

        // une fois toutes les entités pré sauvegardées on les insère en bdd
        $entityManager->flush();*/

        //return $this->redirectToRoute('admin_article_list');
    //}

    /**
     * @Route("/admin/articles/update/{id}", name="admin_article_update")
     */
    // Pour créer une update, on créé une route puis la fonction
    public function updateArticle($id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        //On indique quel article il va récupérer grace au repository article et son id
        $article = $articleRepository->find($id);
        // pour modifier le titre
        //$article->setTitle('update du titre');
        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Article
        $articleForm = $this->createForm(ArticleType::class, $article);

        // persist pour pré sauvegarder et flush pour valider
        /*$entityManager->persist($article);
        $entityManager->flush();*/
        //$this->redirectToRoute('admin_article_list');

        // si le formulaire a été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre l'article
        // créé en bdd
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'L\'article '. $article->getTitle().' a bien été modifié !'
            );
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/admin_insert_article.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_article_delete")
     */
    //Pour supprimer un article à partir de l'id dans l'URL
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        //le repository pour récupérer l'article à partir de son id dans l'URL
        $article = $articleRepository->find($id);
        //permet de stocker en session un message flash,
        // dans le but de l'afficher sur la page suivante
        $this->addFlash(
            'success',
            'L\'article '. $article->getTitle().' a bien été supprimé !'
        );
        //puis les entity manager pour supprimer (remove) puis valider (flush)
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('admin_article_list');

    }

    /**
     * @Route("/admin/articles", name="admin_article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        //Pour faire un SELECT il faut utiliser ArticleRepository
        // il faut l'instancier avec l'autowire en argument du controleur puis de la variable
        $articles = $articleRepository->findAll();
        //je lui demande de la renvoyer à ma vue
        return $this->render('admin/admin_article_list.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="admin_article_show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        // afficher l'article avec à partir de son id (wilcard dans l'url))
        $article = $articleRepository->find($id);

        //Erreur 404 si quelqu'un essaye de rentrer un id qui n'existe pas
        if (is_null($article)) {
            throw new NotFoundHttpException();
        }

        return $this->render('admin/admin_article_show.html.twig', [
            'article' => $article
        ]);
    }
}

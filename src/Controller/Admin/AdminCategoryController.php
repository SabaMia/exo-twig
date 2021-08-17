<?php


namespace App\Controller\Admin;



use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class AdminCategoryController extends AbstractController
{

    /**
     * @Route("/admin/category/insert", name="admin_category_insert")
     */
    public function insertCategory(
        EntityManagerInterface $entityManager,
        Request $request )
    {
        // J'utilise l'entité Category, pour créer une nouvelle categorie en bdd
        // une instance de l'entité Category = un enregistrement de categorie en bdd
        $category = new Category();

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Category
        $categoryForm = $this->createForm(CategoryType::class, $category);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $categoryForm->handleRequest($request);

        // si le formulaire à été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre la catégorie créee dans la bdd
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'La catégorie '. $category->getTitle().' a bien été créée !'
            );

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute("admin_category_list");
        }

        return $this->render('admin/admin_insert_category.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }

    /**
     * @Route("/admin/categories/update/{id}", name="admin_category_update")
     */
    public function categoryUpdate(
        $id,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        Request $request)
    {
        //on va chercher la catégorie que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $category = $categoryRepository->find($id);

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Article
        $categoryForm = $this->createForm(CategoryType::class, $category);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $categoryForm->handleRequest($request);

        // si le formulaire a été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre l'article
        // créé en bdd puis on redirige vers la liste des articles
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'La catégorie '. $category->getTitle().' a bien été modifiée !'
            );

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute("admin_category_list");
        }

        return $this->render('admin/admin_insert_category.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);



    }

    /**
     * @Route("/admin/categories/delete/{id}", name="admin_category_delete")
     */
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        //on va chercher la catégorie que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $category = $categoryRepository->find($id);

        //permet de stocker en session un message flash,
        // dans le but de l'afficher sur la page suivante
        $this->addFlash(
            'success',
            'La catégorie '. $category->getTitle().' a bien été supprimée !'
        );

        //on supprime et on traduit l'ordre en requete SQL via le flush
        $entityManager->remove($category);
        $entityManager->flush();

        //on redirige l'utilisateur vers la page catégorieList une fois que les opérations sont terminées
        return $this->redirectToRoute("admin_category_list");
    }


    /**
     * @Route("/admin/categories", name="admin_category_list")
     */
    public function categoryList (CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/admin_category_list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/{id}", name="admin_category_show")
     */
    public function categoryShow ($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        // si le tag n'a pas été trouvé, je renvoie une exception (erreur)
        // pour afficher une 404
        if (is_null($category)) {
            throw new NotFoundHttpException();
        }

        /*on va chercher la page html twig et on l'interprete dans le navigateur
         on lui envoie les données du tableau pour pouvoir travailler dessus */
        return $this->render('Admin/admin_category_show.html.twig', [
            'category' => $category
        ]);
    }
}

<?php


namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;

use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminTagController extends AbstractController
{

    /**
     * @Route("/admin/tags",name="admin_tags_list")
     */
    public function tagList(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();
        return $this->render('admin/admin_tag_list.html.twig', [
            'tags' => $tags
    ]);
    }

    /**
     * @Route("/admin/tag/{id}", name="admin_tag_show")
     */
    public function tagShow($id, TagRepository $tagRepository)
    {
        $tag = $tagRepository->find($id);

        //Erreur 404 si quelqu'un essaye de rentrer un id qui n'existe pas
        if (is_null($tag)) {
            throw new NotFoundHttpException();
        }

        return $this->render('admin/admin_tag_show.html.twig',[
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/admin/tags/insert", name="admin_tag_insert")
     */
    public function insertTag(EntityManagerInterface $entityManager,
                                   Request $request)
    {
        $tag = new Tag();
        /*$tag->setTitle("nouveau tag");
        $tag->setColor("purple");*/

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Tag
        $tagForm = $this->createForm(TagType::class, $tag);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $tagForm->handleRequest($request);

        /*
                $entityManager->persist($tag);
                $entityManager->flush();*/

        // si le formulaire à été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre le tag crée dans la bdd
        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'Le tag '. $tag->getTitle().' a bien été crée !'
            );
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute("admin_tags_list");
        }

        return $this->render('admin/admin_insert_tag.html.twig', [
            'tagForm' => $tagForm->createView()
        ]);
    }

    /**
     * @Route("/admin/tags/update/{id}", name="adminTagUpdate")
     */
    public function tagUpdate(
        $id,
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository,
        Request $request)
    {
        //on va chercher le tag que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $tag = $tagRepository->find($id);

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Tag
        $tagForm = $this->createForm(TagType::class, $tag);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $tagForm->handleRequest($request);

        // si le formulaire à été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre le tag crée dans la bdd
        if ($tagForm->isSubmitted() && $tagForm->isValid()) {

            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'Le tag '. $tag->getTitle().' a bien été modifié !'
            );

            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute("admin_tags_list");
        }

        return $this->render('admin/admin_insert_tag.html.twig', [
            'tagForm' => $tagForm->createView()
        ]);
    }
    /**
     * @Route("/admin/tags/delete/{id}", name="adminTagDelete")
     */
    public function deleteTag($id, TagRepository $tagRepository, EntityManagerInterface $entityManager)
    {
        //on va chercher le tag que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $tag = $tagRepository->find($id);

        //permet de stocker en session un message flash,
        // dans le but de l'afficher sur la page suivante
        $this->addFlash(
            'success',
            'Le tag '. $tag->getTitle().' a bien été supprimé !'
        );

        //on supprime et on traduit l'ordre en requete SQL via le flush
        $entityManager->remove($tag);
        $entityManager->flush();

        //on redirige l'utilisateur vers la page tagList une fois que les opérations sont terminées
        return $this->redirectToRoute("admin_tags_list");
    }
}
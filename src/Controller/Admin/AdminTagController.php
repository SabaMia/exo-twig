<?php


namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                                  TagRepository $tagRepository)
    {
        $tag = new Tag();
        $tag->setTitle("nouveau tag");
        $tag->setColor("purple");

        $entityManager->persist($tag);
        $entityManager->flush();

        return $this->redirectToRoute('admin_tags_list');

    }
}
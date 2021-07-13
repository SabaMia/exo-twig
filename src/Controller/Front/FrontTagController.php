<?php


namespace App\Controller\Front;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontTagController extends AbstractController
{

    /**
     * @Route("/tags",name="tags_list")
     */
    public function tagList(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();
        return $this->render('front/tag_list.html.twig', [
            'tags' => $tags
    ]);
    }

    /**
     * @Route("/tag/{id}", name="tag_show")
     */
    public function tagShow($id, TagRepository $tagRepository)
    {
        $tag = $tagRepository->find($id);

        //Erreur 404 si quelqu'un essaye de rentrer un id qui n'existe pas
        if (is_null($tag)) {
            throw new NotFoundHttpException();
        }

        return $this->render('front/tag_show.html.twig',[
            'tag' => $tag
        ]);
    }
}
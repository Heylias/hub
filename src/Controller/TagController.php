<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use App\Service\PaginationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @Route("/tags/{page<\d+>?1}", name="tags_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Tags::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('tags_index');
        return $this->render('tag/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/tag/{name}", name="tag_show")
     */
    public function display(Tags $tag)
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag
        ]);
    }
}

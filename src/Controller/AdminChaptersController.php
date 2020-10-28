<?php

namespace App\Controller;

use App\Form\ChapterType;
use App\Entity\Chapter;
use DateTime;
use App\Service\PaginationService;
use App\Repository\ChapterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminChaptersController extends AbstractController
{
    /**
     * @Route("/admin/chapters/{page<\d+>?1}", name="admin_chapters_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Chapter::class)
                    ->setPage($page)
                    ->setLimit(30)
                    ->setRoute('admin_chapters_index');

        return $this->render('admin/chapters/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/chapter/{id}/edit", name="admin_chapter_edit")
     *
     * @param Chapter $chapter
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function edit(Chapter $chapter, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(ChapterType::class, $chapter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $chapter->setAddedAt(new DateTime());

            $manager->persist($chapter);
            $manager->flush();

            return $this->redirectToRoute('admin_chapters_index');

            $this->addFlash(
                'success',
                "The chapter has been successfully edited ! "
            );

            return $this->redirectToRoute('admin_chapters_index');
        }

        return $this->render('admin/chapters/edit.html.twig',[
            'chapter' => $chapter,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/chapter/{id}/delete", name="admin_chapter_delete")
     *
     * @param Chapter $chapter
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Chapter $chapter, EntityManagerInterface $manager){
        $manager->remove($chapter);
        $manager->flush();

        $this->addFlash(
            'success',
            "Chapter <strong>{$chapter->getTitle()}</strong> has successfully been deleted"
        );

        return $this->redirectToRoute('admin_chapters_index');
    }
}
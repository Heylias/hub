<?php

namespace App\Controller;

use App\Form\FictionType;
use App\Entity\Fanfiction;
use App\Service\PaginationService;
use App\Repository\FanfictionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFictionsController extends AbstractController
{
    /**
     * @Route("/admin/fictions/{page<\d+>?1}", name="admin_fictions_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Fanfiction::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('admin_fictions_index');

        return $this->render('admin/fictions/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/fiction/{id}/edit", name="admin_fiction_edit")
     *
     * @param Fanfiction $fiction
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function edit(Fanfiction $fiction, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(FictionType::class, $fiction);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach($fiction->getTags() as $tag){
                $tag->addFiction($fiction);
                $manager->persist($tag);
            }
            
            $manager->persist($fiction);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "<strong>{$fiction->getTitle()}</strong> has successfully been edited"
            );
            
            return $this->redirectToRoute('admin_fictions_index');
        }

        return $this->render('admin/fictions/edit.html.twig',[
            'fiction' => $fiction,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/fiction/{id}/delete", name="admin_fiction_delete")
     *
     * @param Fanfiction $fiction
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Fanfiction $fiction, EntityManagerInterface $manager){
        if(count($fiction->getChapters()) > 0){
            $this->addFlash(
                'warning',
                "You cannot delete <strong>{$fiction->getTitle()}</strong> because it has chapters"
            );
        }else{
            $manager->remove($fiction);
            $manager->flush();

            $this->addFlash(
                'success',
                "Fanfiction <strong>{$fiction->getTitle()}</strong> has successfully been deleted"
            );
        }

        return $this->redirectToRoute('admin_fictions_index');
    }
}
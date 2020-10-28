<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTagsController extends AbstractController
{
    /**
     * @Route("/admin/tags/{page<\d+>?1}", name="admin_tags_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Tags::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('admin_tags_index');

        return $this->render('admin/tags/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/tag/{id}/edit", name="admin_tags_edit")
     *
     * @param Tags $tag
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function edit(Tags $tag, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash(
                'success',
                "The tag has been successfully updated"
            );
        }

        return $this->render('admin/tags/edit.html.twig',[
            'tag' => $tag,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/tag/{id}/delete", name="admin_tags_delete")
     *
     * @param Tags $tag
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Tags $tag, EntityManagerInterface $manager){
        if(count($tag->getFictions()) > 0){
            $this->addFlash(
                'warning',
                "You cannot delete <strong>{$tag->getName()}</strong> because it has fanfictions bound to it"
            );
        }else{
            $manager->remove($tag);
            $manager->flush();

            $this->addFlash(
                'success',
                "Tag <strong>{$tag->getName()}</strong> has successfully been deleted"
            );
        }

        return $this->redirectToRoute('admin_tags_index');
    }

    /**
     * @Route("/admin/tag/new", name="admin_tag_create")
     *
     * @param Tag $tag
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $tag = new Tags();
        
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your new tag has been successfully created ! "
            );
            
            return $this->redirectToRoute('admin_tags_index');
        }

        return $this->render('admin/tags/new.html.twig', [
           'myForm' => $form->createView()
        ]);
    }
}
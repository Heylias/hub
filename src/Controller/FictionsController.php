<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\FictionType;
use App\Entity\Fanfiction;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FictionsController extends AbstractController
{
    /**
     * @Route("/fictions/{page<\d+>?1}", name="fictions_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Fanfiction::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('fictions_index');
        return $this->render('fictions/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

      /**
     * @Route("/fiction/new", name="fiction_create")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $fiction = new Fanfiction();
        
        $form = $this->createForm(FictionType::class, $fiction);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            foreach($fiction->getFanficImages() as $image){
                $image->setFanfiction($fiction);
                $manager->persist($image);
            }

            $fiction->setAuthor($this->getUser());

            $manager->persist($fiction);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>{$fiction->getTitle()}</strong> has been successfully created ! "
            );

            return $this->redirectToRoute('fiction_show',[
                'id' => $fiction->getId()
            ]);
        }

        return $this->render('fictions/new.html.twig', [
           'myForm' => $form->createView()
        ]);

    }

    /**
     * @Route("/fiction/{id}/edit", name="fiction_edit")
     * @Security("(is_granted('ROLE_USER') and user === fiction.getAuthor()) or is_granted('ROLE_ADMIN')", message="You cannot edit a fiction that is not yours")
     *
     * @return Response
     */
    public function edit(Fanfiction $fiction, Request $request, EntityManagerInterface $manager){

        $originalTags = new ArrayCollection();

        foreach ($fiction->getTags() as $tag) {
            $originalTags->add($tag);
        }

        $form = $this->createForm(FictionType::class, $fiction);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            foreach($fiction->getFanficImages() as $image){
                $image->setFanfiction($fiction);
                $manager->persist($image);
            }

            foreach ($originalTags as $tag) {
                if (false === $fiction->getTags()->contains($tag)) {
                    $tag->getFictions()->removeElement($fiction);
    
                    $manager->persist($tag);
                }
            }

            foreach($fiction->getTags() as $tag){
                $tag->addFiction($fiction);
                $manager->persist($tag);
            }

            $manager->persist($fiction);

            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>{$fiction->getTitle()}</strong> has been edited successfully"
            );

            return $this->redirectToRoute('fiction_show',[
                'id' => $fiction->getId()
            ]);
        }

        return $this->render('fictions/edit.html.twig',[
            'fiction' => $fiction,
            "myForm" => $form->createView()
        ]);
    }

    /**
     * @Route("/fiction/{id}", name="fiction_show")
     */
    public function display(Fanfiction $fanfic, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setAuthor($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your comment has successfully been given"
            );

            return $this->redirectToRoute('fiction_show',[
                'id' => $comment->getId()
            ]);
        }

        return $this->render('fictions/show.html.twig', [
            'fanfic' => $fanfic
        ]);
    }
}
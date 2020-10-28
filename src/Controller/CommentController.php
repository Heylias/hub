<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    public function create(Request $request, EntityManagerInterface $manager){
        $comment = new Comment();
        
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setAuthor($this->getUser())
                    ->setCreationDate(new DateTime());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your comment has been successfully sent ! "
            );

            return $this->redirectToRoute('fiction_show',[
                'id' => $comment->getId()
            ]);
        }

        return $this->render('fictions/new.html.twig', [
           'myForm' => $form->createView()
        ]);

    }

    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreationDate(new DateTime());

            $manager->persist($comment);

            $manager->flush();

            $this->addFlash(
                'success',
                "Your comment has been edited successfully"
            );

            return $this->redirectToRoute('fiction_show',[
                'id' => $comment->getId()
            ]);
        }

        return $this->render('fictions/edit.html.twig',[
            'comment' => $comment,
            "myForm" => $form->createView()
        ]);
    }
}
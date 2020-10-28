<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Form\ChapterType;
use App\Entity\Fanfiction;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChapterController extends AbstractController
{
    /**
     * @Route("/fiction/{id}/upload", name="chapter_create")
     * @Security("(is_granted('ROLE_USER') and user === fiction.getAuthor()) or is_granted('ROLE_ADMIN')", message="You cannot upload a new chapter on a fiction that is not yours")
     */
    public function upload(Fanfiction $fiction, Request $request, EntityManagerInterface $manager)
    {
        $chapter = new Chapter();
        
        $form = $this->createForm(ChapterType::class, $chapter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $chapter->setFanfiction($fiction)
                    ->setAddedAt(new DateTime());

            $manager->persist($chapter);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your new chapter has been successfully uploaded ! "
            );

            return $this->redirectToRoute('fiction_show',[
                'id' => $fiction->getId()
            ]);
        }

        return $this->render('chapter/new.html.twig', [
           'myForm' => $form->createView()
        ]);
    }
}
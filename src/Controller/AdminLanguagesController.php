<?php

namespace App\Controller;

use App\Form\LanguageType;
use App\Entity\Language;
use DateTime;
use App\Service\PaginationService;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminLanguagesController extends AbstractController
{
    /**
     * @Route("/admin/languages/{page<\d+>?1}", name="admin_languages_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Language::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('admin_languages_index');

        return $this->render('admin/languages/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/language/{id}/edit", name="admin_language_edit")
     *
     * @param Language $language
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function edit(Language $language, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($language);
            $manager->flush();

            $this->addFlash(
                'success',
                "The language has been successfully edited ! "
            );

            return $this->redirectToRoute('admin_Languages_index');
        }

        return $this->render('admin/languages/edit.html.twig',[
            'language' => $language,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/language/{id}/delete", name="admin_language_delete")
     *
     * @param Language $language
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Language $language, EntityManagerInterface $manager){
        $manager->remove($language);
        $manager->flush();

        $this->addFlash(
            'success',
            "Language <strong>{$language->getName()}</strong> has successfully been deleted"
        );

        return $this->redirectToRoute('admin_languages_index');
    }

    /**
     * @Route("/admin/language/new", name="admin_language_create")
     *
     * @param Language $language
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $language = new Language();
        
        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($language);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your new language has been successfully created ! "
            );
            
            return $this->redirectToRoute('admin_languages_index');
        }

        return $this->render('admin/languages/new.html.twig', [
           'myForm' => $form->createView()
        ]);
    }
}
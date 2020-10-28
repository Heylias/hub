<?php

namespace App\Controller;

use App\Form\GenreType;
use App\Entity\Genre;
use App\Service\PaginationService;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminGenresController extends AbstractController
{
    /**
     * @Route("/admin/genres/{page<\d+>?1}", name="admin_genres_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Genre::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('admin_genres_index');

        return $this->render('admin/genres/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/genre/{id}/edit", name="admin_genre_edit")
     *
     * @param Genre $genre
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function edit(Genre $genre, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($genre);
            $manager->flush();

            $this->addFlash(
                'success',
                "The genre has been successfully edited ! "
            );

            return $this->redirectToRoute('admin_genres_index');
        }

        return $this->render('admin/genres/edit.html.twig',[
            'genre' => $genre,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/genre/{id}/delete", name="admin_genre_delete")
     *
     * @param Genre $genre
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Genre $genre, EntityManagerInterface $manager){
        $manager->remove($genre);
        $manager->flush();

        $this->addFlash(
            'success',
            "Genre <strong>{$genre->getName()}</strong> has successfully been deleted"
        );

        return $this->redirectToRoute('admin_genres_index');
    }

    /**
     * @Route("/admin/genre/new", name="admin_genre_create")
     *
     * @param Genre $genre
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $genre = new Genre();
        
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($genre);
            $manager->flush();

            $this->redirectToRoute('admin_genres_index');

            $this->addFlash(
                'success',
                "Your new genre has been successfully uploaded ! "
            );
        }

        return $this->render('admin/genres/new.html.twig', [
           'myForm' => $form->createView()
        ]);
    }
}
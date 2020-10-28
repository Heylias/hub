<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUsersController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(User::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('admin_users_index');
        
        return $this->render('admin/user/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AccountType::class, $user, [
            'validation_groups' => ['Default']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "The user has been successfully updated"
            );

            return $this->redirectToRoute("admin_users_index");
        }

        return $this->render('admin/user/edit.html.twig',[
            'user' => $user,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(User $user, EntityManagerInterface $manager){
        if(count($user->getFanfictions()) > 0){
            $this->addFlash(
                'danger',
                "You cannot delete <strong>{$user->getPseudonym()}</strong> because he has fanfictions"
            );
        }elseif(count($user->getComments()) > 0){
            $this->addFlash(
                'danger',
                "You cannot delete <strong>{$user->getPseudonym()}</strong> because he has comments"
            );
        }else{
            if(!empty($user->getUserImage())){
                unlink($this->getParameter('uploads_directory').'/'.$user->getUserImage());
            }
            $manager->remove($user);
    
            $this->addFlash(
                'success',
                "The user has successfully been removed"
            );
            $manager->flush();

        }
        return $this->redirectToRoute("admin_users_index");
    }
}
<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PaginationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/users/{page<\d+>?1}", name="users_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(User::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setRoute('users_index');
        return $this->render('user/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function display(User $user){
        return $this->render('account/show.html.twig', [
            'user' => $user
        ]);
    }
}

<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard_index")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        $users = $statsService->getUsersCount();
        $fictions = $statsService->getFictionsCount();
        $comments = $statsService->getCommentsCount();
        $tags = $statsService->getTagsCount();
        $chapters = $statsService->getChaptersCount();
        $genres = $statsService->getGenresCount();
        $languages = $statsService->getLanguagesCount();
        
        $bestFictions = $statsService->getFictionsStats('DESC');

        $worstFictions = $statsService->getFictionsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('users', 'fictions', 'comments', 'tags', 'chapters', 'genres', 'languages'),
            'bestFictions' => $bestFictions,
            'worstFictions' => $worstFictions
        ]);
    }
}

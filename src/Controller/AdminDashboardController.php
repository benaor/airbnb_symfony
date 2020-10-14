<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(StatsService $statsService)
    {

        $stats     = $statsService->getStats();
        $bestAds   = $statsService->getAdsStats('DESC', 5);
        $badAds    = $statsService->getAdsStats('ASC', 5);

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'badAds' => $badAds
        ]);
    }
}

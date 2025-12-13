<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
/**
 * Administration dashboard controller.
 *
 * This controller provides an overview page for administrators
 * with quick access to different sections of the application.
 */
class DashboardController extends AbstractController
{
    /**
     * Displays the administration dashboard homepage.
     *
     * @return Response HTTP response with the rendered dashboard view
     */
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('back/dashboard/index.html.twig', [
            'title' => 'Dashboard',
        ]);
    }
}
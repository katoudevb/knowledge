<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Main controller for the homepage.
 *
 * Handles only the display of the application's homepage.
 */
class HomeController extends AbstractController
{
    /**
     * Homepage of the website.
     *
     * Renders the `home/index.html.twig` template.
     *
     * @return Response HTTP response containing the rendered homepage
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}

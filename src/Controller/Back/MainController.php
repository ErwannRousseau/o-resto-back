<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/back/main", name="app_back_main")
     */
    public function index(): Response
    {
        $siteUrl = $_ENV["SITE_URL"];

        return $this->render('back/main/index.html.twig', [
            'controller_name' => 'MainController',
            'site_url' => $siteUrl
        ]);
    }
}

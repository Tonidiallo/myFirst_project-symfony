<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FirtController extends AbstractController
{
    #[Route('/firt', name: 'app_firt')]
    public function index(): Response
    {
        return $this->render('firt/index.html.twig', [
            'controller_name' => 'FirtController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Config\router;
use Symfony\Component\HttpFoundation\Request;

class FirtController extends AbstractController
{
    #[Route('/firt', name: 'app_firt')]
    public function index(): Response
    {
        // chercher au la abse de données vos users
        return $this->render('firt/index.html.twig', [
            'name' => 'DIALLO',
            'firstname' => 'ANTOINE'
        ]);
    }


    #[Route('/sayHello/{name}/{firstname}', name: 'app_sayhello')]
    public function sayHello(Request $request, $name, $firstname)
    {
        return $this->render('firt/hello.html.twig', [
            'nom' => $name,
            'prenom' => $firstname
        ]);
    }

    #[Route(
        '/multi/{entier1<\d+>}/{entier2<\d+>}',
        name: 'app_multiplication',

        )]
    public function multiplication($entier1, $entier2){
        $resultat = $entier1 * $entier2;
        return new Response( "<h1>$resultat</h1>");

    }

    #[Route('/template',name: 'app_template')]
    public function template(){
        return $this->render('template.html.twig');
    }

}

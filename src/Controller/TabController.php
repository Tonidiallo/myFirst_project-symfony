<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TabController extends AbstractController
{
    #[Route('/tab/{nb<\d+>?5}', name: 'tab')]
    public function index($nb)
    {
        $notes = [];
        for ($i = 0 ; $i<$nb ;$i++) {
            $notes[] = rand(0,20);
        }
        return $this->render('tab/index.html.twig', [
            'notes' => $notes,
        ]);
    }
    #[Route('/tab/users', name: 'app_users')]
    public function users()
    {
        $users = [
            ['firstname' => 'aymen', 'name' => 'sellaouti', 'age' => 39],
            ['firstname' => 'skander', 'name' => 'sellaouti', 'age' => 3],
            ['firstname' => 'souheib', 'name' => 'youssfi', 'age' => 59],
        ];
        return $this->render('tab/users.html.twig', [
            'users' => $users
        ]);
    }
}

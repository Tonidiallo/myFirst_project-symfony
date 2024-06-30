<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

//Préfixe de route

#[Route("/todo")]

abstract class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request)
    {

        // Affihcer le tableau todo
        $session = $request->getSession();

        if (!$session->has('todos')){

            $todos = [
                'achat'=>'Acheter une clé USB',
                'cours'=>'Finaliser mon cours',
                'correction'=>'Corriger mes examens'];

            $session->set ('todos',$todos);
            $this->addFlash('info', "La liste des todos vient d'être initialisée");

        }

        return $this->render('todo/index.html.twig');
    }

    #[Route(
       '/add/{name?test}/{content?test-content}',
       name: 'app_addTodo',

    )]
    public function addTodo(Request $request, $name, $content)
    {
        // Vérifier Existence d'un tablo Todo
        $session =$request->getSession();

        if($session->has('todos')){

            $todos = $session->get('todos');

            // Si oui afficher une erreur
           if(isset($todos[$name])){
               $this->addFlash('error', "le todo de l'id $name existe déjà dans la liste");

           } else{
               $todos[$name] = $content;
               $this->addFlash('success', "Le todo de l'id $name a été ajouté avec succés");
                $session->set('todos', $todos);
           }

        }else{
            $this->addFlash('error', "La liste des Todo n'est pas encore initialisée");

        }

        // Sinon
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/update/{name}/{content}', name: 'app_updateTodo')]
    public function UpdateTodo(Request $request, $name, $content)
    {
        // Vérifier Existence d'un tablo Todo
        $session =$request->getSession();

        if($session->has('todos')){

            $todos = $session->get('todos');

            // Si oui afficher une erreur
            if(!isset($todos[$name])){
                $this->addFlash('error', "le todo de l'id $name existe pas dans la liste");

            } else{
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo de l'id $name a été modifié avec succés");
                $session->set('todos', $todos);
            }

        }else{
            $this->addFlash('error', "La liste des Todo n'est pas encore initialisée");

        }

        // Sinon
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/delete/{name}', name: 'app_deleteTodo')]
    public function DeleteTodo(Request $request, $name)
    {
        // Vérifier Existence d'un tablo Todo
        $session =$request->getSession();

        if($session->has('todos')){

            $todos = $session->get('todos');

            // Si oui afficher une erreur
            if(!isset($todos[$name])){
                $this->addFlash('error', "le todo de l'id $name existe pas dans la liste");

            } else{
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo de l'id $name a été modifié avec succés");
            }

        }else{
            $this->addFlash('error', "La liste des Todo n'est pas encore initialisée");

        }

        // Sinon
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/reset', name: 'app_resetTodo')]
    public function ResetTodo(Request $request)
    {
        // Vérifier Existence d'un tablo Todo
        $session = $request->getSession();
        $session->remove('todos');

        return $this->redirectToRoute('app_todo');
    }


}

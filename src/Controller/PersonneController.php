<?php

namespace App\Controller;

use App\Entity\Personne;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/personne")]

 class PersonneController extends AbstractController
{
    #[Route('/', name: 'app_personne')]
    public function index(ManagerRegistry $doctrine) : Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return  $this->render('personne/index.html.twig',['personnes'=>$personnes] );
    }

     #[Route('/alls/age/{ageMin}/{ageMax}', name: 'app_personne_age')]
     public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response{
         $repository = $doctrine->getRepository(Personne::class);
         $personnes = $repository->findPersonneByAgeInterval($ageMin,$ageMax);
         return  $this->render('personne/index.html.twig',['personnes'=>$personnes] );
     }

     #[Route('/alls/{page?1}/{nbre?12}', name: 'app_personne_alls')]
     public function indexAlls(ManagerRegistry $doctrine, $page, $nbre) : Response{
         $repository = $doctrine->getRepository(Personne::class);

         $nbPersonne = $repository->count([]);
         $nbrePage = ceil($nbPersonne / $nbre);


         $personnes = $repository->findBy([],[], $nbre,offset:($page -1)*$nbre);

         return  $this->render('personne/index.html.twig',[
             'personnes'=>$personnes ,
             'isPagined'=>true,
             'nbrePage'=>$nbrePage,
             'page'=>$page,
             'nbre'=>$nbre
         ]);
     }

     #[Route('/{id}', name: 'app_personne_detail')]
     public function detail(Personne $personne = null) : Response{

         if(!$personne){
             $this->addFlash('error',"La personne n'existe pas");
             return $this->redirectToRoute('app_personne');
         }
         return  $this->render('personne/detail.html.twig',['personne'=>$personne] );
     }

    #[Route('/add', name: 'app_personne_add')]
    public function addPersonne(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirtname('ROSINE');
        $personne->setName('COLY');
        $personne->setAge('48');

        // Ajouter l'opération d'insert de la personne
        $entityManager->persist($personne);

        // Exécute la transaction
        $entityManager->flush();

        return $this->render('personne/detail.html.twig', [
            'personne' => $personne ,
        ]);
    }

     #[Route('/delete/{id}', name: 'app_personne_delete')]
     public function deletePersonne(Personne $personne=null, ManagerRegistry $doctrine): RedirectResponse
     {
            if($personne){
                // La personne existe
                $manager = $doctrine->getManager();
                $manager->remove($personne);
                $manager->flush();
                $this->addFlash('success',"La personne a été supprimée avec succés");
            }else{
                // Elle n'existe pas
                $this->addFlash('error',"DELETE : La personne n'existe pas");
            }
         return $this->redirectToRoute('app_personne_alls');
     }

     #[Route('/update/{id}/{name}/{firtname}/{age}', name: 'app_personne_update')]
     public function updatePersonne(Personne $personne=null,$name,$firtname,$age, ManagerRegistry $doctrine): RedirectResponse
     {
         if($personne){
             $personne->setName($name);
             $personne->setFirtname($firtname);
             $personne->setAge($age);

             $manager = $doctrine->getManager();
             $manager->persist($personne);
             $manager->flush();

             $this->addFlash('success',"La personne a été mise à jour avec succés");
         }else{
             $this->addFlash('error',"UPDATE : La personne n'existe pas");
         }
         return $this->redirectToRoute('app_personne_alls');
     }
}

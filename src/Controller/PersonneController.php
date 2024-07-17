<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Service\Helpers;
use App\Service\MailerService;
use App\Service\UploaderService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Psr\Log\LoggerInterface;


#[Route('/personne')]

 class PersonneController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private Helpers $helper) {
        
    }
    #[Route('/', name: 'app_personne')]
    public function index(ManagerRegistry $doctrine) : Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return  $this->render('personne/index.html.twig',['personnes'=>$personnes] );
    }

     #[Route('/alls/age/{ageMin}/{ageMax}', name: 'app_personne_age')]
     public function personneByAge(ManagerRegistry $doctrine, 
                                    $ageMin, 
                                    $ageMax) : Response{

         $repository = $doctrine->getRepository(Personne::class);
         $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);
         return  $this->render('personne/index.html.twig',['personnes'=>$personnes] );
     
        }

     #[Route('/alls/{page?1}/{nbre?12}', name: 'app_personne_alls')]
     public function indexAlls(ManagerRegistry $doctrine, 
                                $page, 
                                $nbre
                              ) : Response{
         
        
         echo $this->helper->sayCc();   

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

     #[Route('/detail/{id}', name: 'app_personne_detail')]
     public function detail(Personne $personne = null) : Response{

         if(!$personne){
             $this->addFlash('error',"La personne n'existe pas");
             return $this->redirectToRoute('app_personne');
         }
         return  $this->render('personne/detail.html.twig',['personne'=>$personne] );
     }
 

    #[Route('/edit/{id?0}', name: 'app_personne_edit')]
    public function addPersonne(Personne $personne=null, 
                                    ManagerRegistry $doctrine, 
                                    Request $request,
                                    UploaderService $uploaderService,
                                    MailerService $mailer
                                ): Response
    {
        $new = false;
        if(!$personne){
            $new = true;
           $personne = new Personne();
        } 
        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();
            if ($photo) {

                $directory = $this->getParameter('personne_directory');
                $personne->setImage($uploaderService->uploadFile($photo, $directory));
            
            }

            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            if($new){
                $message=" a été ajouté avec succès";
                
            }else{
                $message= " a été mis à jour avec succès";
            }

            $mailMessage = $personne->getFirtname().' '.$personne->getName().' '.$message;
            $this->addFlash('success',  $personne->getName().$message);
            $mailer->sendEmail(content: $mailMessage);

            return $this->redirectToRoute("app_personne_alls");

        }else{
            return $this->render('personne/add-personne.html.twig', [
                'form'=> $form->createView(),
                'new' => $new,
            ]);
        }

       
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

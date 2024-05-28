<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Job;
use App\Form\PersonneType;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PersonneRepository;
use App\Service\PdfService;
use App\Service\UploaderService;
use PhpParser\Node\Expr\Cast\String_;
use App\Event\AddPersonneEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('personne')]
class PersonneController extends AbstractController

{

    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ){

    }

#[Route('/' ,name : 'personne.list')]
public function index(ManagerRegistry $doctrine):Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
     return  $this->render('personne/index.html.twig',[
         'personnes' => $personnes
        
     ]);
    }
    #[Route('/pdf/{id<\d+>}', name: 'personne.pdf')]
    public function generatePdfPersonne(Personne $personne = null, PdfService $pdf)
    {
        $html = $this->renderView('personne/details.html.twig', ['personne' => $personne]);
        $pdf->ShowPdfFille($html);
    }


#[
    Route('/alls/{page?1}/{nbre?12}' ,name : 'personne.list.alls'),
    IsGranted('ROLE_USER')

]
public function alls(ManagerRegistry $doctrine,$page,$nbre):Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findBy([],[],$nbre,($page-1)*$nbre);
        
        $nbPersone = $repository->count([]);

        $nbrePage =ceil($nbPersone / $nbre) ;




     return  $this->render('personne/index.html.twig',[
         'personnes' => $personnes ,
         'isPaginated'=>true,
         'nbrePage'=>$nbrePage,
         'page'=>$page
        
     ]);
    }
#[Route('/{id<\d+>}' ,name : 'personne.detail'),
IsGranted('ROLE_USER')
 ]
public function detail(ManagerRegistry $doctrine ,$id):Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);


        if(!$personne){
            $this->addFlash('error' , "la personne d'id $id n'existe pas ");

            return $this->redirectToRoute('personne.list');
         
        }
     return  $this->render('personne/details.html.twig',[


         'personne' => $personne
        
     ]);
    }
    #[Route('/edit/{id?0}', name: 'personne.edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editPersonne(
        ManagerRegistry $doctrine,
        Request $request,
        Personne $personne = null,
        UploaderService $uploaderService,
        MailerService $mailer
    ): Response {
        $isNewPersonne = false; // Indicates if this is a new Personne or an existing one being edited
    
        if (!$personne) {
            $isNewPersonne = true;
            $personne = new Personne();
        }
    
        $form = $this->createForm(PersonneType::class, $personne);
    
        // Remove fields not needed in the form
        $form->remove('createdAt');
        $form->remove('updatedAt');
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()&& $form->isValid() ) {
            $photo = $form->get('photo')->getData();
    
            if ($photo) {
                // Upload and set the image if a new photo is provided
                $directory = $this->getParameter('personne_directory');
                $personne->setImage($uploaderService->uploadFile($photo, $directory));
            }
    
            // Set createdBy only for new Personne entities
            if ($isNewPersonne) {
                $personne->setCreatedBy($this->getUser());
            }
    
            $manager = $doctrine->getManager();
            $profileId = $request->request->get('profile_id');
            $personne->setProfile($profileId);
            $manager->persist($personne);
            $manager->flush();
    
            $message = $isNewPersonne ? "a été ajouté avec succès" : "a été mis à jour avec succès";
            $this->addFlash('success', $personne->getName() . " " . $message);
           
            // $mailMessage = $personne->getFirstname() ." ". $personne->getname(). " ".$message ; 
            // $mailer->sendEmail(content:$mailMessage);
          
            if ($isNewPersonne) {
                $addPersonneEvent = new AddPersonneEvent($personne);
                $this->dispatcher->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
            }
            return $this->redirectToRoute('personne.list.alls');
        }
    
        // If form is not submitted or not valid, render the form template
        return $this->render('personne/add-personne.html.twig', [
            'form' => $form->createView()
        ]);
    }



#[
    Route('/delete/{name}' , name:'personne.delete'),
    IsGranted('ROLE_ADMIN')
]
public function deletePersonne(Personne $personne =null,ManagerRegistry $doctrine , $name):RedirectResponse{
    
// recupérer la  personne 

      if($personne){
       
        // si la personne existe => le suprime retourne un message e flash
        $manager =$doctrine->getManager();
        // ajouteer la focntion du suprission dans la transaction 
        $manager->remove($personne);
        //le methde dlash il excute la supprission sur la basse de 
         $manager->flush();

         $this->addFlash('success', "la personne a ete supprimer avec succés $name ") ;
      }
         else{
            $this->addFlash('error', "la personne innexistante") ;
         }

    return $this->redirectToRoute('personne.list.alls');

} 
#[Route('/update/{id}/{name}/{firstname}/{age}', "personne.update") ]
public function update(Personne $personne = null ,ManagerRegistry $doctrine, $name,$firstname,$age):RedirectResponse
{
    if($personne){
        $personne->setName($name);
        $personne->setFirstname($firstname);
        $personne->getAge($age);
        $manager = $doctrine->getManager();
        $manager->persist($personne);
        $manager->flush();
          $this->addFlash(
             'success',
             'la personne a ete modifer avec sucess'
          );
    }
    else{
        $this->addFlash('error', "la personne innexistante") ;
     }

return $this->redirectToRoute('personne.list.alls');
}


#[Route("/alls/age/{ageMin}/{ageMax}","personne.age")]
public function personneByAge(ManagerRegistry $doctrine,$ageMin,$ageMax):Response
{

$repository=$doctrine->getRepository(Personne::class);
$personnes= $repository->findPersonneByAgeInterval($ageMin,$ageMax);


return  $this->render('personne/index.html.twig',[
    'personnes' => $personnes
   
]);



}

#[Route("/alls/age/{ageMin}/{ageMax}","personne.agee")]
public function statsPersonneByAgeh(ManagerRegistry $doctrine,$ageMin,$ageMax):Response
{

$repository=$doctrine->getRepository(Personne::class);
$stats= $repository->statsPersonneByAge($ageMin,$ageMax);


return  $this->render('personne/stats.html.twig',[
    'stats' => $stats,
    'ageMin'=>$ageMin,
    'ageMax'=>$ageMax
]);



}

}


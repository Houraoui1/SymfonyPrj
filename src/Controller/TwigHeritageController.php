<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TwigHeritageController extends AbstractController
{
    #[Route('/twig/', name: 'app_twig_heritage')]
    public function index(): Response
    {
        return $this->render('Heritage/Heritage.html.twig', [
            'controller_name' => 'TwigHeritageController',
        ]);
    }
    #[Route('twig/heritage' , name:'heritage')]
    public function heritage(): Response
    {
        // Définition des variables à envoyer au modèle Twig
        $data = [
            'variable1' => 'valeur1',
            'variable2' => 'valeur2',
            // Ajoutez d'autres variables si nécessaire
        ];
    
        // Rendre la page index.html.twig avec les variables spécifiées
        return $this->render('heritage.html.twig', $data);
    }
}

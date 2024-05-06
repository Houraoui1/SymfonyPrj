<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;



class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session =$request->getSession() ;

        // Check if the session key 'nbVisite' exists
        if ($session->has('nbVisite')) {
            // If it exists, get the current count and increment it
            $nbVisite = $session->get('nbVisite') + 1;
        } else {
            // If the session key doesn't exist, initialize it with a count of 1
            $nbVisite = 1;
        }

        // Update the session with the new or incremented count
        $session->set('nbVisite', $nbVisite);

        // Pass the nbVisite value to the Twig template for rendering
        return $this->render('session/index.html.twig', [
            'nbVisite' => $nbVisite,
        ]);
    }
}
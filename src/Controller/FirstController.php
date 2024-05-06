<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class FirstController extends AbstractController
{
    #[Route('/tamplate', name: 'tamplate')]
    public function index(): Response
    {
        return $this->render('tamplate.html.twig');
    }

    #[Route('/first',name:'firstcall')]
    public function first() :Response
    {
    return $this->render('first/index.html.twig' , [
        'nom'=>"aymen",
        'prenom'=>"sellaouti",
    ]);
    }

    // #[Route('sayHello/{name}/{firstname}' , name:'say.hello')]
    public function sayHello (Request $request , $name , $firstname) :Response
    {
    return $this->render('first/hello.html.twig' , [
        'nom'=>$name,
        'prenom'=>$firstname,
    ]);
    }
   
    
}

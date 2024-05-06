<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaController extends AbstractController
{
    #[Route('/tap/{np?5<\d+>}', name: 'tap')]
    public function index($np): Response
    { 
        $tableNote = [];

        for ($i = 0; $i < $np; $i++) {
            $note = rand(0, 20);
            $tableNote[] = $note;
        }

        return $this->render('tap/index.html.twig', [
            'notes'=> $tableNote
        ]);
    }

    #[Route('/users', name: 'tap.user')]
    public function users(): Response 
    {
        $users = [
            ['firstname' => 'aymen', 'name' => 'sellaouti', 'age' => 39],
            ['firstname' => 'ahmed', 'name' => 'mahmoud', 'age' => 3],
            ['firstname' => 'yassine', 'name' => 'youssefi', 'age' => 59]
        ];

        return $this->render('tap/users.html.twig', [
            'users' => $users
        ]);
    }
}
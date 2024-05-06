<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ToDoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        
        // Check if todos exist in session, if not, initialize it
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter cle usb',
                'cours' => 'finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todos', $todos);
        } else {
            // If todos exist, fetch them from the session
            $todos = $session->get('todos');
        }
    
        return $this->render('to_do/index.html.twig', [
            'todos' => $todos,
        ]);
    }

    #[Route ('/todo/add/{name}/{content}' , name:'todo.add')]
    public function addTodo(Request $request, $name, $content):RedirectResponse
    {
        $session = $request->getSession();

        // Check if todos exist in session
        if ($session->has('todos')) {
            $todos = $session->get('todos');

            // Check if the todo with the given name already exists
            if (isset($todos[$name])) {
                $this->addFlash('error', "Le todo d'id $name existe déjà.");
            } else {
                // Add the new todo to the list
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été ajouté avec succès.");
            }
        } else {
            // If todos don't exist in session, show an error message
            $this->addFlash('error', "La liste des todos n'existe pas.");
        }

        return $this->redirectToRoute('todo');
    }

    #[Route ('/todo/update/{name}/{content}' , name:'todo.update')]
    public function updateTodo(Request $request, $name, $content):RedirectResponse
    {
        $session = $request->getSession();

        // Check if todos exist in session
        if ($session->has('todos')) {
            $todos = $session->get('todos');

            // Check if the todo with the given name exists
            if (!isset($todos[$name])) {
                $this->addFlash('error', "Le todo d'id $name n'existe pas.");
            } else {
                // Update the content of the todo
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été modifié avec succès.");
            }
        } else {
            // If todos don't exist in session, show an error message
            $this->addFlash('error', "La liste des todos n'existe pas.");
        }

        return $this->redirectToRoute('todo');
    }

    #[Route ('/todo/delete/{name}' , name:'todo.delete')]
    public function deleteTodo(Request $request, $name):RedirectResponse
    {
        $session = $request->getSession();

        // Check if todos exist in session
        if ($session->has('todos')) {
            $todos = $session->get('todos');

            // Check if the todo with the given name exists
            if (isset($todos[$name])) {
                // Remove the todo from the list
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été supprimé avec succès.");
            } else {
                $this->addFlash('error', "Le todo d'id $name n'existe pas.");
            }
        } else {
            // If todos don't exist in session, show an error message
            $this->addFlash('error', "La liste des todos n'existe pas.");
        }

        return $this->redirectToRoute('todo');
    }

}
<?php

namespace App\Controller;

use App\Entity\Todos; // Importation de la classe Todos
use App\Entity\TodosList; // Importation de la classe TodosList
use App\Form\TodosListType; // Importation de la classe de formulaire pour TodosList
use App\Form\TodosType; // Importation de la classe de formulaire pour Todos
use App\Repository\TodosListRepository; // Importation du repository pour TodosList
use Doctrine\ORM\EntityManagerInterface; // Importation de l'EntityManager
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Importation de la classe de base des contrôleurs
use Symfony\Component\HttpFoundation\Request; // Importation de la classe Request
use Symfony\Component\HttpFoundation\Response; // Importation de la classe Response
use Symfony\Component\Routing\Annotation\Route; // Importation de l'annotation Route

class TodosListController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function homepage(): Response
    {
        return $this->render('homepage.html.twig', [
            'message' => 'Bienvenue sur l\'application de gestion de tâches !',
        ]);
    }

    #[Route('/todos-lists', name: 'todos_list_index', methods: ['GET'])]
    public function index(TodosListRepository $todosListRepository): Response
    {
        $todosLists = $todosListRepository->findAll();

        return $this->render('todos_list/index.html.twig', [
            'todosLists' => $todosLists,
        ]);
    }

    #[Route('/todos-lists/create', name: 'todos_list_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todosList = new TodosList();
        $form = $this->createForm(TodosListType::class, $todosList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($todosList);
            $entityManager->flush();

            return $this->redirectToRoute('todos_list_index');
        }

        return $this->render('todos_list/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/todos-lists/{id}/add-task', name: 'todos_add_task', methods: ['GET', 'POST'])]
    public function addTask(TodosList $todosList, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Todos();
        $task->setTodosList($todosList);

        $form = $this->createForm(TodosType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('todos_list_index');
        }

        return $this->render('todos_list/add_task.html.twig', [
            'todosList' => $todosList,
            'form' => $form->createView(),
        ]);
    }
}

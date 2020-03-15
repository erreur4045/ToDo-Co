<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function listAction()
    {
        /** @var AuthorizationChecker $securityContext */
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('ROLE_ADMIN'))
            $tasks = $this->getDoctrine()
                ->getRepository('AppBundle:Task')
                ->findBy(['user' => [null, 'anonyme']]);
        else
            $tasks = $this->getDoctrine()
                ->getRepository('AppBundle:Task')
                ->findBy(['user' => $this->get('security.token_storage')
                    ->getToken()
                    ->getUser()]);

        return $this->render(
            'task/list.html.twig',
            [
                'tasks' => $tasks
            ]
        );
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        $user = $this->get('security.token_storage')
            ->getToken()
            ->getUser();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setUser($user);
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function deleteTaskAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}

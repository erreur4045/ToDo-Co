<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $isAuthorised = $this->clientIsAuthorised($task);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if (
                $this->clientIsAuthorised($task)
                xor$this->isAdminAndTaskUserIsNull($task)
                xor $this->isAdminAndTaskUserIsAnonymous($task)
                )
            return $this->editTaskTraitment();
        }

        if ($this->isAdminAndTaskUserIsNull($task)
            xor $this->isAdminAndTaskUserIsAnonymous($task)
            xor $isAuthorised) {
            return $this->render('task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);
        }
        else{
            return $this->redirectToRoute('task_list');
        }
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function toggleTaskAction(Task $task)
    {
        if (
            $this->clientIsAuthorised($task)
            xor $this->isAdminAndTaskUserIsNull($task)
            xor $this->isAdminAndTaskUserIsAnonymous($task)
        )
            $this->changeToggleStatus($task);
        else
            $this->addFlash('warning', 'Vous n\'avais pas les autorisations pour modifier cette tache.');
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function deleteTaskAction(Task $task)
    {
        if (
            $this->clientIsAuthorised($task)
            xor $this->isAdminAndTaskUserIsNull($task)
            xor $this->isAdminAndTaskUserIsAnonymous($task)
        )
            $this->deleteTask($task);
        else
            $this->addFlash('warning', 'Vous n\'avais pas les autorisation pour supprimer cette tache.');

        return $this->redirectToRoute('task_list');
    }

    /**
     * @param Task $task
     */
    private function changeToggleStatus(Task $task): void
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
    }

    /**
     * @param Task $task
     */
    private function deleteTask(Task $task): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        $this->addFlash('success', 'La tâche a bien été supprimée.');
    }

    /**
     * @return RedirectResponse
     */
    private function editTaskTraitment(): RedirectResponse
    {
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'La tâche a bien été modifiée.');

        return $this->redirectToRoute('task_list');
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function isAdminAndTaskUserIsNull(Task $task): bool
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            && $task->getUser() === null;
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function isAdminAndTaskUserIsAnonymous(Task $task): bool
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            && $task->getUser() === 'anonymous';
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function clientIsAuthorised(Task $task): bool
    {
        if ($task->getUser() == $this->getUser()) {
            $isAuthorised = true;
        } else {
            $isAuthorised = false;
        }
        return $isAuthorised;
    }
}

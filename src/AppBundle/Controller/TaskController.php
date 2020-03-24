<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\TaskType;
use AppBundle\Repository\TaskRepositoy;
use Doctrine\ORM\ORMException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController
{
    /** @var AuthorizationCheckerInterface */
    private $userChecker;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var FlashBagInterface */
    private $flashBag;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;
    /** @var Environment */
    private $environment;
    /** @var FormFactoryInterface */
    private $formFactory;
    /** @var TaskRepositoy */
    private $taskRepo;

    /**
     * TaskController constructor.
     * @param AuthorizationCheckerInterface $userChecker
     * @param TokenStorageInterface $tokenStorage
     * @param FlashBagInterface $flashBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param Environment $environment
     * @param FormFactoryInterface $formFactory
     * @param TaskRepositoy $taskRepo
     */
    public function __construct(
        AuthorizationCheckerInterface $userChecker,
        TokenStorageInterface $tokenStorage,
        FlashBagInterface $flashBag,
        UrlGeneratorInterface $urlGenerator,
        Environment $environment,
        FormFactoryInterface $formFactory,
        TaskRepositoy $taskRepo
    ) {
        $this->userChecker = $userChecker;
        $this->tokenStorage = $tokenStorage;
        $this->flashBag = $flashBag;
        $this->urlGenerator = $urlGenerator;
        $this->environment = $environment;
        $this->formFactory = $formFactory;
        $this->taskRepo = $taskRepo;
    }

    /**
     * @Route("/tasks", name="task_list")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function listAction()
    {
        /** @var User $user */
        $user =  $this->tokenStorage->getToken()->getUser();
        if ($this->userChecker->isGranted('ROLE_ADMIN')) {
            $tasks = $this->taskRepo->getTaskAdminRole($user);
        } else {
            $tasks = $this->taskRepo->getTaskUserRole($user);
        }
        return new Response(
            $this->environment->render(
                'task/list.html.twig',
                [
                    'tasks' => $tasks
                ]
            )
        );
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createAction(Request $request)
    {
        /** @var Task $task */
        $task = new Task();
        $form = $this->formFactory->create(TaskType::class, $task);
        /** @var User $user */
        $user =  $this->tokenStorage->getToken()->getUser();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->taskRepo->addTask($task, $user);
            $this->flashBag->add('success', 'La tâche a été bien été ajoutée.');
            return new RedirectResponse($this->urlGenerator->generate('task_list'));
        }

        return new Response($this->environment->render(
            'task/create.html.twig',
            [
                'form' => $form->createView()
            ]
        ));
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->formFactory->create(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($this->isAuthorised($task)) {
                $this->taskRepo->editTaskTreatment();
                $this->flashBag->add('success', 'La tâche a bien été modifiée.');
                $this->urlGenerator->generate('task_list');
                return new RedirectResponse($this->urlGenerator->generate('task_list'));
            }
        }

        if ($this->isAuthorised($task)) {
            return new Response(
                $this->environment->render(
                    'task/edit.html.twig',
                    [
                        'form' => $form->createView(),
                        'task' => $task,
                    ]
                )
            );
        }
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function toggleTaskAction(Task $task)
    {
        if ($this->isAuthorised($task)) {
            $this->taskRepo->changeToggleStatus($task);
            $this->flashBag->add('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }
        return new RedirectResponse($this->urlGenerator->generate('task_list'));
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function deleteTaskAction(Task $task)
    {
        if ($this->isAuthorised($task)) {
            $this->taskRepo->deleteTask($task);
            $this->flashBag->add('success', 'La tâche a bien été supprimée.');
        } else {
            $this->flashBag->add('warning', 'Vous n\'avais pas les autorisation pour supprimer cette tache.');
        }

        return new RedirectResponse($this->urlGenerator->generate('task_list'));
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function isAdminAndTaskUserIsNull(Task $task): bool
    {
        return $this->userChecker->isGranted('ROLE_ADMIN')
            && $task->getUser() === null;
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function isAdminAndTaskUserIsAnonymous(Task $task): bool
    {
        return $this->userChecker->isGranted('ROLE_ADMIN')
            && $task->getUser() === 'anonymous';
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function clientIsAuthorised(Task $task): bool
    {
        if ($task->getUser() == $this->tokenStorage->getToken()->getUser()) {
            $isAuthorised = true;
        } else {
            $isAuthorised = false;
        }
        return $isAuthorised;
    }

    /**
     * @param Task $task
     * @return bool
     */
    private function isAuthorised(Task $task): bool
    {
        return $this->clientIsAuthorised($task)
            xor $this->isAdminAndTaskUserIsNull($task)
            xor $this->isAdminAndTaskUserIsAnonymous($task);
    }
}

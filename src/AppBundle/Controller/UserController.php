<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

class UserController
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var AuthorizationCheckerInterface */
    private $userChecker;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var FlashBagInterface */
    private $flashBag;
    /** @var RouterInterface */
    private $router;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;
    /** @var Environment */
    private $environment;
    /** @var FormFactoryInterface */
    private $formFactory;
    /** @var UserRepository */
    private $userRepo;

    /**
     * UserController constructor.
     * @param EntityManagerInterface $em
     * @param AuthorizationCheckerInterface $userChecker
     * @param TokenStorageInterface $tokenStorage
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     * @param UrlGeneratorInterface $urlGenerator
     * @param Environment $environment
     * @param FormFactoryInterface $formFactory
     * @param UserRepository $userRepo
     */
    public function __construct(
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $userChecker,
        TokenStorageInterface $tokenStorage,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        UrlGeneratorInterface $urlGenerator,
        Environment $environment,
        FormFactoryInterface $formFactory,
        UserRepository $userRepo
    ) {
        $this->em = $em;
        $this->userChecker = $userChecker;
        $this->tokenStorage = $tokenStorage;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->urlGenerator = $urlGenerator;
        $this->environment = $environment;
        $this->formFactory = $formFactory;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/users", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction()
    {
        return new Response($this->environment->render(
            'user/list.html.twig',
            [
                'users' => $this->userRepo->getUsers()
            ]
        ));
    }

    /**
     * @Route("/users/create", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->userRepo->addUser($user);

            $this->flashBag->add(
                'success',
                "L'utilisateur a bien été ajouté."
            );
            return new RedirectResponse($this->urlGenerator->generate('user_list'));
        }

        return new Response($this->environment->render(
            'user/create.html.twig',
            [
                'form' => $form->createView()
            ]
        ));
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit", requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->userRepo->editUser($user);

            $this->flashBag->add(
                'success',
                "L'utilisateur a bien été modifié"
            );

            return new RedirectResponse($this->urlGenerator->generate('user_list'));
        }

        return new Response(
            $this->environment->render(
                'user/edit.html.twig',
                [
                'form' => $form->createView(),
                'user' => $user
                ]
            )
        );
    }
}

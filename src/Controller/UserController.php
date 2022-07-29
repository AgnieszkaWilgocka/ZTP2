<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordType;
use App\Repository\UserRepository;
use App\Service\UserServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 */
#[Route('user')]
class UserController extends AbstractController
{

    private UserServiceInterface $userService;

    /**
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        name: 'user_index', methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->userService->getPaginatedList(
            $request->query->getInt('page', 1),
        );

        return $this->render('user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @param User $user
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'user_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param UserPasswordHasherInterface $passwordHasher
     *
     * @return Response
     */
    #[Route(
        '/{id}/edit',
        name: 'change_password',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    public function changePassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user, ['method' => 'PUT',
        'action' => $this->generateUrl('change_password',
            ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->userService->save($user);

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'user/change_password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user
            ]
        );
    }
}
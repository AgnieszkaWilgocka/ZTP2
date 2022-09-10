<?php
/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\Type\RegistrationType;
use App\Repository\UserrDataRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * Function register.
     *
     * @param Request                     $request            HTTP Request
     * @param UserRepository              $userRepository     User repository
     * @param UserrDataRepository         $userDataRepository UserData repository
     * @param UserPasswordHasherInterface $passwordHasher     Password hasher
     * @param UserAuthenticatorInterface  $userAuthenticator  User authenticator
     * @param LoginFormAuthenticator      $formAuthenticator  Form authenticator
     *
     * @return Response|null HTTP Response
     */
    #[Route(
        '/registration',
        name: 'app_register',
        methods: 'GET|POST'
    )]
    public function register(Request $request, UserRepository $userRepository, UserrDataRepository $userDataRepository, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $formAuthenticator): ?Response
    {
        $user = new User();
        $userData = new UserData();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles([User::ROLE_USER]);
            $user->setUserData($userData);
            $userRepository->save($user);
            $userDataRepository->save($userData);

            return $userAuthenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request
            );
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}

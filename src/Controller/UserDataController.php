<?php

namespace App\Controller;

use App\Entity\UserData;
use App\Form\Type\UserDataType;
use App\Repository\UserDataRepository;
use App\Service\UserDataServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserDataController
 */
#[Route(
    '/userData'
)]
class UserDataController extends AbstractController
{

    private UserDataServiceInterface $userDataService;

    private TranslatorInterface $translator;

    public function __construct(UserDataServiceInterface $userDataService, TranslatorInterface $translator)
    {
        $this->userDataService = $userDataService;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param UserData $userData
     *
     * @return Response
     */
    #[Route(
        '/{id}/edit',
        name: 'user_data_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    public function edit(Request $request, UserData $userData): Response
    {
        $form = $this->createForm(UserDataType::class, $userData, ['method' => 'PUT',
            'action' => $this->generateUrl('user_data_edit',
            ['id' => $userData->getId()]),
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userDataService->save($userData);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );
            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'userData/edit.html.twig',
            [
                'form' => $form->createView(),
                'userData' => $userData
            ]
        );
    }
}
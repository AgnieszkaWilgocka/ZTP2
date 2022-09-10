<?php
/**
 * UserData controller
 */
namespace App\Controller;

use App\Entity\UserData;
use App\Form\Type\UserDataType;
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

    /**
     * UserData Service Interface
     *
     * @var UserDataServiceInterface
     */
    private UserDataServiceInterface $userDataService;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * Constructor
     *
     * @param UserDataServiceInterface $userDataService UserData service
     * @param TranslatorInterface      $translator      Translator
     */
    public function __construct(UserDataServiceInterface $userDataService, TranslatorInterface $translator)
    {
        $this->userDataService = $userDataService;
        $this->translator = $translator;
    }

    /**
     * Function edit user data
     *
     * @param Request  $request  HTTP Request
     * @param UserData $userData UserData entity
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
            'action' => $this->generateUrl(
                'user_data_edit',
                ['id' => $userData->getId()]
            ),
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
                'userData' => $userData,
            ]
        );
    }
}

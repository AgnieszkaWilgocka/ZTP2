<?php
/**
 * Favourite controller.
 */

namespace App\Controller;

use App\Entity\Favourite;
use App\Form\Type\FavouriteType;
use App\Service\FavouriteService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FavouriteController.
 */
#[Route(
    '/favourite'
)]
class FavouriteController extends AbstractController
{
    /**
     * Favourite Service Interface.
     */
    private FavouriteService $favouriteService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param FavouriteService    $favouriteService Favourite service
     * @param TranslatorInterface $translator       Translator
     */
    public function __construct(FavouriteService $favouriteService, TranslatorInterface $translator)
    {
        $this->favouriteService = $favouriteService;
        $this->translator = $translator;
    }

    /**
     * Function index.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        name: 'favourite_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->favouriteService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render('favourite/own.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Function create favourite.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     *
     * @IsGranted("ROLE_USER")
     */
    #[Route(
        '/create',
        name: 'favourite_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        $favourite = new Favourite();
        $user = $this->getUser();
        $form = $this->createForm(FavouriteType::class, $favourite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $favourite->setAuthor($user);
            $this->favouriteService->save($favourite);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('favourite_index');
        }

        return $this->render(
            'favourite/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Function delete favourite.
     *
     * @param Request   $request   HTTP Request
     * @param Favourite $favourite Favourite entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}/delete',
        name: 'favourite_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    public function delete(Request $request, Favourite $favourite): Response
    {
        if ($favourite->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('category_index');
        }
        $form = $this->createForm(FormType::class, $favourite, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('favourite_delete', ['id' => $favourite->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->favouriteService->delete($favourite);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('favourite_index');
        }

        return $this->render(
            'favourite/delete.html.twig',
            [
                'form' => $form->createView(),
                'favourite' => $favourite,
            ]
        );
    }
}

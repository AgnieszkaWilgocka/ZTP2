<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\categoryType;
use App\Service\CategoryService;
use App\Service\CategoryServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Category Controller
 */

#[Route('/category')]
class CategoryController extends AbstractController
{
    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    private CategoryServiceInterface $categoryService;

    /**
     * Constructor
     *
     * @param TranslatorInterface $translator
     *
     * @param CategoryService $categoryService
     */
    public function __construct(TranslatorInterface $translator, CategoryServiceInterface $categoryService)
    {
        $this->translator = $translator;
        $this->categoryService = $categoryService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route(
        name: 'category_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->categoryService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('category/index.html.twig', ['pagination' => $pagination]);
    }



    /**
     * Show action
     *
     * @param Category $category
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'category_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Category $category): Response
    {
        return $this->render(
            'category/show.html.twig',
            ['category' => $category]
        );
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/create',
        name: 'category_create', methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(categoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Category $category
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/{id}/edit',
        name: 'category_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(categoryType::class, $category, ['method' => 'PUT',
            'action' => $this->generateUrl('category_edit',
            ['id'=> $category->getId()]),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_succeessfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Category $category
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}/delete',
    name: 'category_delete',
    requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Category $category): Response
    {
        if(!$this->categoryService->canBeDeleted($category)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.category_constains_books')
            );

            return $this->redirectToRoute('category_index');
        }




        $form = $this->createForm(FormType::class, $category, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('category_delete', ['id' => $category->getId()])
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
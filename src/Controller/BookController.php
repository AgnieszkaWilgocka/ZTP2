<?php

/**
 * Book controller
 */
namespace App\Controller;

use App\Entity\Comment;
use App\Form\Type\BookType;
use App\Form\Type\CommentType;
use App\Service\BookServiceInterface;
use App\Service\CommentService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Book;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Book Controller
 */

#[Route('book')]
class BookController extends AbstractController
{
    /**
     * Book Service Interface
     *
     * @var BookServiceInterface
     */
    private BookServiceInterface $bookService;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    private CommentService $commentService;

    /**
     * Constructor
     *
     * @param BookServiceInterface $bookService    Book Service
     * @param TranslatorInterface  $translator     Translator
     * @param CommentService       $commentService Comment Service
     */
    public function __construct(BookServiceInterface $bookService, TranslatorInterface $translator, CommentService $commentService)
    {
        $this->bookService = $bookService;
        $this->translator = $translator;
        $this->commentService = $commentService;
    }

    /**
     * Function index
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        name: 'book_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->bookService->getPaginatedList(
            $request->query->getInt('page', '1'),
            $filters
        );

        return $this->render('book/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Function get filters
     *
     * @param Request $request HTTP Request
     *
     * @return int[]
     *
     * @psalm-return array{tag_id: int}
     */
    public function getFilters(Request $request): array
    {
        $filters = [];
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');
        $filters['category_id'] = $request->query->getInt('filters_category_id');

        return $filters;
    }

    /**
     * Function show book
     *
     * @param Request $request HTTP Request
     * @param Book    $book    Book entity
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    #[Route(
        '/{id}',
        name: 'book_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function show(Request $request, Book $book): Response
    {
        $comment = new Comment();
        $id = $book->getId();
        $author = $this->getUser();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBook($book);
            $comment->setAuthor($author);
            $this->commentService->save($comment);

            return $this->redirectToRoute('book_show', ['id' => $id]);
        }

        return $this->render(
            'book/show.html.twig',
            ['book' => $book,
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * Function create book
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/create',
        name: 'book_create',
        methods: 'POST|GET'
    )]
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Function edit book
     *
     * @param Request $request HTTP Request
     * @param Book    $book    Book entity
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/{id}/edit',
        name: 'book_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(
            BookType::class,
            $book,
            [
            'method' => 'PUT',
            'action' => $this->generateUrl('book_edit', ['id' => $book->getId()]),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/edit.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Function delete book
     *
     * @param Request $request HTTP Request
     * @param Book    $book    Book entity
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/{id}/delete',
        name: 'book_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    public function delete(Request $request, Book $book): Response
    {
        if ($book->getTags()->count()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.book_contains_tags')
            );

            return $this->redirectToRoute('book_index');
        }

        if ($book->getComments()->count()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.book_contains_comments')
            );

            return $this->redirectToRoute('book_index');
        }

        $form = $this->createForm(FormType::class, $book, [
            'method' => 'DELETE',
                'action' => $this->generateUrl('book_delete', ['id' => $book->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->delete($book);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/delete.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }
}

<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\Type\BookType;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
use App\Service\BookService;
use App\Service\BookServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Book;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    private BookServiceInterface $bookService;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(BookServiceInterface $bookService, TranslatorInterface $translator)
    {
        $this->bookService = $bookService;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route(
        name: 'book_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->bookService->getPaginatedList(
            $request->query->getInt('page', '1')
        );

        return $this->render('book/index.html.twig', ['pagination'=> $pagination]);
    }

    /**
     * @param Book $book
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'book_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function show(Request $request, Book $book, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $id = $book->getId();
        $author = $this->getUser();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBook($book);
            $comment->setAuthor($author);
            $commentRepository->save($comment);

            return $this->redirectToRoute('book_show', ['id' => $id]);
        }

        return $this->render(
            'book/show.html.twig',
            ['book' => $book,
                'form' => $form->createView()
                ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/create',
        name: 'book_create', methods: 'POST|GET'
    )]
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

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
     * @param Request $request
     * @param Book $book
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
        $form = $this->createForm(BookType::class, $book,
        [
            'method' => 'PUT',
            'action' => $this->generateUrl('book_edit', ['id' => $book->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
                'book' => $book
            ]
        );

    }

    /**
     * @param Request $request
     * @param Book $book
     *
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route(
        '/{id}/delete',
        name: 'book_delete',
        requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE'
    )]
    public function delete(Request $request, Book $book): Response
    {
//        if(!$this->bookService->canBeDeleted($book)) {
//            $this->addFlash(
//                'warning',
//                $this->translator->trans('message.book_contains_categories')
//            );
//
//            return $this->redirectToRoute('book_index');
//        }
        $form = $this->createForm(FormType::class, $book, [
            'method' => 'DELETE',
                'action' => $this->generateUrl('book_delete', ['id' => $book->getId()])
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
                'book' => $book
            ]
        );
    }
}
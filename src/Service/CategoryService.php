<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
    private CategoryRepository $categoryRepository;

    private PaginatorInterface $paginator;

    private BookRepository $bookRepository;

    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator, BookRepository $bookRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
        $this->bookRepository = $bookRepository;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Category $category)
    {
        $this->categoryRepository->save($category);
    }

    public function delete(Category $category)
    {
        $this->categoryRepository->delete($category);
    }

    public function canBeDeleted(Category $category): bool
    {
       try {
           $result = $this->bookRepository->countByCategory($category);

           return !($result > 0);
       } catch (NoResultException|NonUniqueResultException) {
           return false;
       }
    }
}
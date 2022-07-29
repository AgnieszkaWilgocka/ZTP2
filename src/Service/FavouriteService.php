<?php

namespace App\Service;

use App\Entity\Favourite;
use App\Entity\User;
use App\Repository\FavouriteRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class FavouriteService implements FavouriteServiceInterface
{
    private FavouriteRepository $favouriteRepository;

    private PaginatorInterface $paginator;

    public function __construct(FavouriteRepository $favouriteRepository, PaginatorInterface $paginator)
    {
        $this->favouriteRepository = $favouriteRepository;
        $this->paginator = $paginator;
    }

    public function save(Favourite $favourite)
    {
        $this->favouriteRepository->save($favourite);
    }

    public function delete(Favourite $favourite)
    {
        $this->favouriteRepository->delete($favourite);
    }

    public function getPaginatedList(int $page, User $author): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->favouriteRepository->queryByAuthor($author),
            $page,
            FavouriteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
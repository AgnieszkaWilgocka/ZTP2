<?php
/**
 * Favourite service.
 */

namespace App\Service;

use App\Entity\Favourite;
use App\Entity\User;
use App\Repository\FavouriteRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class FavouriteService.
 */
class FavouriteService implements FavouriteServiceInterface
{
    /**
     * Favourite repository.
     */
    private FavouriteRepository $favouriteRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param FavouriteRepository $favouriteRepository Favourite repository
     * @param PaginatorInterface  $paginator           Paginator
     */
    public function __construct(FavouriteRepository $favouriteRepository, PaginatorInterface $paginator)
    {
        $this->favouriteRepository = $favouriteRepository;
        $this->paginator = $paginator;
    }

    /**
     * Action save.
     *
     * @param Favourite $favourite Favourite
     */
    public function save(Favourite $favourite)
    {
        $this->favouriteRepository->save($favourite);
    }

    /**
     * Action delete.
     *
     * @param Favourite $favourite Favourite
     */
    public function delete(Favourite $favourite)
    {
        $this->favouriteRepository->delete($favourite);
    }

    /**
     * Paginated list.
     *
     * @param int  $page   Page
     * @param User $author Author
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedList(int $page, User $author): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->favouriteRepository->queryByAuthor($author),
            $page,
            FavouriteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}

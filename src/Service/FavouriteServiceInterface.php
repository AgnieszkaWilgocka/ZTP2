<?php
/**
 * FavouriteService interface.
 */

namespace App\Service;

use App\Entity\Favourite;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

// use Knp\Component\Pager\PaginatorInterface;

/**
 * Class FavouriteServiceInterface.
 */
interface FavouriteServiceInterface
{
    /**
     * Paginated list.
     *
     * @param int  $page Page
     * @param User $user User
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface;

    /**
     * Action save.
     *
     * @param Favourite $favourite Favourite
     *
     * @return mixed Mixed
     */
    public function save(Favourite $favourite);

    /**
     * Action delete.
     *
     * @param Favourite $favourite Favourite
     *
     * @return mixed mixed
     */
    public function delete(Favourite $favourite);
}

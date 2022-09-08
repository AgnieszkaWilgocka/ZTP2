<?php
/**
 * FavouriteService interface
 */
namespace App\Service;

use App\Entity\Favourite;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

//use Knp\Component\Pager\PaginatorInterface;

/**
 * Class FavouriteServiceInterface
 */
interface FavouriteServiceInterface
{
    /**
     * Paginated list
     *
     * @param int  $page
     * @param User $user
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface;

    /**
     * Action save
     *
     * @param Favourite $favourite
     *
     * @return mixed
     */
    public function save(Favourite $favourite);

    /**
     * Action delete
     *
     * @param Favourite $favourite
     *
     * @return mixed
     */
    public function delete(Favourite $favourite);
}

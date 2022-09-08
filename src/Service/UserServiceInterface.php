<?php
/**
 * UserService interface
 */
namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class UserServiceInterface
 */
interface UserServiceInterface
{
    /**
     * Paginated list
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save
     *
     * @param User $user
     *
     * @return mixed
     */
    public function save(User $user);
}

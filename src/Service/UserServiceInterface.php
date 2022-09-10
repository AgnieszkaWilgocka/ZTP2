<?php
/**
 * UserService interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Paginated list.
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save.
     *
     * @param User $user User
     *
     * @return mixed Mixed
     */
    public function save(User $user);
}

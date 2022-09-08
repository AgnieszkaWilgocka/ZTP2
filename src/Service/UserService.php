<?php
/**
 * User service
 */
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService
 *
 */
class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    private PaginatorInterface $paginator;

    /**
     * Constructor
     *
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * Paginated list
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Action save
     *
     * @param User $user
     *
     * @return void
     */
    public function save(User $user)
    {
        $this->userRepository->save($user);
    }

    /**
     * Action delete
     *
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user)
    {
        $this->userRepository->delete($user);
    }
}

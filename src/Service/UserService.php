<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    private PaginatorInterface $paginator;

    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(User $user)
    {
        $this->userRepository->save($user);
    }

    public function delete(User $user)
    {
        $this->userRepository->delete($user);
    }
}
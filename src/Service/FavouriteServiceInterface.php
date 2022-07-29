<?php

namespace App\Service;

//use Paginato
use App\Entity\Favourite;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;
//use Knp\Component\Pager\PaginatorInterface;

interface FavouriteServiceInterface
{
    public function getPaginatedList(int $page, User $user): PaginationInterface;
    public function save(Favourite $favourite);
    public function delete(Favourite $favourite);
}

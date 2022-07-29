<?php

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CategoryServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;
    public function save(Category $category);
    public function delete(Category $category);
    public function canBeDeleted(Category $category): bool;
}
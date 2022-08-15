<?php

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface
 */
interface TagServiceInterface
{
    public function getPaginatedList(int $page):PaginationInterface;
    public function save(Tag $tag);
    public function delete(Tag $tag);
    public function findOneByTitle(string $title): ?Tag;
}
<?php
/**
 * TagService interface
 */
namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface
 */
interface TagServiceInterface
{
    /**
     * Paginated list
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page):PaginationInterface;

    /**
     * Action save
     *
     * @param Tag $tag
     *
     * @return mixed
     */
    public function save(Tag $tag);

    /**
     * Action delete
     *
     * @param Tag $tag
     *
     * @return mixed
     */
    public function delete(Tag $tag);

    /**
     * Find One By Title
     *
     * @param string $title
     *
     * @return Tag|null
     */
    public function findOneByTitle(string $title): ?Tag;

    /**
     * Find One By Id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function findOneById(int $id);
}

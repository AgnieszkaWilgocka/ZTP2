<?php
/**
 * TagService interface.
 */

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * Paginated list.
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination inferface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save.
     *
     * @param Tag $tag Tag
     *
     * @return mixed Mixed
     */
    public function save(Tag $tag);

    /**
     * Action delete.
     *
     * @param Tag $tag Tag
     *
     * @return mixed Mixed
     */
    public function delete(Tag $tag);

    /**
     * Find One By Title.
     *
     * @param string $title Title
     *
     * @return Tag|null Tag
     */
    public function findOneByTitle(string $title): ?Tag;

    /**
     * Find One By Id.
     *
     * @param int $id Id
     *
     * @return mixed Mixed
     */
    public function findOneById(int $id);
}

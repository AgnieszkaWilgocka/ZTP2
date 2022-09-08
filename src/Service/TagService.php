<?php
/**
 * Tag service
 */
namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService
 *
 */
class TagService implements TagServiceInterface
{
    private TagRepository $tagRepository;

    private PaginatorInterface $paginator;

    /**
     * Constructor
     *
     * @param TagRepository      $tagRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
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
            $this->tagRepository->queryAll(),
            $page,
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save
     *
     * @param Tag $tag
     *
     * @return void
     */
    public function save(Tag $tag)
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete
     *
     * @param Tag $tag
     *
     * @return void
     */
    public function delete(Tag $tag)
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Find One By Title
     *
     * @param string $title
     *
     * @return Tag|null
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    /**
     * Find One By Id
     *
     * @param int $id
     *
     * @return Tag|null
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}

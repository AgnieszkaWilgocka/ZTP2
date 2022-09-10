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
    /**
     * Tag repository
     *
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * Paginator
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor
     *
     * @param TagRepository      $tagRepository Tag repository
     * @param PaginatorInterface $paginator     Paginator
     */
    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->paginator = $paginator;
    }

    /**
     * Paginated list
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination interface
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
     * @param Tag $tag Tag
     *
     */
    public function save(Tag $tag)
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete
     *
     * @param Tag $tag Tag entity
     *
     */
    public function delete(Tag $tag)
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Find One By Title
     *
     * @param string $title Title
     *
     * @return Tag|null Tag
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    /**
     * Find One By Id
     *
     * @param int $id Id
     *
     * @return Tag|null Tag
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}

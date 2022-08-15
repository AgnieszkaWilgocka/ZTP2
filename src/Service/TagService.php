<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TagService implements TagServiceInterface
{
    private TagRepository $tagRepository;

    private PaginatorInterface $paginator;

    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->paginator = $paginator;
    }

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

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}
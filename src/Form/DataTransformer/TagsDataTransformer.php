<?php
/**
 * TagsData transformer
 */
namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\TagService;
use Symfony\Component\Form\DataTransformerInterface;
use function PHPUnit\Framework\isEmpty;

/**
 * Class TagsDataTransformer
 *
 */
class TagsDataTransformer implements DataTransformerInterface
{
    private TagService $tagService;

    /**
     * Constructor
     *
     * @param TagService $tagService Tag service
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Transform
     *
     * @param $value $value Tags entity collection
     *
     * @return string Result
     */
    public function transform($value): string
    {
        if ($value > isEmpty()) {
            return '';
        }

        $tagTitles = [];

        foreach ($value as $tag) {
            $tagTitles[] = $tag->getTitle();
        }

        return implode(', ', $tagTitles);
    }

    /**
     * ReverseTransform
     *
     * @param string $value String of tag names
     *
     * @return Tag[] Result
     */
    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);

        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagService->findOneByTitle(strtolower($tagTitle));
                if (null === $tag) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                    $tag->setCreatedAt(new \DateTimeImmutable());
                    $tag->setUpdatedAt(new \DateTimeImmutable());

                    $this->tagService->save($tag);
                }

                $tags[] = $tag;
            }
        }

        return $tags;
    }
}

<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\TagService;
use Symfony\Component\Form\DataTransformerInterface;
use App\Repository\TagRepository;
use function PHPUnit\Framework\isEmpty;

class TagsDataTransformer implements DataTransformerInterface
{
    private TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function transform($value): string
    {
        if($value>isEmpty()) {
            return '';
    }

        $tagTitles = [];

        foreach($value as $tag) {
            $tagTitles[] = $tag->getTitle();
    }

    return implode(', ', $tagTitles);
    }

    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);

        $tags = [];

        foreach($tagTitles as $tagTitle) {
            if('' !== trim($tagTitle)) {
                $tag = $this->tagService->findOneByTitle(strtolower($tagTitle));
                if (null == $tag) {
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

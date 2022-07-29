<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use Symfony\Component\Form\DataTransformerInterface;
use App\Repository\TagRepository;

class TagsDataTransformer implements DataTransformerInterface
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function transform($value): string
    {
        if($value->isEmpty()) {
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
                $tag = $this->tagRepository->findOneByTitle(strtolower($tagTitle));
                if (null == $tag) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);

                    $this->tagRepository->save($tag);
                }

                $tags[] = $tag;
            }
        }

        return $tags;
    }
}

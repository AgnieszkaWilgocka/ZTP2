<?php

namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FavouriteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'book',
            EntityType::class,
            [
                'class' => Book::class,
                'choice_label' => function($book): string {
                    return $book->getTitle();
                },
                'label' => 'label.book',
                'placeholder' => 'label_none',
                'required' => true
            ]
        );
    }
}
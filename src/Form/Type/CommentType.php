<?php

namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'content',
            TextType::class,
            [
                'label' => 'label_text',
                'attr' => ['max_length' => 64],
                'required' => true,
            ]
        );

//        $builder->add(
//            'book',
//            EntityType::class,
//            [
//                'class' => Book::class,
//                'choice_label' => function(Book $book): string {
//                    return $book->getTitle();
//                },
//                'required' => true,
//            ]
//        );
    }
}
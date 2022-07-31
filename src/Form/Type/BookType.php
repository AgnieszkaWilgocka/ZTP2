<?php

namespace App\Form\Type;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * class BookType
 */
class BookType extends AbstractType
{
    /**
     * Tags data transformer
     *
     * @var TagsDataTransformer
     */
    private TagsDataTransformer $tagsDataTransformer;


    /**
     * Constructor
     *
     * @param TagsDataTransformer $tagsDataTransformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'label_title',
                'required' => true,
                'attr' => ['max_length' => 64]
            ]
        );

        $builder->add(
            'author',
            TextType::class,
            [
                'label' => 'label_author',
                'required' => true,
                'attr' => ['max_length' => 64]
            ]
        );

        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function($category): string {
                    return $category->getTitle();
                },
                'label' => 'label.category',
                'placeholder' => 'label.none',
                'required' => true,
            ]
        );

        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label_tags',
                'required' => false,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Book::class]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'book';
    }
}
<?php
/**
 * Favourite type.
 */

namespace App\Form\Type;

use App\Entity\Book;
use App\Entity\Favourite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FavouriteType.
 */
class FavouriteType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder builder Builder
     * @param array                $options options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'book',
            EntityType::class,
            [
                'class' => Book::class,
                'choice_label' => function ($book): string {
                    return $book->getTitle();
                },
                'label' => 'label.book',
                'placeholder' => 'label.none',
                'required' => true,
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Favourite::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'favourite';
    }
}

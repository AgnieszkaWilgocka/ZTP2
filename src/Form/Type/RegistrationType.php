<?php
/**
 * Registration type
 */
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 */
class RegistrationType extends AbstractType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'email',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'required' => true,
            ]
        );
    }
}

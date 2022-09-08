<?php
/**
 * ChangePassword type
 */
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ChangePasswordType
 */
class ChangePasswordType extends AbstractType
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
            'password',
            RepeatedType::class,
            [
               'type' => PasswordType::class,
               'invalid_message' => 'The password fields must match',
               'first_options' => ['label' => 'Password'],
               'second_options' => ['label' => 'Repeat Password'],
            ]
        );
    }
}

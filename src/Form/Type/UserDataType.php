<?php

namespace App\Form\Type;

use App\Entity\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add(
           'nick',
           TextType::class,
           [
               'label' => 'label_nick',
               'required' => true,
               'attr' => ['max_length' => 64]
           ]
       );
    }

    /**
     * Configure the options for this type
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => UserData::class]);
    }

    /**
     * Returns the prefix of the template block name for this type
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'userData';
    }
}

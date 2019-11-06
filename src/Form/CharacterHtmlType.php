<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterHtmlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kind', TextType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('caste', TextType::class, array(
                'required' => false,
            ))
            ->add('knowledge', TextType::class, array(
                'required' => false,
            ))
            ->add('intelligence', IntegerType::class, array(
                'required' => false,
            ))
            ->add('life', IntegerType::class, array(
                'required' => false,
            ))
            ->add('image', TextType::class, array(
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}

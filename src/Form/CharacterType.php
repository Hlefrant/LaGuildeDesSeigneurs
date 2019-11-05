<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('caste', TextType::class)
            ->add('knowledge', TextType::class)
            ->add('intelligence', IntegerType::class)
            ->add('life', IntegerType::class)
            ->add('image', TextType::class)
            ->add('kind', TextType::class)
            ->add('creation', DateTimeType::class)
            ->add('identifier', TextType::class)
            ->add('Modification', DateTimeType::class)
            ->add('player', EntityType::class, [
                // looks for choices from this entity
                'class' => Player::class ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}

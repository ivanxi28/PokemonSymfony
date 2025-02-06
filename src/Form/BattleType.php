<?php

namespace App\Form;

use App\Entity\Battle;
use App\Entity\Pokemon;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BattleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pokemonWinner')
            ->add('player', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('pokemonPlayer', EntityType::class, [
                'class' => Pokemon::class,
                'choice_label' => 'id',
            ])
            ->add('pokemonWild', EntityType::class, [
                'class' => Pokemon::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Battle::class,
        ]);
    }
}

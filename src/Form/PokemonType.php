<?php

namespace App\Form;

use App\Entity\Pokedex;
use App\Entity\Pokemon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('name')
            ->add('tipo')
            ->add('image')
            ->add('level')
            ->add('strong')
            ->add('pokedexes', EntityType::class, [
                'class' => Pokedex::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}

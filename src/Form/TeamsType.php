<?php

namespace App\Form;

use App\Entity\Teams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('country')
            ->add('moneyBalance')
        ;
        $builder->add('players', CollectionType::class, [
            'entry_type' => PlayersType::class,
            'entry_options' => ['label' => false],
            'prototype' => true,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true
        ]);
        $builder->add('save', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ])
    ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teams::class,
        ]);
    }
}

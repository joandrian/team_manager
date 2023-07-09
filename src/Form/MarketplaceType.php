<?php

namespace App\Form;

use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MarketplaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('seller', EntityType::class, [
            'class' => Teams::class,
            'choice_label' => 'name',
            'mapped' => false,
            'placeholder' => 'Seller Team ?'
        ]);

        $builder->add('buyer', EntityType::class, [
            'class' => Teams::class,
            'choice_label' => 'name',
            'mapped' => false,
            'placeholder' => 'Buyer Team ?'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teams::class,
        ]);
    }
}

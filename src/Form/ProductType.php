<?php

namespace App\Form;

use App\Entity\Tax;
use App\Entity\Product;
use App\Entity\Manufacturer;
use App\Form\ProductImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('manufacturer', EntityType::class, ['class' => Manufacturer::class])
            ->add('reference')
            ->add('EAN13')
            ->add('price')
            ->add('weight')
            ->add('description')
            ->add('vat', EntityType::class, ['class' => Tax::class])
            ->add('qty', NumberType::class, ['label' => 'quantity', 'html5' => true,'mapped' => false])
            ->add('product_images', CollectionType::class, [
                'entry_type' => ProductImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

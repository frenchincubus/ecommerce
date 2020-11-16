<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Address')
            ->add('street_number')
            ->add('zipcode')
            ->add('city')
            ->add('country', CountryType::class)
            ->add('customer_id', EntityType::class, [
                'class'   => Customer::class,
                'choice_label' => function($customer) {
                    return $customer;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
